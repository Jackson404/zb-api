<?php

namespace app\api\controller\v1;

use app\api\model\AdminUserLoginHistoryModel;
use app\api\model\AdminUserModel;
use think\Controller;
use think\Request;
use Util\Util;

class AdminUser extends AuthBase
{
    /**
     * 后台用户注册
     */
    public function register()
    {
        $params = Request::instance()->request();
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';

        $adminUserModel = new AdminUserModel();
        $count = $adminUserModel->verifyUsername($username);
        if ($count > 0) {
            Util::printResult($GLOBALS['ERROR_REGISTER_DUPLICATEUSERNAME'], '用户名已经存在');
            exit;
        }
        $password = password_hash($username . $password, PASSWORD_DEFAULT);
        $data = [
            'name' => $username,
            'password' => $password,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ];
        $insertRow = $adminUserModel->save($data);
        if ($insertRow > 0) {
            util::printResult($GLOBALS['ERROR_SUCCESS'], '注册成功');
            exit;
        } else {
            util::printResult($GLOBALS['ERROR_REGISTER'], '注册出错');
            exit;
        }
    }


    /**
     * 后台用户登录
     * @throws \think\Exception
     */
    public function login()
    {
        $params = Request::instance()->request();
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';

        if ($username == '' || $password == '') {
            Util::printResult('ERROR_PARAM_MISSING', '缺少参数');
            exit;
        }

        $adminUserModel = new AdminUserModel();
        $count = $adminUserModel->verifyUsername($username);
        if (!$count) {
            Util::printResult($GLOBALS['ERROR_USER_EXISTS'], '用户名不存在');
            exit;
        }

        $result = $adminUserModel->getPasswordByName($username);
        $verify = password_verify($username . $password, $result);
        if (!$verify) {
            Util::printResult($GLOBALS['ERROR_PASSWORD'], '密码错误');
            exit;
        }
        $userId = $adminUserModel->getIdByName($username);
        $token = password_hash($userId . $username . $password, PASSWORD_DEFAULT);
        $loginIp = $_SERVER["REMOTE_ADDR"];
        $loginTime = date('Y-m-d H:i:s', time());
        $loginOut = 0;
        $arr = [
            'loginName' => $username,
            'userId' => $userId,
            'token' => $token,
            'loginIp' => $loginIp,
            'loginTime' => $loginTime,
            'loginOut' => $loginOut
        ];
        $adminUserLoginHistoryModel = new AdminUserLoginHistoryModel();
        $insertRow = $adminUserLoginHistoryModel->add($arr);
        if ($insertRow > 0) {
            $id_token = $userId . '|' . $token;
            $data = [
                'username' => $username,
                'id_token' => $id_token
            ];

            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录失败');
            exit;
        }
    }

    /**
     * 检查用户登录
     * @return bool
     */
    public function checkLogin()
    {
        $params = Request::instance()->request();
        $id_token = $params['id_token'] ?? '';

        if (!$id_token || $id_token == '') {
            return false;
        }
        $data = explode('|', $id_token);
        $userId = $data['0'];
        $token = $data['1'];
        $adminUserLoginHistoryModel = new AdminUserLoginHistoryModel();
        $count = $adminUserLoginHistoryModel->countByIdToken($userId, $token);
        if ($count > 0) {
            $GLOBALS['userId'] = $userId;
            return true;
        }
        return false;
    }
}