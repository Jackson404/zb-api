<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 18:02
 */

namespace app\admin\controller;

use app\admin\common\Check;
use app\admin\common\Util;
use app\admin\db\CDBAccount;
use app\admin\db\CDBFeedback;
use think\Controller;

class FeedBack extends Controller
{
    /**
     * 获取所有的反馈信息
     */
    public function getAllFeedbackMsgByServerId()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        if ($serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $result = $CDBFeedback->getFeedbackMsg($serverId);
        $data['feedList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;

    }

    /**
     * 分页获取反馈信息
     */
    public function getFeedbackMsgPaging()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));

        if ($serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $result = $CDBFeedback->getFeedbackMsgPage($serverId, $perPage, $currentPage);
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $result);
        exit;
    }

    /**
     * 添加服务器名字，地址，创建者 到数据库
     */
    public function insertServers()
    {
        $CDBAccount = new CDBAccount();
        if (!$CDBAccount->isLogin()) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录错误');
            exit;
        }
        $serverName = Check::check(trim(isset($_POST['serverName']) ? $_POST['serverName'] : ''));
        $serverIp = Check::check(trim(isset($_POST['serverIp']) ? $_POST['serverIp'] : ''));
        $createBy = Check::check(trim(isset($_POST['createBy']) ? $_POST['createBy'] : ''));
        if ($serverName == '' || $serverIp == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '参数缺失');
            exit;
        }
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->insertServers($serverName, $serverIp, $createBy);
        if (!$count) {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据插入错误');
            exit;
        }
        $data['count'] = $count;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 获取服务器列表
     */
    public function getServerLists()
    {
        $CDBFeedback = new CDBFeedback();
        $result = $CDBFeedback->getAllServerList();
        $data['serverList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 获取已经处理的反馈信息
     */
    public function getHandledFeedbackMsg()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
        if ($serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $result = $CDBFeedback->getAllHandled($serverId, $perPage, $currentPage);
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $result);
        exit;
    }

    /**
     * 获取未处理的反馈信息
     */
    public function getUntreatedFeedbackMsg()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
        if ($serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $result = $CDBFeedback->getAllUntreated($serverId, $perPage, $currentPage);
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $result);
        exit;
    }

    /**
     * 获取处理中的反馈信息
     */
    public function getHandlingFeedbackMsg()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $perPage = Check::checkInteger(trim(isset($_POST['perPage']) ? $_POST['perPage'] : 15));
        $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
        if ($serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $result = $CDBFeedback->getAllHandling($serverId, $perPage, $currentPage);
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $result);
        exit;
    }

    /**
     * 更改处理状态
     */
    public function updateFeedbackHandleStatus()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $handleStatus = Check::checkInteger(trim(isset($_POST['handleStatus']) ? $_POST['handleStatus'] : ''));
        $feedbackId = Check::checkInteger(trim(isset($_POST['feedbackId']) ? $_POST['feedbackId'] : ''));

        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $count = $CDBFeedback->updateHandleStatus($serverId, $handleStatus, $feedbackId);
        if ($count > 0) {
            $data['count'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '无更新');
        exit;
    }

    /**
     * 更新反馈信息的优先级
     */
    public function updateFeedbackPriority()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $feedbackId = Check::checkInteger(trim(isset($_POST['feedbackId']) ? $_POST['feedbackId'] : ''));
        $type = Check::checkInteger(trim(isset($_POST['type']) ? $_POST['type'] : ''));
        $CDBFeedback = new CDBFeedback();
        $count = $CDBFeedback->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $result = $CDBFeedback->updateFeedbackPriority($serverId, $type, $feedbackId);
        if ($result > 0) {
            $data['updateCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '无更新');
        exit;
    }
}