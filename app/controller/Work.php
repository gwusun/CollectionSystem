<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/15
 * Time: 20:35
 */

namespace app\controller;

use app\model\Classes;
use app\model\HomeWork;
use app\model\User;
use app\model\WorkItem;
use Sunwu\Errmsg\CommonField;
use Sunwu\Errmsg\ErrMaps;
use Sunwu\Errmsg\Helper;
use think\Controller;
use think\Exception;
use think\Request;

class Work extends Base
{
    private $commonField;
    private $worksModel;
    private $userModel;
    private $itemModel;
    private $classModel;


    //字段
    private $createTime = '';

    //系统编码
    private $sysCharset = CommonField::gbk;

    //文件保存路径
    private $saveDir = '';

    //文件名称
    private $fileName;

    /*
      自动命名规则
      学号A|姓名B|作业名成C|时间D
      短学号:Z
      20150107030131-孙武-计算机网络实验报告-2017-08-01.doc
     */
    private $renameRule = 'ABCD';

    /**
     * @var array 命名规则
     * A 学号
     * B 姓名
     * C 作业名称
     * D 日期
     * Z 学号后两位
     *
     * 例如:
     * AB表示 学号姓名,既文件名称,例如:201501069343-孙武.doc
     */
    private $renameRuleData = [];

    //信息
    private $userID   = null;
    private $userInfo = [];
    private $workInfo = [];
    private $classid;
    private $workid;

    //


    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //comon
        $this->commonField = new CommonField();
        //model
        $this->worksModel = new HomeWork();
        $this->userModel  = new User();
        $this->itemModel  = new WorkItem();
        $this->classModel = new Classes();

