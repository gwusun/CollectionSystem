<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/24
 * Time: 14:32
 */

namespace Sunwu\Errmsg;


class Helper
{
    public static function ajaxHeader()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
    }

    static function utf8togbk($sourcecode)
    {
        return iconv("UTF-8", "GBK", $sourcecode);
    }

    static function gbktoutf8($sourcecode)
    {
        return iconv("GBK", "UTF-8", $sourcecode);
    }

    static function mkWorkDir($dir = '')
    {
        if (!file_exists($dir)) return mkdir($dir,0777);
        return true;
    }
}