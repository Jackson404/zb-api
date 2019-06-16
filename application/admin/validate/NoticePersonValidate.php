<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 14:15
 */

namespace app\admin\validate;


use think\Validate;

class NoticePersonValidate extends Validate
{

    protected $rule = [
        'name' => 'require|max:52',
        'email' => 'require|email',
        'phone' => 'require|max:11|min:11',
        'project_id' => 'require|number'
    ];
    protected $scene = [
        'add' => ['name', 'email', 'phone','project_id']
    ];
}