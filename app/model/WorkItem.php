<?php
/**
 * Author: 孙武 QQ:1228746736
 * Date: 2018/3/15
 * Time: 23:08
 */

namespace app\model;


use think\Model;

class WorkItem extends Model
{
    protected $table = 'sh_works_items';
    protected $pk    = 'pk_works_items';
    public function user()
    {
        return $this->hasOne('user','pk_user','fk_user');
    }
}