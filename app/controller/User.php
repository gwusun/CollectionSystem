<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/15
 * Time: 20:35
 */

namespace app\controller;

use app\model\Classes;
use app\model\HomeWork;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Sunwu\Errmsg\CommonField;
use Sunwu\Errmsg\ErrMaps;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class User extends Controller
{
    private $commonField;
    private $errMaps;
    private $userModel;
    private $workModel;
    private $classModel;
    private $adminModel;
    const login_name  = 'login_name';
    const login_pwd   = 'login_passwork';
    const login_email = 'login_email';

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->commonField = new CommonField();
        $this->errMaps     = new ErrMaps();
        $this->userModel   = new \app\model\User();
        $this->workModel   = new HomeWork();
        $this->classModel  = new Classes();
        $this->adminModel  = new \app\model\Admin();
    }

    function login($loginname = '', $loginpwd = '')
    {
        $userInfo = $this->userModel->where([
            self::login_name => $loginname,
            self::login_pwd => $loginpwd
        ])->find();
        $pk_user  = $userInfo['pk_user'];
        if (empty($pk_user)) return $this->errMaps->infoTips(10004);
        return $this->errMaps->infoDbSelect($userInfo);

    }


    function reg()
    {
        $regData = input('post.');
        if ($this->isReg($regData['stu_no']) !== false) return $this->errMaps->infoTips(10005);
        $regData['login_name'] = $regData['stu_no'];
        $regData['pk_user']    = md5(time());
        $regData['status']     = CommonField::db_status_able;
        $saved                 = $this->userModel->allowField(true)->save($regData);
        return $this->errMaps->infoDbInsert($saved);
    }

    function forget()
    {

    }

    private function isReg($stu)
    {
        $login_name = $this->userModel->where(self::login_name, $stu)->value('login_name');
        if ($login_name) return $login_name;
        return false;
    }

    function addSchoolWork()
    {
        $data                 = input('post.');
        $data['pk_homeworks'] = md5(json_encode($data));
        $data['end_time']     = strtotime($data['end_time']);
        $data['status']       = 'able';
        return $this->errMaps->infoDbInsert($this->workModel->save($data));
    }

    function modifySchoolWork()
    {
        $data             = input('post.');
        $data['end_time'] = strtotime($data['end_time']);
        $data['status']   = 'able';
        return $this->errMaps->infoDbUpdate($this->workModel->save($data, ['pk_homeworks' => $data['pk_homeworks']]));
    }

    function inportUserFromExcel($class_id, $password = '')
    {
        if ($password !== 'sunwu.liubingnan') return '密码错误';
        $file = __DIR__ . '/../../upload/class/' . $class_id . ".xlsx";
        $data = $this->getExcelDataOfUser($class_id, $file);
        $res  = $this->userModel->insertAll($data);
        if ($res) {
            return $this->errMaps->infoSuccess();
        } else {
            return $this->errMaps->infoTips(-1);
        }
    }


//    获取excel表用户
    function getExcelDataOfUser($class_id, $file = '')
    {

        $data        = [];
        $filename    = $file;
        $reader      = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($filename);
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {

            foreach ($worksheet->getRowIterator() as $row) {

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $index = 0;
                $t     = [];
                foreach ($cellIterator as $cell) {
                    if ($cell !== null) {
                        if ($index === 0) {
                            //excel第一行
                            $t['stu_no'] = (string)$cell;
                        } else if ($index === 1) {
//                            excel第二行
                            $t['name'] = (string)$cell;
                            break;
                        }
                    }

                    $index++;
                }
                $t['login_name']     = $t['stu_no'];
                $t['create_time']    = time();
                $t['status']         = 'able';
                $t['login_email']    = 0;
                $t['update_time']    = 0;
                $t['pk_user']        = md5($t['login_name'] . $t['stu_no'].$class_id);
                $t['fk_class']       = $class_id;
                $t['login_passwork'] = substr($t['stu_no'], -6);
                array_push($data, $t);
            }
            break;//只读取第一个sheet
        }

        return $data;
    }

