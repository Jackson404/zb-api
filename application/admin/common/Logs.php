<?php
///**
// * Created by PhpStorm.
// * User: Administrator
// * Date: 2017/11/2 0002
// * Time: 13:17
// */
//
//namespace app\admin\common;
//
//use think\Db;
//use think\db\exception\DataNotFoundException;
//use think\db\exception\ModelNotFoundException;
//use think\exception\DbException;
//
//
//class Logs
//{
//
//    /*
//     * 通过文件名插入日志
//     * @param  文件名
//     * */
//
//    public function logInsertByFilename($filename, $serverId)
//    {
//
//        $file = file_get_contents($filename);
//        $arr = explode('[] []', $file);
//        //去除数组最后一个空值
//        unset($arr[count($arr) - 1]);
//
//        $data = array();
//        foreach ($arr as $key => $a) {
//            $a = trim($a);
//            $position = strpos($a, ']');
//            $logTime = substr($a, '1', $position - 1);
//            $lastRecordTime = $this->lastRecordTime($serverId);
//            if ($lastRecordTime != null && $logTime <= $lastRecordTime) continue;
//            $content = trim(substr($a, $position + 2));
//            $pos = stripos($content, ':');
//            $logType = trim(substr($content, 0, $pos));
//            $array_data = array(
//                'time' => $logTime,
//                'content' => $content,
//                'log_type' => $logType,
//                'create_time' => date('Y-m-d H:i:s', time()),
//                'server_id' => $serverId
//            );
//            array_push($data, $array_data);      //把数据添加到数组最后面
//        }
//
//        $count = DB::name('log')->insertAll($data);
//        if ($count > 0) {
//            return $count;
//        }
//        return false;
//    }
//
//    /*
//     * 获取数据库中最后一条记录的时间
//     * */
//    public function lastRecordTime($serverId)
//    {
//        $lastRecordTime = DB::name('log')->where('server_id', 'eq', $serverId)->order('id', 'DESC')->value('time');
//        return $lastRecordTime;
//    }
//
//    /*
//     * 添加日志类型
//     * @param $logType
//     * */
//    public function logTypeInsert($logType, $createBy)
//    {
//        $data = [
//            'log_type' => $logType,
//            'create_time' => date('Y-m-d H:i:s', time()),
//            'create_by' => $createBy
//        ];
//        $count = DB::name('log_type')->insert($data);
//        if ($count > 0) {
//            return $count;
//        }
//        return false;
//    }
//
//    /*
//     * 获取全部的日志类型信息
//     * */
//    public function getAllLogType()
//    {
//        try {
//            $result = DB::name('log_type')->select();
//            return $result;
//        } catch (DataNotFoundException $e) {
//            Util::printResult($GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'], $e->getMessage());
//            exit;
//        } catch (ModelNotFoundException $e) {
//            Util::printResult($GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'], $e->getMessage());
//            exit;
//        } catch (DbException $e) {
//            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
//            exit;
//        }
//
//    }
//
//    /*
//     * @param $level 日志不同错误类型
//     * 根据不同错误类型过滤显示日志记录
//     * */
//    public function getLogByType($logTypeId, $serverId)
//    {
//        $map = [
//            'b.id' => $logTypeId,
//            'a.server_id' => $serverId
//        ];
//        try {
//            $getLog = DB::name('log as a')
//                ->field(['a.id', 'a.content', 'a.time', 'a.log_type', 'a.create_time', 'a.server_id', 'b.id' => 'logTypeId'])
//                ->join(['familymanage_log_type' => 'b'], 'a.log_type = b.log_type', 'INNER')
//                ->where($map)
//                ->order('a.id', 'DESC')
//                ->select();
//            return $getLog;
//        } catch (DataNotFoundException $e) {
//            Util::printResult($GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'], $e->getMessage());
//            exit;
//        } catch (ModelNotFoundException $e) {
//            Util::printResult($GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'], $e->getMessage());
//            exit;
//        } catch (DbException $e) {
//            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
//            exit;
//        }
//
//    }
//
//    /*
//     * @param $level 日志不同错误类型
//     * 根据不同错误类型过滤显示日志记录
//     * */
//    public function getLogByTypePage($logTypeId, $serverId, $perPage, $currentPage)
//    {
//        $map = [
//            'b.id' => $logTypeId,
//            'a.server_id' => $serverId
//        ];
//        try {
//            $getLog = DB::name('log as a')
//                ->field(['a.id', 'a.content', 'a.time', 'a.log_type', 'a.create_time', 'a.server_id', 'b.id' => 'logTypeId'])
//                ->join(['familymanage_log_type' => 'b'], 'a.log_type = b.log_type', 'INNER')
//                ->where($map)
//                ->order('a.id', 'DESC')
//                ->paginate($perPage, false, ['page' => $currentPage]);
//            return $getLog;
//        } catch (DbException $e) {
//            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
//            exit;
//        }
//    }
//
//    /*
//     * 获取全部的日志信息
//     * */
//    public function getAllLogs($serverId)
//    {
//        try {
//            $logs = DB::name('log as a')
//                ->field(['a.id', 'a.content', 'a.time', 'a.log_type', 'a.create_time', 'a.server_id', 'b.id' => 'logTypeId'])
//                ->join(['familymanage_log_type' => 'b'], 'a.log_type = b.log_type', 'LEFT')
//                ->where('server_id', 'eq', $serverId)
//                ->order('id', 'DESC')
//                ->select();
//            if ($logs == []) {
//                return false;
//            }
//            return $logs;
//        } catch (DataNotFoundException $e) {
//            Util::printResult($GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'], $e->getMessage());
//            exit;
//        } catch (ModelNotFoundException $e) {
//            Util::printResult($GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'], $e->getMessage());
//            exit;
//        } catch (DbException $e) {
//            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
//            exit;
//        }
//    }
//
//    /*
//     * 分页获取全部的日志信息
//     * @param
//     * */
//    public function getAllLogsByPage($serverId, $perPage, $currentPage)
//    {
//        try {
//            $logs = DB::name('log as a')
//                ->field(['a.id', 'a.content', 'a.time', 'a.log_type', 'a.create_time', 'a.server_id', 'b.id' => 'logTypeId'])
//                ->join(['familymanage_log_type' => 'b'], 'a.log_type = b.log_type', 'LEFT')
//                ->where('server_id', 'eq', $serverId)
//                ->order('id', 'DESC')
//                ->paginate($perPage, false, ['page' => $currentPage]);
//            return $logs;
//        } catch (DbException $e) {
//            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
//            exit;
//        }
//    }
//
//    public function getLogListPhonePaging($pageIndex, $pageSize)
//    {
//        $result = Db::name('log_phone')
//            ->order('id', 'DESC')
//            ->paginate($pageSize, false, ['page' => $pageIndex]);
//        return $result;
//    }
//}