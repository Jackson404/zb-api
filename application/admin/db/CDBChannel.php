<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 2017/12/14 0014
 * Time: 15:41
 */

namespace app\admin\db;


use app\admin\common\Util;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;

class CDBChannel
{
    /**
     * 获取系统帐号列表
     * @param $serverId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getSystemUsers($serverId)
    {
        try {
            $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
            $DB = (new CDBFeedback())->connectDbByServerIp($serverIp);
            $result = $DB->name('user')
                ->where('user_type', 'eq', 2)
                ->select();
            return $result;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /**
     * 检测频道名字是否存在
     * @param $channelName
     * @param $serverId
     * @return int|string
     */
    public function verifyChannelNameExists($channelName, $serverId)
    {
        $count = Db::name('channel')
            ->where('channel_name', 'eq', $channelName)
            ->where('serverId', 'eq', $serverId)
            ->count('channel_name');
        return $count;
    }

    /**
     * 创建频道
     * @param $channelName
     * @param $channelKey
     * @param $serverId
     * @return int|string
     */
    public function createChannel($channelName, $channelKey, $serverId)
    {
        $data = [
            'channel_name' => $channelName,
            'create_time' => currentTime(),
            'update_time' => currentTime(),
            'channel_key' => $channelKey,
            'serverId' => $serverId
        ];
        $count = Db::name('channel')->insert($data);
        return $count;
    }

    /**
     * @param $channelId
     * @param $channelName
     * @param $serverId
     * @return int|string
     */
    public function updateChannel($channelId, $channelName, $serverId)
    {
        try {
            $count = Db::name('channel')
                ->where('id', 'eq', $channelId)
                ->where('serverId', 'eq', $serverId)
                ->update([
                    'channel_name' => $channelName,
                    'update_time' => currentTime()
                ]);
            return $count;
        } catch (PDOException $e) {
            Util::printResult($GLOBALS['ERROR_PDO_EXCEPTION'], $e->getMessage());
            exit;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /**
     * @param $channelId
     *
     * @return int
     */
    public function deleteChannel($channelId)
    {
        try {
            $count = Db::name('channel')
                ->where('id', 'eq', $channelId)
                ->delete();
            return $count;
        } catch (PDOException $e) {
            Util::printResult($GLOBALS['ERROR_PDO_EXCEPTION'], $e->getMessage());
            exit;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }

    }

    /**
     * @param $serverId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getChannelList($serverId)
    {
        $result = DB::name('channel')
            ->where('serverId', 'eq', $serverId)
            ->select();
        return $result;
    }

    /**
     *
     * 根据频道Id获取该频道下的所有系统用户
     * @param $channelId
     * @return mixed
     */
    public function getSystemUserIdByChannelId($channelId)
    {
        $result = Db::name('channel')
            ->where('id', 'eq', $channelId)
            ->value('system_userId');
        return $result;
    }

    /**
     *
     * 根据频道Id获取该频道下的所有系统用户
     * @param $channelId
     * @return mixed
     */
    public function getSystemUserNameByChannelId($channelId)
    {
        $result = Db::name('channel')
            ->where('id', 'eq', $channelId)
            ->value('system_userName');
        return $result;
    }


    /**
     *
     * 添加系统用户到指定的频道
     * @param $channelId
     * @param $systemUserId
     * @param $systemUserName
     * @return int|string
     */
    public function updateSystemUserIdToChannel($channelId, $systemUserId, $systemUserName)
    {
        $data = [
            'system_userId' => $systemUserId,
            'system_username' => $systemUserName
        ];
        try {
            $count = Db::name('channel')
                ->where('id', 'eq', $channelId)
                ->update($data);
            return $count;
        } catch (PDOException $e) {
            Util::printResult($GLOBALS['ERROR_PDO_EXCEPTION'], $e->getMessage());
            exit;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }

    }

    /**
     * 通过系统帐号id获取系统帐号昵称
     * @param $systemUserId
     * @param $serverId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getSystemUserNameById($systemUserId, $serverId)
    {
        try {
            $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
            $DB = (new CDBFeedback())->connectDbByServerIp($serverIp);
            $result = $DB
                ->name('user')->where('id', 'eq', $systemUserId)
                ->value('nickname');
            return $result;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /**
     * 通过系统帐号的Id获取系统帐号的推文
     * @param $systemUserId
     * @param $serverId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getPostsBySystemUserId($systemUserId, $serverId)
    {
        try {
            $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
            $DB = (new CDBFeedback())->connectDbByServerIp($serverIp);
            $result = $DB
                ->table('gener_zonePost')->where('userId', 'eq', $systemUserId)
                ->select();
            return $result;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }

    }

    /**
     * @param $systemUserId
     * @param $pageSize
     * @param $pageIndex
     * @param $serverId
     * @return \think\Paginator
     */
    public function getPostsBySystemUserIdPaging($systemUserId, $pageSize, $pageIndex, $serverId)
    {
        try {
            $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
            $DB = (new CDBFeedback())->connectDbByServerIp($serverIp);
            $result = $DB
                ->table('gener_zonePost')->where('userId', 'eq', $systemUserId)
                ->paginate($pageSize, false, ['page' => $pageIndex]);
            return $result;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }


    /**
     * @param $channelKey
     * @return string
     */
    public function getUserTimeLineKey($channelKey)
    {
        return 'ck' . $channelKey;
    }

    /**
     * @param $channelKey
     * @param $postIds
     * @param $postTimes
     * @param $serverId
     * @return mixed
     */
    public function setPushPostsIdCollection($channelKey, $postIds, $postTimes, $serverId)
    {
        $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
        $CDBRedis = new CDBRedis(['host' => $serverIp]);

        $postIdArr = explode(',', $postIds);
        $postTimeArr = explode(',', $postTimes);

        $CDBRedis->handler()->multi();
        $CDBRedis->handler()->select(2);
        foreach ($postIdArr as $key => $postId) {
            $postTime = $postTimeArr[$key];
            $postTime = strtotime($postTime);
            $CDBRedis->Zadd($this->getUserTimeLineKey($channelKey), $postTime, $postId);
        }
        $result = $CDBRedis->handler()->exec();
        return $result['0'];
    }

    /**
     * @param $channelKey
     * @param $postIds
     * @param $serverId
     * @return mixed
     */
    public function deletePushPostsIdCollection($channelKey, $postIds, $serverId)
    {
        $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
        $CDBRedis = new CDBRedis(['host' => $serverIp]);
        $postIdArr = explode(',', $postIds);

        $CDBRedis->handler()->multi();
        $CDBRedis->handler()->select(2);
        foreach ($postIdArr as $postId) {
            $CDBRedis->handler()->zrem($this->getUserTimeLineKey($channelKey), $postId);
        }
        $result = $CDBRedis->handler()->exec();
        return $result['1'];
    }

    public function getPushPostIdsByChannelKey($channelKey, $serverId)
    {
        $serverIp = (new CDBFeedback())->getServerIpByServerId($serverId);
        $CDBRedis = new CDBRedis(['host' => $serverIp]);
        $CDBRedis->handler()->multi();
        $CDBRedis->handler()->select(2);
        $CDBRedis->handler()->zrange($this->getUserTimeLineKey($channelKey), 0, -1);
        $result = $CDBRedis->handler()->exec();
        return $result[1];

    }

}