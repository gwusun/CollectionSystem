<?php
namespace Sunwu\File;
/**
 * 文件操作基础类
 * Created by PhpStorm.
 * User: Sunwu
 * Date: 2017/7/19
 * Time: 21:10
 */
class FileObject{
    /**
     * v1.0
     * 只能处理文件,不能处理文件夹
     * @param $dir 需要打开的文件夹
     * @return array 文件夹里面的文件名
     */
    protected function getFileList($dir)
    {

        if(!file_exists($dir)){
            die("文件夹[ ".$dir." ]不存在");
        }

        $validFile = array();
        if (is_dir($dir)) {
            if ($fileArray = scandir($dir)) {
                foreach ($fileArray as $item) {
                    if ($item !== '..' && $item !== '.') {
                        if (!is_dir($item)) {
                            $validFile[] = $dir . $item;
                        } else {
                            //如果是文件夹,暂不处理
                        }
                    }
                }
                return $validFile;
            } else {
                die('无法遍历给定的文件夹下的文件');
            }
        } else {
            die("你遍历的不是一个文件夹!可能文件夹不存在或者已经被删除!");
        }
    }


    /**
     * 编码转换函数
     * @param string $str 需要转换的字符串
     * @param string $from 原来的编码
     * @param string $to 目标编码
     * @return string 转换好的字符串
     */
    protected function exchangeEcodeing($str,$from,$to)
    {
        if($from='gbk' && $to='utf-8') return iconv('gbk', 'utf-8', $str);
        if($from='utf-8'&& $to='gbk') return iconv('utf-8', 'gbk', $str);

    }


}