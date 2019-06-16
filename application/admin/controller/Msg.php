<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 14:23
 */

namespace app\admin\controller;

use app\admin\common\Check;
use app\admin\common\SFTPConnection;
use app\admin\common\Util;
use app\admin\db\CDBAccount;
use app\admin\db\CDBMsg;
use app\admin\db\CDBServer;
use app\admin\message\ChuanglanSmsApi;
use Redis;
use think\Controller;
use think\Request;


class Msg extends Controller
{
    /**
     * 用sftp登录服务器 读取邮件日志 并把内容添加进数据库
     */
    public function insertMsgByFilenameOrDir()
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

        $CDBMail = new CDBMsg();
        $count = $CDBMail->msgInsert($fileArr, $serverId, $createBy);
        $data['count'] = $count;
        if ($count > 0) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据添加出现错误');
        exit;
    }

    /**
     * 测试sftp登录
     */
    public function testSftp(){
        $host='120.26.103.174';
        $username='fm';
        $password='family-manage-187';

        $SFTPConnection = new SFTPConnection($host, 22);
        try {
            $SFTPConnection->login($username, $password);

        } catch (\Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'],$e->getMessage());
            exit;
        }
    }

    /**
     * 更新信息
     */
    public function updateMsg()
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
        $CDBMail = new CDBMsg();
        $count = $CDBMail->updateMsg($fileArr, $serverId, $createBy);
        $data['updateCount'] = $count;
        if ($count > 0) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        \think\Log::error('错误信息');
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据更新出现错误');
        exit;
    }

    /**
     *分页获取已经发送的信息
     */
    public function getHaveSentMessagesPaging()
    {

        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
        $serverId = Check::check(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBMsg = new CDBMsg();
        $msgList = $CDBMsg->showAllMsgListPaging($serverId, $perPage, $currentPage);
        $data['msgList'] = $msgList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 获取所有的已经发送的信息
     */
    public function getALLSentMessages()
    {
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBMsg = new CDBMsg();
        $msgList = $CDBMsg->showAllMsgList($serverId);
        $data['msgList'] = $msgList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 添加公司类型
     */
    public function insertCompanyName()
    {
        $company = Check::check(trim(isset($_POST['company']) ? $_POST['company'] : ''));
        $createBy = Check::checkInteger(trim(isset($_POST['createBy']) ? $_POST['createBy'] : ''));
        if ($company == '' || $createBy == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $CDBMsg = new CDBMsg();
        $count = $CDBMsg->insertCompany($company, $createBy);
        if (!$count) {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据添加出现错误');
            exit;
        }
        $data['insertCount'] = $count;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 获取公司的类型
     */
    public function getAllCompanyName()
    {
        $CDBMsg = new CDBMsg();
        $result = $CDBMsg->showAllCompany();
        $data['companyList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 根据公司名字获取信息列表
     */
    public function getMsgListByCompanyIdPage()
    {
        $companyId = Check::checkInteger(trim(isset($_POST['companyId']) ? $_POST['companyId'] : ''));
        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBMsg = new CDBMsg();
        $msgList = $CDBMsg->showMsgListByCompanyIdPage($serverId, $companyId, $perPage, $currentPage);
        $data['msgList'] = $msgList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 获取未发送的信息队列
     */
    public function getUnSentMessages()
    {

        if (Request::instance()->isPost()) {
            $pageSize = Check::checkInteger(trim(isset($_POST['pageSize']) ? $_POST['pageSize'] : '15')); // 每页显示多少条记录
            $pageIndex = Check::checkInteger(trim(isset($_POST['pageIndex']) ? $_POST['pageIndex'] : '1')); // 当前是第几页
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

            if ($redis->exists('message')) {
                $len = $redis->lLen('message');
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
                $result = $redis->lRange('message', $start, $end);
                $data['paging'] = $result;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;

            }
            Util::printResult($GLOBALS['ERROR_REDISKEY_NOT_EXISTS'], '信息队列不存在');
            exit;

        }
        Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
        exit;

    }

    /**
     * 获取创蓝短信余额 253通讯
     * @return mixed
     */
    public function getChuanglanMsgBalance()
    {
        $clapi = new ChuanglanSmsApi();
        $result = $clapi->queryBalance();
        if (!is_null(json_decode($result))) {

            $output = json_decode($result, true);
            if (isset($output['code']) && $output['code'] == '0') {
                $data['balance'] = $output['balance'];
                Util::printResult($output['code'], $data);
                exit;
            } else {
                Util::printResult($output['code'], $output['error']);
                exit;
            }
        } else {
            return $result;
        }

    }

    /**
     * 获取容大有信 短信余额
     *
     */
    public function getYouxinyunMsgBalance()
    {
        //获得当前时间的正确时间戳格式，格式为MMddHHmmss
        function formatTime()
        {
            return date('mdHis');
        }

        $timestamp = formatTime(); //时间戳
        $username = "xinhuo1"; //用户名
        $pwd = "xinhuo_yx_187187"; //密码

        //获得当前的毫秒值，因为smsid不能为空，所以使用此数值

        $post_data = array();
        $post_data['UserName'] = $username;
        $post_data['Key'] = md5($username . $pwd . $timestamp);
        $post_data['Timestemp'] = $timestamp;
        $post_data['CharSet'] = "utf-8";
        $post_data['ServiceType'] = '01';
        $post_data['AccountType'] = '01';
        $url = 'http://www.youxinyun.com:3070/Platform_Http_Service/servlet/GetBalance';

        $o = "";

        foreach ($post_data as $k => $v) {
            $o .= "$k=" . $v . "&";
        }
        $post_data = substr($o, 0, -1);
        $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);//返回相应的标识，具体请参考我方提供的短信API文档
        curl_close($ch);

        $arr = json_decode($result, true);

        if (!is_null($arr['result']) && $arr['result'] == 0) {

            $data['description'] = $arr['description'];
            $data['body'] = $arr['body'];
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        $data['description'] = $arr['description'];
        $data['body'] = $arr['body'];
        Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], $data);
        exit;

    }

}