//    取excel表管理员
    function getExcelDataOfAdmin($file = '')
    {

        $data        = [];
        $filename    = $file;
        $reader      = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($filename);
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {

            foreach ($worksheet->getRowIterator() as $row) {

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $row = $this->getDbAdminRow($cellIterator);
                if ($row === false) break;
                array_push($data, $row);
            }
            break;//只读取第一个sheet
        }

        return $data;
    }

    function addClassAndList()
    {
        $className = Request::instance()->param('classname');
        if (!$className) return $this->errMaps->infoErrmsg('缺少班级名称，请保证name=classname');
        try {
            $adminListPath = '';//传过来的
            if (!($adminListPath = $this->upload())) {
                return $this->errMaps->infoErrmsg('列表上传失败，请保证name=stulists');
            }


            $classId = '';
            Db::startTrans();
            if ($class = $this->addClass($className)) {
                $classId = $class['pk_classes'];
            } else {
                Db::rollback();
                return $this->errMaps->infoErrmsg('创建班级失败，请保证name=classname');
            }
            if (file_exists($adminListPath) && $classId !== '') {
                $stuLists = $this->getExcelDataOfUser($classId, $adminListPath);

                $res = $this->userModel->insertAll($stuLists);
                if ($res) {
                    Db::commit();
                    return $this->errMaps->infoSuccess();
                } else {
                    Db::rollback();
                    return $this->errMaps->infoTips(-1);
                }
            } else {
                Db::rollback();
                return $this->errMaps->infoErrmsg('请保证name=stulists');
            }
        }
        catch (Exception $e) {
            return $this->errMaps->infoErrmsg($e->getMessage());
        }
    }

    public function addAdmin()
    {
        try {
            $adminListPath = $this->uploadv2('admin');//传过来的
            if (!$adminListPath) {
                return $this->errMaps->infoErrmsg('列表上传失败，请保证name=admin');
            }


            Db::startTrans();
            if (file_exists($adminListPath)) {
                $stuLists = $this->getExcelDataOfUser($adminListPath);

                $res = $this->userModel->insertAll($stuLists);
                if ($res) {
                    var_dump($res);
//                    Db::commit();
//                    return $this->errMaps->infoSuccess();
                } else {
                    Db::rollback();
                    return $this->errMaps->infoTips(-1);
                }
            } else {
                Db::rollback();
                return $this->errMaps->infoErrmsg('请保证name=stulists');
            }
        }
        catch (Exception $e) {
            return $this->errMaps->infoErrmsg($e->getMessage());
        }
    }


    function importAdmin()
    {
        $adminListPath = $this->uploadv2('admin');//传过来的
        if (!$adminListPath) {
            return $this->errMaps->infoErrmsg('列表上传失败，请保证name=admin');
        }
        Db::execute("TRUNCATE TABLE sh_admin;");
        Db::startTrans();
        if (file_exists($adminListPath)) {
            $stuLists = $this->getExcelDataOfAdmin($adminListPath);
            $res      = $this->adminModel->insertAll($stuLists);
            if ($res) {
                Db::commit();
                return $this->errMaps->infoSuccess();
            } else {
                Db::rollback();
                return $this->errMaps->infoTips(-1);
            }
        } else {
            Db::rollback();
            return $this->errMaps->infoErrmsg('请保证name=stulists');
        }
    }

    function addClass($className)
    {
        if ($className) {
            $classId   = md5($className);
            $time      = time();
            $classData = ['pk_classes' => $classId, 'class_name' => $className, 'create_time' => $time, "status" => CommonField::db_status_able];
            if ($this->classModel->find($classId)) {
                return $classData;
            }
            if ($this->classModel->insert($classData)) {
                return $classData;
            }
            return false;
        }
        return false;
    }

    public function upload()
    {
        $savePath = ROOT_PATH . DS . 'upload' . DS . 'class';
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('stulists');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move($savePath);
            if ($info) {
                return $savePath . DS . $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                var_dump($file->getError());
                return false;
            }
        }
        return false;
    }

    public function uploadv2($name = '')
    {
        $savePath = ROOT_PATH . 'upload' . DS . 'class';
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move($savePath);
            if ($info) {
                return $savePath . DS . $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                return false;
            }
        }
        return false;
    }

    private function getDbAdminRow($cellIterator)
    {
        $index = 0;
        $t     = [];
        foreach ($cellIterator as $cell) {
            if (strlen($cell) >= 2) {
                if ($index === 0) {
                    //excel第一行
                    $t['stu_no'] = (string)$cell;
                } else if ($index === 1) {
//                            excel第二行
                    $t['name'] = (string)$cell;
                    break;
                }
            } else {
                return false;
            }
            $index++;
        }
        $t['pk_admin']    = md5($t['stu_no']);
        $t['fk_user']     = md5($t['stu_no'] . $t['stu_no']);
        $t['update_time'] = 0;
        $t['create_time'] = 0;
        $t['status']      = 'able';
        unset($t['name']);
        unset($t['stu_no']);
        return $t;
    }

    /**
     * 更改密码
     * 接口：http://www.sh.cn/index.php/user/reset
     * 参数：
     * 学号  ：stu_no
     * 旧密码：login_pwd
     * 新密码：new_login_pwd
     *
     * 服务器返回
     * {"errno":30001,"errmsg":"更新失败"} 代表修改密码失败
     * {"errno":0,"errmsg":"成功"} 代表修改成功
     *
     */
    function reset()
    {
        $data = input('post.');
        if (!array_key_exists('stu_no', $data) || !array_key_exists('login_pwd', $data) || !array_key_exists('new_login_pwd', $data)) $this->errMaps->infoDie('参数不正确。');
        return $this->errMaps->infoDbUpdate($this->userModel->save(['login_passwork' => $data['new_login_pwd']], ['stu_no' => $data['stu_no'], 'login_passwork' => $data['login_pwd']]));


    }
}