<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 2017/11/24 0024
 * Time: 14:06
 */

namespace app\admin\db;

use app\admin\common\Util;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class CDBServer
{
    /*
       * 检测serverId 是否存在
       * */
    public function  checkServerIdExists($serverId){
        $count=DB::name('servers')->where('id','eq',$serverId)->count('server_name');
        return $count;
    }
    /*
     *获取服务器列表
     * */
    public function getAllServerList()
    {
        try {
            $result = DB::name('servers')->select();
        } catch (DataNotFoundException $e) {
            Util::printResult($GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'], $e->getMessage());
            exit;
        } catch (ModelNotFoundException $e) {
            Util::printResult($GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'], $e->getMessage());
            exit;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        return $result;
    }
    /*
     * 通过数据库服务器ID 获取服务器IP
     * @param $serverId 服务器Id
     *
     * */
    public function getServerIpByServerId($serverId)
    {
        $serverIp = DB::name('servers')->where('id', 'eq', $serverId)->value('server_ip');
        if ($serverIp) {
            return $serverIp;
        }
    }
    /*
    * 把数据库服务器地址 名字添加进数据库、
    * @param $serverName 服务器名字
    * @param $serverIp   服务器Ip
    * @param $createBy   创建者
    * */
    public function insertServers($serverName, $serverIp, $createBy)
    {
        $data = [
            'server_name' => $serverName,
            'server_ip' => $serverIp,
            'create_by' => $createBy,
            'create_time' => date('Y-m-d H:i:s', time())
        ];
        $count = DB::name('servers')->insert($data);
        if ($count > 0) {
            return $count;
        }
        return false;
    }
}