        $this->createTime = time();
        $this->initUser();
        $this->initWork();

    }


    //已上传|未上传|已上传与未上传人数|....
    function uploadInfo($type = '')
    {
        //已经上传
        $users = $this->userModel->where([
            'fk_class' => $this->userInfo['fk_class']
        ])->order('stu_no asc')->select()->toArray();

        $usersArr = [];
        foreach ($users as $v) {
            array_push($usersArr, $v['pk_user']);
        }

        $hasUploadUser = $this->itemModel->with('user')->where([
            'fk_homeworks' => $this->workInfo['pk_homeworks']
        ])->select()->toArray();

        $hasUploadUserArr = [];
        foreach ($hasUploadUser as $v) array_push($hasUploadUserArr, $v['fk_user']);


        $nonUploadUserArr = array_diff($usersArr, $hasUploadUserArr);

        $nonUploadUser = [];
        foreach ($users as $v) {
            if (in_array($v['pk_user'], $nonUploadUserArr)) {
                array_push($nonUploadUser, $v);
            }
        }
        switch ($type) {
            case "hasUpload": {
                return $this->infoDbSelect($hasUploadUser);
            }
                break;
            case "nonUpload": {
                return $this->infoDbSelect($nonUploadUser);
            }
                break;
            case "statistics": {
                return $this->infoDbSelect([
                    'hasUpload' => count($hasUploadUser), 'nonUpload' => count($nonUploadUser)
                ]);
            }
                break;
            default:
                return $this->infoEmpty();
        }
    }


    //初始化用户信息
    //所需参数:
    //post uid:用户id
    private function initUser()
    {
        if (!Request::instance()->has('uid')) $this->infoDie('缺少参数uid');
        $this->userID = input('param.uid');
        $userInfo     = $this->userModel->find($this->userID);
        if (!$userInfo['pk_user']) $this->infoDieErrno(10006);
        $this->userInfo = $userInfo;
        $this->classid  = $userInfo['fk_class'];
    }

    //初始化作业信息
    private function initWork()
    {
        $wid = input('param.wid');
        if (!$wid) return '';
        $workInfo = $this->worksModel->find($wid);
        if (!$workInfo['pk_homeworks']) $this->infoDie('作业不存在');
        $this->workInfo = $workInfo;
        $this->workid   = $workInfo['pk_homeworks'];
        return true;
    }

    //具体的某个作业信息
    function workDetail()
    {
        return $this->infoDbSelect($this->workInfo);
    }


    //打包下载作业
    public function downloadZip()
    {
        $this->initWorkPath();
        (new Admin())->checkAuth();
        require_once __DIR__ . DS . "Zip.php";
        $zip = new \PHPZip();
        $zip->ZipAndDownload($this->saveDir, iconv(CommonField::utf8, CommonField::gbk, $this->workInfo['title']));
    }


    //上传制动命名
    function uploadAutoRename()
    {
        try {
            $this->initWorkPath();
            $file           = request()->file('file');
            $this->fileName .= '.' . pathinfo(($file->getInfo())['name'], PATHINFO_EXTENSION);

            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($file) {
                $info = $file->move($this->saveDir, $this->fileName);
                if ($info) {
                    $fileName = $this->saveDir . $this->fileName;
                    if (file_exists($fileName)) { //上传文明是否真实存在
                        $file = $this->itemIsExist();
                        if ($file !== false) {
                            //数据库存在记录,更新
                            $unlinkFile = $this->saveDir . Helper::utf8togbk($file['file_path']);
                            if (file_exists($unlinkFile) && $unlinkFile != $fileName) {
                                if (!unlink($unlinkFile)) return $this->infoErrmsg('删除旧文件失败');
                            }
                            $res = $this->itemModel->save([
                                'fk_homeworks' => $this->workInfo['pk_homeworks'], "fk_user" => $this->userInfo['pk_user'], "file_path" => Helper::gbktoutf8($this->fileName),
                            ], ['fk_homeworks' => $this->workInfo['pk_homeworks'], 'fk_user' => $this->userInfo['pk_user']]);
                            return $this->infoDbUpdate($res);
                        } else {
                            //不存在,添加
                            $res = $this->itemModel->save([
                                'pk_works_items' => md5(time()), 'fk_homeworks' => $this->workInfo['pk_homeworks'], "fk_user" => $this->userInfo['pk_user'], "file_path" => Helper::gbktoutf8($this->fileName),
                            ]);
                            return $this->infoDbInsert($res);
                        }
                    } else {
                        return $this->infoErrmsg('上传文件移动失败,服务器未知错误');
                    }

                } else {

                    return $this->infoErrmsg($file->getError());//上传失败
                }
            } else {
                return $this->infoErrmsg('未选择上传文件,请确定input域为file');
            }
        }
        catch (Exception $e) {
            return $this->infoErrmsg($e->getMessage());
        }

    }

    private function mkWorkDir($dir = '')
    {
        $this->saveDir = iconv(CommonField::utf8, $this->sysCharset, ROOT_PATH . 'upload' . DS . $dir . DS);
        if (!file_exists($this->saveDir)) return mkdir($this->saveDir, 0777);
        return true;
    }

    private function initWorkPath()
    {
        $this->renameRuleData = [
            "A" => $this->userInfo['stu_no'], "B" => $this->userInfo['name'], "C" => $this->workInfo['title'], "D" => date('ymd', $this->workInfo['create_time']), "Z" => substr($this->userInfo['stu_no'], -2)
        ];
        $this->renameRule     = $this->workInfo['auto_name_rule'];
        $this->mkWorkDir($this->workInfo['title']);
        $this->getAutoRenameFile();
    }


    //获得自动命名的名称
    private function getAutoRenameFile()
    {
        $i        = 0;
        $fileName = "";


        while ($i < strlen($this->renameRule) && $t = $this->renameRule[ $i ]) {
            $i++;
            $fileName .= $this->renameRuleData[ $t ] . '-';

        }
        $this->fileName = iconv(CommonField::utf8, $this->sysCharset, rtrim($fileName, '-'));
    }


    function getAllWorkOfClass()
    {
        return $this->infoDbSelect($this->worksModel->where('fk_classes', $this->classid)->select());
    }

    function getDeletedWorkOfClass()
    {
        return $this->infoDbSelect($this->worksModel->where('fk_classes', $this->workid)->where('status', 'neq', 'deleted')->select());
    }

    public function delWork($wid = 11)
    {
        $res = $this->worksModel->save(['status' => 'deleted'], ['pk_homeworks' => $wid]);
        return $this->infoDbUpdate($res);
    }

    private function itemIsExist()
    {
        $info = $this->itemModel->where('fk_homeworks', $this->workInfo['pk_homeworks'])->where('fk_user', $this->userInfo['pk_user'])->find();
        if ($info) return $info;
        return false;
    }

    function statistics()
    {
        $usercount  = $this->userModel->count();
        $classcount = $this->classModel->count();
        return $this->infoDbSelect([
            'uc' => $usercount, 'cc' => $classcount
        ]);
    }

}