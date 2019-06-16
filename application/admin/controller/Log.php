<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1 0001
 * Time: 10:39
 * 错误日志的统计 分类
 */

namespace app\admin\controller;

use app\admin\common\Check;
use app\admin\common\SFTPConnection;

use app\admin\common\Upload;
use app\admin\db\CDBAccount;
use app\admin\common\Logs;
use app\admin\common\Util;
use app\admin\db\CDBServer;
use think\Controller;
use think\Db;
use think\Request;


class Log extends Controller
{
    /**
     * 日志的添加
     */
    public function logInsert()
    {
        $CDBAccount = new CDBAccount();
        if (!$CDBAccount->isLogin()) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录错误');
            exit;
        }
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $username = Check::check(isset($_POST['username']) ? $_POST['username'] : '');
        $password = Check::check(isset($_POST['password']) ? $_POST['password'] : '');
        $path = Check::check(isset($_POST['path']) ? $_POST['path'] : '');
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
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
        if ($host == '' || $username == '' || $password == '' || $path == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $arr = $SFTPConnection->scanFilesystem($path);
        $num = 0;
        foreach ($arr as $file) {
            $logs = new Logs();
            $count = $logs->logInsertByFilename($file, $serverId);
            $num += $count;
        }
        if (!$num) {
            Util::printResult($GLOBALS['ERROR_RECORD_INSERT'], '无需要添加的日志记录');
            exit;
        }
        $data['insertCount'] = $num;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }


    /**
     * 获取全部的日志信息
     */
    public function showAllLogs()
    {
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $logs = new logs();
        $logsList = $logs->getAllLogs($serverId);
        if (!$logsList) {
            Util::printResult($GLOBALS['ERROR_SQL_QUERY'], '没有记录');
            exit;
        }
        $data['logsList'] = $logsList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;

    }

    /**
     * 分页获取全部的日志信息
     */
    public function showAllLogsByPage()
    {
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $perPage = Check::checkInteger(isset($_POST['perPage']) ? $_POST['perPage'] : 15);
        $currentPage = Check::checkInteger(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1);
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $logs = new logs();
        $logsList = $logs->getAllLogsByPage($serverId, $perPage, $currentPage);
        $data['logsList'] = $logsList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }


    /**
     * 添加日志类型
     */
    public function insertLogType()
    {
        $logType = Check::check(isset($_POST['logType']) ? $_POST['logType'] : '');
        $createBy = Check::checkInteger(isset($_POST['createBy']) ? $_POST['createBy'] : '');
        if ($logType == '' || $createBy == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $logs = new logs();
        $count = $logs->logTypeInsert($logType, $createBy);
        if ($count) {
            $data['insertCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }
    }


    /**
     * 获取全部的日志类型
     */
    public function showAllLogType()
    {
        $logs = new logs();
        $result = $logs->getAllLogType();
        $data['logTypeList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }


    /**
     * 根据不同的日志类型过滤显示
     */
    public function showLogByLogType()
    {
        $logTypeId = Check::checkInteger(isset($_POST['logTypeId']) ? $_POST['logTypeId'] : '');
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $CDBServer = new CDBServer();
        if (!$CDBServer->checkServerIdExists($serverId) > 0) {
            Util::printResult($GLOBALS['ERROR_SERVERID_EXISTS'], '服务器Id不存在');
            exit;
        }
        $logShowByType = new Logs();
        $result = $logShowByType->getLogByType($logTypeId, $serverId);
        $data['logsList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }


    /**
     * 根据不同的日志类型分页显示
     */
    public function showLogByLogTypePage()
    {
        $serverId = Check::checkInteger(isset($_POST['serverId']) ? $_POST['serverId'] : '');
        $logTypeId = Check::checkInteger(isset($_POST['logTypeId']) ? $_POST['logTypeId'] : '');
        $perPage = Check::checkInteger(isset($_POST['perPage']) ? $_POST['perPage'] : 15);
        $currentPage = Check::checkInteger(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1);
        if ($logTypeId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $logShowByType = new Logs();
        $result = $logShowByType->getLogByTypePage($logTypeId, $serverId, $perPage, $currentPage);
        $data['logsList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 把手机上的日志文件上传到cdn 单文件
     */
    public function addLogByPhone()
    {
        $name = 'file';
        $Upload = new Upload();
        $result = $Upload->uploadLogInMobile($name);
        if ($result) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '文件上传成功');
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], '文件上传失败');
            exit;
        }
    }

    /**
     * 把手机上的文件上传到cdn 多文件
     */
    public function addLogsByPhone()
    {
        $data = Request::instance()->param();
        $size = $data['size'] ?? '';
        $ext = $data['ext'] ?? '';
        $Upload = new Upload();
        $result = $Upload->uploadFilesByPhone($size, $ext);

        if ($result) {
            $url = json_encode($result);
            $arr = [
                'url' => $url,
                'create_time' => date('Y-m-d H:i:s', time()),
                'update_time' => date('Y-m-d H:i:s', time())
            ];
            $count = Db::name('log_phone')->insert($arr);
            if ($count > 0) {
                Util::printResult($GLOBALS['ERROR_SUCCESS'], '文件上传成功');
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], '文件上传失败');
                exit;
            }
        } else {
            Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], '文件上传失败');
            exit;
        }
    }

    /**
     * 分页获取上传的文件的地址
     */
    public function getLogListPhonePaging()
    {
        $data = Request::instance()->param();
        $pageIndex = $data['pageIndex'] ?? 1;
        $pageSize = $data['pageSize'] ?? 15;
        $result = (new Logs())->getLogListPhonePaging($pageIndex, $pageSize);
        $items = $result->items();
        foreach ($items as $key => $v) {
            $vArr = json_decode($v['url'], true);
            $items["$key"]['url'] = $vArr;
        }
        $data['total'] = $result->total();
        $data['current_page'] = $result->currentPage();
        $data['per_page'] = $result->listRows();
        $data['last_page'] = $result->lastPage();
        $data['data']['list'] = $items;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }
}