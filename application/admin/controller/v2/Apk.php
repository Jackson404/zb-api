<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 2017/12/4 0004
 * Time: 13:54
 */

namespace app\admin\controller\v2;

use app\admin\db\CDBAccount;
use app\admin\db\CDBApk;
use app\admin\common\Check;
use app\admin\common\Upload;
use app\admin\common\Util;
use think\Controller;
use think\Request;


class Apk extends Controller
{
    /**
     *  apk上传
     */
    public function apkUpload()
    {
        $CDBAccount = new CDBAccount();
        if (!$CDBAccount->isLogin()) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录错误');
            exit;
        }
        if (Request::instance()->isPost()) {
            $apkId = Check::checkInteger(trim(isset($_POST['apkId']) ? $_POST['apkId'] : '')); //apk_id
            $isTest = Check::checkInteger(trim(isset($_POST['isTest']) ? $_POST['isTest'] : 0)); //1是测试 0是正常 默认是0
            $version = Check::check(trim(isset($_POST['version']) ? $_POST['version'] : ''));
            $updateInfo = Check::check(trim(isset($_POST['updateInfo']) ? $_POST['updateInfo'] : ''));
            if (!array_key_exists('apk', $_FILES) || !$_FILES['apk']['tmp_name'] || $apkId == '' || $version == '' || $updateInfo == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }
            $name = 'apk';
            $upload = new Upload();
            $data = $upload->uploadFiles($name);
            if (!$data) {
                Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], '请上传apk类型的文件');
                exit;
            }
            $url = $data['apk_url'];
            $size = $data['size'];
            $data = [
                'size' => $size,
                'apk_url' => $url,
                'update_info' => $updateInfo,
                'version_number' => $version,
                'create_time' => date('Y-m-d H:i:s', time()),
                'apk_id' => $apkId
            ];
            $CDBApk = new CDBApk();
            if ($isTest == 0) {
                $res = $CDBApk->showApkLatestVersionV2($apkId);
                $latestVersion = $res['version_number'];
                if ($latestVersion >= $version) {
                    Util::printResult($GLOBALS['ERROR_VERSION_INPUT'], '新输入的版本号小于或者等于最新的版本号，请重新输入');
                    exit;
                }
                $result = $CDBApk->uploadApk($data);

            }
            if ($isTest == 1) {
                $res = $CDBApk->showTestApkLatestVersion($apkId);
                $latestVersion = $res['version_number'];
                if ($latestVersion >= $version) {
                    Util::printResult($GLOBALS['ERROR_VERSION_INPUT'], '新输入的版本号小于或者等于最新的版本号，请重新输入');
                    exit;
                }
                $result = $CDBApk->uploadApkTest($data);
            }
            if ($result > 0) {
                $data['count'] = $result;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据插入出现错误');
                exit;
            }
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }

    }

    /*
     * 获取最新版本
     * */
    public function getLatestVersion()
    {
        if (request()->isPost()) {
            $apkId = Check::checkInteger(trim(isset($_POST['apkId']) ? $_POST['apkId'] : ''));
            $isTest = Check::checkInteger(trim(isset($_POST['isTest']) ? $_POST['isTest'] : 0)); //1是测试 0是正常 默认是0
            if ($apkId == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }
            $CDBApk = new CDBApk();
            if ($isTest == 0) {
                $result = $CDBApk->showApkLatestVersionV2($apkId);
            }
            if ($isTest == 1) {
                $result = $CDBApk->showTestApkLatestVersion($apkId);
            }

            if ($result) {
                $data = [
                    'apk_id' => $result['apk_id'],
                    'apk_url' => $result['apk_url'],
                    'update_info' => $result['update_info'],
                    'version_number' => $result['version_number'],
                    'size' => $result['size'],
                    'create_time' => $result['create_time']
                ];

            } else {
                $data = [];
            }
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;

        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }


    /*
    * 分页获得apk列表
    * */
    public function getApkListByPage()
    {
        if (request()->isPost()) {
            $apkId = Check::checkInteger(trim(isset($_POST['apkId']) ? $_POST['apkId'] : ''));
            $isTest = Check::checkInteger(trim(isset($_POST['isTest']) ? $_POST['isTest'] : 0)); //1是测试 0是正常 默认是0
            $row = Check::checkInteger(trim(isset($_POST['row']) ? $_POST['row'] : 15));
            $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
            if ($row == '' || $currentPage == '' || $apkId == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }
            $CDBApk = new CDBApk();
            if ($isTest == 0) {
                $result = $CDBApk->getApkListPageV2($apkId, $row, $currentPage);
            }
            if ($isTest == 1) {
                $result = $CDBApk->getApkListTestPage($apkId, $row, $currentPage);
            }
            $data['apkList'] = $result;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }

    /**
     * 把apk名字添加进数据库
     */
    public function insertApkName()
    {
        if (Request::instance()->isPost()) {
            $name = Check::check(trim(isset($_POST['name']) ? $_POST['name'] : ''));
            if ($name == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }
            $CDBApk = new CDBApk();
            $count = $CDBApk->apkNameInsert($name);
            if (!$count) {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据添加出现错误');
                exit;
            }
            $data['insertCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }

    /**
     * 更新apk名字
     */
    public function updateApkName()
    {
        if (Request::instance()->isPost()) {
            $apkId = Check::checkInteger(trim(isset($_POST['apkId']) ? $_POST['apkId'] : ''));
            $name = Check::check(trim(isset($_POST['name']) ? $_POST['name'] : ''));
            if ($name == '' || $apkId == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }
            $CDBApk = new CDBApk();
            $count = $CDBApk->apkNameUpdate($apkId, $name);
            if (!$count) {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据更新出现错误');
                exit;
            }
            $data['updateCount'] = $count;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }

    /**
     * 获取apk名字列表
     */
    public function showApkNameList()
    {
        $CDBApk = new CDBApk();
        $result = $CDBApk->getApkNameList();
        $data['apkNameList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 撤销app
     */
    public function rescindApk()
    {
        $uploadApkId = Check::checkInteger(trim(isset($_POST['uploadApkId']) ? $_POST['uploadApkId'] : '')); //apk_id
        $isTest = Check::checkInteger(trim(isset($_POST['isTest']) ? $_POST['isTest'] : 0)); //1是测试 0是正常 默认是0
        $apkDB = new CDBApk();
        if ($isTest == 1) {
            $delRow = $apkDB->rescindApkTest($uploadApkId);
        }
        if ($isTest == 0) {
            $delRow = $apkDB->rescindApk($uploadApkId);
        }
        $data['delRow'] = $delRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}