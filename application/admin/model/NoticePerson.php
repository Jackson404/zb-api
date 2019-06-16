<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 14:11
 */
namespace app\admin\model;

use think\Model;

class NoticePerson extends Model
{
    protected $pk = 'id';
    protected $name = 'notice_person';
    protected $autoWriteTimestamp = 'datetime';

}