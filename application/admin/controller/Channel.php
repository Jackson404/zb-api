<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 2017/12/14 0014
 * Time: 15:39
 */

namespace app\admin\controller;


use app\admin\common\Check;
use app\admin\common\Util;
use app\admin\db\CDBChannel;
use app\admin\db\CDBFeedback;
use think\Controller;

class Channel extends Controller
{
    /**
     * 获取系统帐号列表
     */
    public function getSystemUsers()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        if ($serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->getSystemUsers($serverId);
        $data['systemUserList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 添加频道名字
     */
    public function addChannel()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $channelName = Check::check(trim(isset($_POST['channelName']) ? $_POST['channelName'] : ''));
        $channelKey = Check::check(trim(isset($_POST['channelKey']) ? $_POST['channelKey'] : ''));
        if ($channelName == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->verifyChannelNameExists($channelName, $serverId);
        if ($result > 0) {
            Util::printResult($GLOBALS['ERROR_CHANNEL_EXISTS'], '频道名字已经存在');
            exit;
        }
        $count = $CDBChannel->createChannel($channelName, $channelKey, $serverId);
        if ($count > 0) {
            $data['insertCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据添加错误');
        exit;
    }

    /**
     *  更新频道名字
     */
    public function updateChannel()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $channelName = Check::check(trim(isset($_POST['channelName']) ? $_POST['channelName'] : ''));
        $channelId = Check::checkInteger(trim(isset($_POST['channelId']) ? $_POST['channelId'] : ''));
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->verifyChannelNameExists($channelName, $serverId);
        if ($result > 0) {
            Util::printResult($GLOBALS['ERROR_CHANNEL_EXISTS'], '频道名字已经存在');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $count = $CDBChannel->updateChannel($channelId, $channelName, $serverId);
        if ($count > 0) {
            $data['updateCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '数据更新错误');
        exit;
    }

    /**
     *  删除频道名字
     */
    public function deleteChannel()
    {
        $channelId = Check::checkInteger(trim(isset($_POST['channelId']) ? $_POST['channelId'] : ''));
        $CDBChannel = new CDBChannel();
        $count = $CDBChannel->deleteChannel($channelId);
        if ($count > 0) {
            $data['deleteCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
        exit;
    }

    public function getChannel()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->getChannelList($serverId);
        $arr = array();
        foreach ($result as $v) {
            $systemUserId = $v['system_userId'];
            $systemUserIdArr = explode('|', $systemUserId);
            array_pop($systemUserIdArr);
            $v['system_userId'] = $systemUserIdArr;
            $systemUserName = $v['system_username'];
            $systemUserNameArr = explode('|', $systemUserName);
            array_pop($systemUserNameArr);
            $v['system_username'] = $systemUserNameArr;
            array_push($arr, $v);
        }
        $data['channelList'] = $arr;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 添加系统用户到指定的频道
     */
    public function updateSystemUserIdToChannel()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $systemUserId = Check::checkInteger(trim(isset($_POST['systemUserId']) ? $_POST['systemUserId'] : ''));
        $channelId = Check::checkInteger(trim(isset($_POST['channelId']) ? $_POST['channelId'] : ''));
        if ($systemUserId == '' || $channelId == '' || $serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $systemUserName = $CDBChannel->getSystemUserNameById($systemUserId, $serverId);

        $result = $CDBChannel->getSystemUserIdByChannelId($channelId);
        if ($result != null) {
            $usersArr = explode('|', $result);
            array_pop($usersArr);

            if (in_array($systemUserId, $usersArr)) {
                Util::printResult($GLOBALS['ERROR_SYSTEMUSER_EXISTS'], '系统用户已经存在');
                exit;
            }
            $systemUserId = $result . $systemUserId . '|';
        } else {
            $systemUserId = $systemUserId . '|';
        }
        $systemUserNames = $CDBChannel->getSystemUserNameByChannelId($channelId);
        if ($systemUserNames != null) {
            $usersArr = explode('|', $systemUserNames);
            array_pop($usersArr);

            if (in_array($systemUserName, $usersArr)) {
                Util::printResult($GLOBALS['ERROR_SYSTEMUSER_EXISTS'], '系统用户名已经存在');
                exit;
            }
            $systemUserName = $systemUserNames . $systemUserName . '|';
        } else {
            $systemUserName = $systemUserName . '|';
        }
        $count = $CDBChannel->updateSystemUserIdToChannel($channelId, $systemUserId, $systemUserName);
        if ($count > 0) {
            $data['insertCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据添加错误');
        exit;
    }

    /**
     *  通过系统帐号id获取系统帐号昵称
     */
    public function getSystemUserNameById()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $systemUserId = Check::checkInteger(trim(isset($_POST['systemUserId']) ? $_POST['systemUserId'] : ''));
        if ($systemUserId == '' || $serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->getSystemUserNameById($systemUserId, $serverId);
        $data['nickName'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;

    }

    /**
     *  通过系统帐号的Id获取系统帐号的推文
     */
    public function getPostsBySystemUserId()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $systemUserId = Check::checkInteger(trim(isset($_POST['systemUserId']) ? $_POST['systemUserId'] : ''));
        if ($systemUserId == '' || $serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->getPostsBySystemUserId($systemUserId, $serverId);
        $data['posts'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     *  通过系统帐号的Id分页获取系统帐号的推文
     */
    public function getPostsBySystemUserIdPaging()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $systemUserId = Check::checkInteger(trim(isset($_POST['systemUserId']) ? $_POST['systemUserId'] : ''));
        $pageIndex = Check::checkInteger(trim(isset($_POST['pageIndex']) ? $_POST['pageIndex'] : 1));
        $pageSize = Check::checkInteger(trim(isset($_POST['pageSize']) ? $_POST['pageSize'] : 15));
        if ($systemUserId == '' || $serverId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->getPostsBySystemUserIdPaging($systemUserId, $pageSize, $pageIndex, $serverId);
        $data['posts'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     *  设置发送推文数据集
     */
    public function setPushPostsIdCollection()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));
        $channelKey = Check::check(trim(isset($_POST['channelKey']) ? $_POST['channelKey'] : ''));
        $postId = Check::check(trim(isset($_POST['postId']) ? $_POST['postId'] : ''));
        $postTimes = Check::check(trim(isset($_POST['postTimes']) ? $_POST['postTimes'] : ''));
        if ($postId == '' || $postTimes == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->setPushPostsIdCollection($channelKey, $postId, $postTimes, $serverId);
        $data['insertPostsIdStatus'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;

    }

    public function getPushPostIdsByChannelKey()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));

        $channelKey = Check::check(trim(isset($_POST['channelKey']) ? $_POST['channelKey'] : ''));
        if ($channelKey == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->getPushPostIdsByChannelKey($channelKey, $serverId);
        $data['postIdList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     *  从推文集合中删除postId
     */
    public function deletePushPostsIdCollection()
    {
        $serverId = Check::checkInteger(trim(isset($_POST['serverId']) ? $_POST['serverId'] : ''));

        $channelKey = Check::check(trim(isset($_POST['channelKey']) ? $_POST['channelKey'] : ''));
        $postId = Check::check(trim(isset($_POST['postId']) ? $_POST['postId'] : ''));

        if ($postId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $count = (new CDBFeedback)->checkServerIdExists($serverId);
        if (!$count > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $CDBChannel = new CDBChannel();
        $result = $CDBChannel->deletePushPostsIdCollection($channelKey, $postId, $serverId);
        $data['deletePostsIdStatus'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }


}