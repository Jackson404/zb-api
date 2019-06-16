<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15 0015
 * Time: 10:42
 */

namespace app\admin\controller;

use app\admin\common\Check;
use app\admin\common\SFTPConnection;
use app\admin\common\Util;
use app\admin\db\CDBAccount;
use app\admin\db\CDBMail;
use app\admin\db\CDBServer;
use Redis;
use think\Controller;
use think\Request;

class Mail extends Controller
{
    /**
     * 用sftp登录服务器 读取邮件日志 并把内容添加进数据库
     */
    public function insertMailByFilenameOrDir()
    {
        $CDBAccount = new CDBAccount();
        if (!$CDBAccount->isLogin()) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录错误');
            exit;
        }
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $username = Check::check(trim(isset($_POST['username']) ? $_POST['username'] : ''));
        $password = Check::check(trim(isset($_POST['password']) ? $_POST['password'] : ''));
        $file = Check::check(trim(isset($_POST['file']) ? $_POST['file'] : ''));
        $createBy = Check::checkInteger(trim(isset($_POST['createBy']) ? $_POST['createBy'] : ''));

        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $host = $CDBServer->getServerIpByServerId($serverId);
        $SFTPConnection = new SFTPConnection($host, 22);
        try {
            $SFTPConnection->login($username, $password);
        } catch (\Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'],$e->getMessage());
            exit;
        }
        if ($host == '' || $username == '' || $password == '' || $file == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $fileArr = $SFTPConnection->scanFilesystem($file);
        $CDBMail = new CDBMail();
        $count = $CDBMail->mailInsert($fileArr, $serverId, $createBy);
        $data['count'] = $count;
        if ($count > 0) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据添加出现错误');
        exit;
    }

    /**
     * 更新数据
     */
    public function updateMail()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $username = Check::check(trim(isset($_POST['username']) ? $_POST['username'] : ''));
        $password = Check::check(trim(isset($_POST['password']) ? $_POST['password'] : ''));
        $file = Check::check(trim(isset($_POST['file']) ? $_POST['file'] : ''));
        $createBy = Check::checkInteger(trim(isset($_POST['createBy']) ? $_POST['createBy'] : ''));

        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $host = $CDBServer->getServerIpByServerId($serverId);
        $SFTPConnection = new SFTPConnection($host, 22);
        try {
            $SFTPConnection->login($username, $password);
        } catch (\Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'],$e->getMessage());
            exit;
        }
        if ($host == '' || $username == '' || $password == '' || $file == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $fileArr = $SFTPConnection->scanFilesystem($file);
        $CDBMail = new CDBMail();
        $count = $CDBMail->updateMail($fileArr, $serverId, $createBy);
        $data['updateCount'] = $count;
        if ($count > 0) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '未更新');
        exit;
    }

    /**
     * 获取所有已经发送的邮件信息
     */
    public function getHaveSentMails()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBMail = new CDBMail();
        $contents = $CDBMail->showAllMailList($serverId);
        $data['allList'] = $contents;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 分页获取已经发送的邮件记录
     */
    public function getHaveSentMailsPaging()
    {
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBMail = new CDBMail();
        $list = $CDBMail->showAllMailListPaging($serverId, $perPage, $currentPage);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }
    /**
     * 获取未发送的邮件
     */
    public function getUnSentMails()
    {
        if (Request::instance()->isPost()) {
            $pageSize = Check::checkInteger(trim(isset($_POST['pageSize']) ? $_POST['pageSize'] : '15')); // 每页显示多少条记录
            $pageIndex = Check::checkInteger(trim(isset($_POST['pageIndex']) ? $_POST['pageIndex'] : '1'));  // 当前是第几页
            $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
            $CDBServer = new CDBServer();
            if (!$CDBServer->checkServerIdExists($serverId) > 0) {
                Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
                exit;
            }
            $host = $CDBServer->getServerIpByServerId($serverId);
            // 连接redis
            $redis = new Redis;
            $redis->connect($host, $GLOBALS['redis_port']);
            $redis->auth($GLOBALS['redis_auth']);
            $redis->select(0);

            if ($redis->exists('mail')) {
                $len = $redis->lLen('mail');
                $start = ($pageIndex - 1) * $pageSize;
                $end = $start + $pageSize - 1;
                $pageCount = ceil($len / $pageSize); // 共有多少页
                $data['pageSize'] = $pageSize;
                $data['pageIndex'] = $pageIndex;
                $data['pageCount'] = $pageCount;
                $data['total'] = $len;

                if (($end + 1) >= $len) {
                    $end = '-1';
                }
                // 分页读取redis消息队列中的数据
                $result = $redis->lRange('mail', $start, $end);
                $data['paging'] = $result;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;

            }
            Util::printResult($GLOBALS['ERROR_REDISKEY_NOT_EXISTS'], '邮件队列不存在');
            exit;

        }
        Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
        exit;
    }


}