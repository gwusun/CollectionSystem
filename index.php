<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/15
 * Time: 14:54
 */

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
define('APP_PATH', __DIR__ . '/app/');
define('RUNTIME_PATH', APP_PATH . '/runtime/');
require_once "./vendor/topthink/framework/start.php";
