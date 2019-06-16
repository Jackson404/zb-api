<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 11:04
 */

namespace app\admin\model;


use think\Model;

class AppProject extends Model
{

    protected $pk = 'id';
    protected $name = 'app_project';
    protected $autoWriteTimestamp = 'datetime';

}