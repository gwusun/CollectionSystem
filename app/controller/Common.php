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

class Common extends Base
{
    private $commonField;
    private $worksModel;
    private $userModel;
    private $itemModel;
    private $classModel;


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

    }


    //根据班级获取用户
    public function classesuser(){
        return $this->infoDbSelect($this->userModel->where('fk_class',input('param.class'))->select());
    }

}