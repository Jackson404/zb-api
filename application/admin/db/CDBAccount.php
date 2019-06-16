<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 18:33
 */

namespace app\admin\db;

use app\admin\common\Util;
use think\Db;
use think\Request;

class CDBAccount
{
    public function isLogin()
    {
        /*
         * 检查用户是否登录
         * */
        if (Request::instance()->isPost()) {
            $id_token = isset($_POST['id_token']) ? $_POST['id_token'] : '';
            if (!$id_token || $id_token == '') {
                return false;
            }
            $data = explode('|', $id_token);
            $userId = $data['0'];
            $token = $data['1'];
            $result = DB::name('login_history')->where('userId', 'eq', $userId)->where('token', 'eq', $token)->where('login_out', 'eq', '0')->count('id');
            if ($result > 0) {
                $GLOBALS['userId']=$userId;
                return true;
            }
            return false;
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是post传值');
            exit;
        }
    }

    /*
     *用户登录
     *
     * */
    public function userLogin($username, $password)
    {
        $count = $this->verifyUsername($username);
        if (!$count) {
            Util::printResult($GLOBALS['ERROR_USER_EXISTS'], '用户名不存在');
            exit;
        }
        $result = DB::name('user')->where('username', $username)->value('password');
        $verify = password_verify($username . $password, $result);
        if (!$verify) {
            Util::printResult($GLOBALS['ERROR_PASSWORD'], '密码错误');
            exit;
        }
        $userId = DB::name('user')->where('username', $username)->value('id');
        $token = password_hash($userId . $username . $password, PASSWORD_DEFAULT);
        $login_ip = $_SERVER["REMOTE_ADDR"];
        $login_time = date('Y-m-d H:i:s', time());
        $login_out = '0';
        $arr = [
            'login_name' => $username,
            'userId' => $userId,
            'token' => $token,
            'login_ip' => $login_ip,
            'login_time' => $login_time,
            'login_out' => $login_out
        ];
        $count = DB::name('login_history')->insert($arr);
        if ($count > 0) {
            $id_token = $userId . '|' . $token;
            $data = [
                'username' => $username,
                'id_token' => $id_token
            ];
            return $data;
        }
    }

    /*
     *注册用户
     * */
    public function userRegister($username, $password)
    {
        $count = $this->verifyUsername($username);
        if ($count > 0) {
            Util::printResult($GLOBALS['ERROR_REGISTER_DUPLICATEUSERNAME'], '用户名已经存在');
            exit;
        }
        $password = password_hash($username . $password, PASSWORD_DEFAULT);
        $data = [
            'username' => $username,
            'password' => $password
        ];
        $result = DB::name('user')->insert($data);
        return $result;
    }

    /*
     * 检查用户名是否存在
     * */
    public function verifyUsername($username)
    {
        $count = DB::name('user')->where('username', $username)->count();
        return $count;
    }
}