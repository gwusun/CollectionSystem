<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/7
 * Time: 9:54
 */

namespace Sunwu\Errmsg;

//错误基类
class ErrCore
{
    private $errno;
    private $errmsg;
    private $maps;

    public function __construct()
    {
        $mapname = __DIR__ . "/../conf/maps.ini";
        if (!file_exists($mapname)) throw new \Exception('当前目录下的maps.ini文件不存在,请手动创建');
        $this->maps = parse_ini_file($mapname);
    }

    public function err($errno)
    {
        $this->setErrnoAndErrmsg($errno);
        return $this->formate();

    }
    public function errdata($errno,$data)
    {
        $this->setErrnoAndErrmsg($errno);
        $t=$this->formate();
        $t['data']=$data;
        return $t;


    }


    function errmsg($msg = '')
    {
        return [
            'errno' => 50,
            'errmsg' => $msg,
        ];
    }



    private function formate()
    {
        return [
            'errno' => $this->errno,
            'errmsg' => $this->errmsg,
        ];
    }

    private function setErrnoAndErrmsg($errno)
    {
        $this->errno  = (int)$errno;
        $this->errmsg = $this->maps[$this->errno];
    }


}



