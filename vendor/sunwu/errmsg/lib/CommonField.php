<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/7
 * Time: 9:54
 */

namespace Sunwu\Errmsg;

//错误基类
class CommonField
{
    const create_time = 'create_time';
    const delete_time = 'delete_time';
    const update_time = 'update_time';
    const end_time    = 'end_time';

    const db_status_able    = 'able';
    const db_status_disable = 'disable';
    const db_status_delete  = 'deleted';
    //status : ENUM('able', 'disable', 'deleted')

    const uid   = "uid";
    const uname = 'uname';


    const utf8 = "UTF-8";
    const gbk  = "GBK";

    const login_name = 'loginname';
    const login_pwd  = 'loginpwd';

    public static function ajaxHeader()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
    }


}



