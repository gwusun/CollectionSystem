<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/6/15
 * Time: 20:35
 */

namespace app\controller;

use app\model\Classes;
use app\model\HomeWork;
use Sunwu\Errmsg\CommonField;
use think\Exception;
use think\Request;
use think\Validate;

class Admin extends Base
{

    private $uid        = null;
    private $ucid       = null;
    private $class      = null;
    private $user       = null;
    private $admin      = null;
    private $classModel = null;
    private $userModel  = null;
    private $workModel  = null;
    private $adminModel = null;


    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->classModel = new \app\model\Classes();
        $this->userModel  = new \app\model\User();
        $this->workModel  = new \app\model\HomeWork();
        $this->adminModel = new \app\model\Admin();
        $this->init();
    }

    function init()
    {
        if (!Request::instance()->has('uid')) $this->infoDie('缺少参数uid');
        //        if (!Request::instance()->has('ucid')) $this->infoDie('缺少参数ucid');
        $uid = input('param.uid');
        //        $ucid = input('param.ucid');

        $this->uid = $uid;
        $user      = $this->userModel->find($uid);
        if (!$user) $this->infoDie('用户不存在');

        $this->user = $user;
        $this->ucid = $user['fk_class'];

        $class = $this->classModel->find($this->ucid);
        if (!$class) $this->infoDie('拒绝服务');
        $this->class = $class;

        $this->auth();
    }

    function classv2()
    {
        return $this->infoDbSelect($this->class);
    }

    public function checkAuth()
    {
        return true;
    }

    function addSchoolWorkv2()
    {

        //todo 验证
        $data     = input('post.');
        $validate = new Validate(['auto_name_rule' => ['regex' => '/^(?:[ABCDZ]){1,5}$/'], 'end_time' => 'require|min:1', 'uid' => 'require|min:1', 'ucid' => 'require|min:1',]);

        if (!$validate->check($data)) {
            return $this->infoErrmsg(($validate->getError()));
        }
        $data['fk_classes']   = $this->ucid;
        $data['pk_homeworks'] = md5(json_encode($data));
        $data['end_time']     = strtotime($data['end_time']);
        $data['status']       = 'able';
        return $this->infoDbInsert($this->workModel->allowField(true)->save($data));

    }

    private function auth()
    {
        $admin = $this->adminModel->where("fk_user", $this->user['pk_user'])->find();
        if (!$admin) $this->infoDie('暂无权限');
        $this->admin = $admin;
        return true;
    }

    public function isAdmin()
    {
        return $this->infoSuccess();
    }


    function modifySchoolWorkv2()
    {
        $data             = input('post.');
        $data['end_time'] = strtotime($data['end_time']);
        $data['status']   = 'able';
        return $this->infoDbUpdate($this->workModel->allowField(true)->save($data, ['pk_homeworks' => $data['pk_homeworks']]));
    }

    function viewPwd()
    {
        return $this->infoDbSelect($this->userModel->where('stu_no', input('post.stu_no'))->find());
    }


}