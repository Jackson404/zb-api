<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 15:19
 */

namespace app\admin\controller;

use app\admin\db\CDBAccount;
use app\admin\common\Check;
use app\admin\common\Util;
use think\Controller;
use think\Request;

class UserLogin extends Controller
{
    /**
     * 登录
     */
    public function index()
    {
        $CDBAccount = new CDBAccount();
        if (Request::instance()->isPost()) {
            Check::check(input('username'));
            Check::check(input('password'));
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            if ($username == '' || $password == '') {
                Util::printResult('ERROR_PARAM_MISSING', '缺少参数');
                exit;
            }
            $count = $CDBAccount->userLogin($username, $password);
            $data[] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是post传值');
            exit;
        }
    }

    /**
     * 注册
     */
//    public function userRegister()
//    {
//        if (Request::instance()->isPost()) {
//            $username = $_POST['username'];
//            $password = $_POST['password'];
//            $CDBAccount = new CDBAccount();
//            $count = $CDBAccount->userRegister($username, $password);
//            if ($count > 0) {
//                util::printResult($GLOBALS['ERROR_SUCCESS'], '注册成功');
//                exit;
//            }
//            util::printResult($GLOBALS['ERROR_REGISTER'], '注册出错');
//            exit;
//        } else {
//            util::printResult($GLOBALS['ERROR_POST'], '不是post传值');
//            exit;
//        }
//
//    }
}