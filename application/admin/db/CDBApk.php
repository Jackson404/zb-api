<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 16:59
 */

namespace app\admin\db;

use app\admin\common\Util;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;

class CDBApk
{
    /**
     * 分页获取apk列表
     *
     * @param $row
     * @param $currentPage
     * @return \think\Paginator
     */
    public function getApkListPage($row, $currentPage)
    {
        try {
            $result = Db::name('apk')
                ->order('id', 'DESC')
                ->paginate($row, false, ['page' => $currentPage]);
            return $result;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /**
     * 分页获取apk列表----v2
     *
     * @param $row
     * @param $currentPage
     * @return \think\Paginator
     */
    public function getApkListPageV2($apkId,$row, $currentPage)
    {
        try {
            $result = Db::name('apk')
                ->where('apk_id','eq',$apkId)
                ->where('is_delete','eq',0)
                ->order('id', 'DESC')
                ->paginate($row, false, ['page' => $currentPage]);
            return $result;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
    }
    /**
     * 分页获取apk列表 --- test ---v2
     * @param $apkId
     * @param $row
     * @param $currentPage
     * @return \think\Paginator
     */
    public function getApkListTestPage($apkId,$row, $currentPage)
    {
        try {
            $result = Db::name('apk_test')
                ->where('apk_id','eq',$apkId)
                ->where('is_delete','eq',0)
                ->order('id', 'DESC')
                ->paginate($row, false, ['page' => $currentPage]);
            return $result;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /**
     * 上传apk
     * @param $data
     * @return int|string
     */
    public function uploadApk($data)
    {
        $count = Db::name('apk')->insert($data);
        return $count;
    }

    /**
     * 上传apk ---test
     * @param $data
     * @return int|string
     */
    public function uploadApkTest($data)
    {
        $count = Db::name('apk_test')->insert($data);
        return $count;
    }

    /**
     * 获取最新版本
     * @param $apkId
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function showApkLatestVersion()
    {
        try {
            $result = Db::name('apk')
                ->where('is_delete','eq',0)
               // ->where('apk_id','eq',$apkId)
                ->order('id', 'DESC')
                ->find();
            return $result;
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
    }
    /**
     * 获取最新版本-----V2
     * @param $apkId
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function showApkLatestVersionV2($apkId)
    {
        try {
            $result = Db::name('apk')
                ->where('is_delete','eq',0)
                ->where('apk_id','eq',$apkId)
                ->order('id', 'DESC')
                ->find();
            return $result;
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
    }

    /**
     * 获取最新版本---test---v2
     * @param $apkId
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function showTestApkLatestVersion($apkId)
    {
        try {
            $result = Db::name('apk_test')
                ->where('is_delete','eq',0)
                ->where('apk_id','eq',$apkId)
                ->order('id', 'DESC')
                ->find();
            return $result;
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
    }


    /**
     * apk名字添加进数据库
     * @param $name
     * @return bool|int|string
     */
    public function apkNameInsert($name)
    {
        $data = [
            'apk_name' => $name,
            'create_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time())
        ];
        $count = Db::name('apk_list')->insert($data);
        if ($count > 0) {
            return $count;
        }
        return false;
    }

    /**
     * 更新apk名字
     * @param $apkId
     * @param $name
     * @return int|string
     */
    public function apkNameUpdate($apkId,$name)
    {
        $data = [
            'apk_name' => $name,
            'update_time' => date('Y-m-d H:i:s', time())
        ];
        try {
            $count = Db::name('apk_list')
                ->where('is_delete','eq',0)
                ->where('id','eq',$apkId)
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
     * 获取apk名字列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getApkNameList()
    {
        try {
            $result = Db::name('apk_list')
                ->select();
            return $result;
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
    }


    /**
     * 删除apk上传记录 正式的
     * @param $uploadApkId
     * @return int|string
     */
    public function rescindApk($uploadApkId){

        $delRow = Db::name('apk')->where('id','eq',$uploadApkId)->update(['is_delete'=>1]);
        return $delRow;
    }


    /**
     * 删除apk上传记录 测试的
     * @param $uploadApkId
     * @return int|string
     */
    public function rescindApkTest($uploadApkId){
        $delRow = Db::name('apk_test')->where('id','eq',$uploadApkId)->update(['is_delete'=>1]);
        return $delRow;
    }


}