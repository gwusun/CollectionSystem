<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/15
 * Time: 20:35
 */

namespace app\controller;

use app\model\Classes;
use app\model\TechCat;
use Sunwu\Errmsg\CommonField;
use Sunwu\Errmsg\ErrMaps;
use think\Controller;
use think\Request;
use think\Validate;

class Index extends Base
{
    private $commonField;
    private $homeWorksModel;
    private $classModel;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->commonField    = new CommonField();
        $this->homeWorksModel = new \app\model\HomeWork();
        $this->classModel     = new Classes();
    }

    //进行中
    function doing($class = '')
    {
        if ($class) return $this->infoDbSelect($this->homeWorksModel->where(CommonField::end_time, '>', time())->where('fk_classes', $class)->where('status', 'neq', 'deleted')->order("create_time desc")->select()->toArray()); else {
            $data = $this->homeWorksModel->where(CommonField::end_time, '>', time())->where('status', 'neq', 'deleted')->order("create_time desc")->select()->toArray();
            return $this->infoDbSelect($data);
        }

    }

    //已完成
    function done($class = '')
    {
        if ($class) return $this->infoDbSelect($this->homeWorksModel->where(CommonField::end_time, '<', time())->where('fk_classes', $class)->order("create_time desc")->where('status', 'neq', 'deleted')->select()->toArray()); else {

            $data = $this->homeWorksModel->where('status', 'neq', 'deleted')->where(CommonField::end_time, '<', time())->order("create_time desc")->select()->toArray();
            return $this->infoDbSelect($data);
        }
    }

    //获取分类
    function class()
    {
        return $this->infoDbSelect($this->classModel->select());
    }



    function test()
    {
        $data     = input('param.');
        $validate = new Validate([
            'name' => 'require|max:4', 'email' => 'email'
        ]);
        if (!$validate->check($data)) {
            dump($validate->getError());
        }
    }

}