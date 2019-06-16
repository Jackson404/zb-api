<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 17:44
 */

namespace app\admin\controller;

use app\admin\db\CDBAccount;
use app\admin\db\CDBApk;
use app\admin\common\Check;
use app\admin\common\Upload;
use app\admin\common\Util;
use think\Controller;
use think\Db;
use think\Request;


class Apk extends Controller
{

    /**
     * apk上传
     */
    public function apkUpload()
    {
        $CDBAccount = new CDBAccount();
        if (!$CDBAccount->isLogin()) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录错误');
            exit;
        }
        if (Request::instance()->isPost()) {
            $version = Check::check(trim(isset($_POST['version']) ? $_POST['version'] : ''));
            $updateInfo = Check::check(trim(isset($_POST['updateInfo']) ? $_POST['updateInfo'] : ''));
            if (!array_key_exists('apk', $_FILES) || !$_FILES['apk']['tmp_name'] || $version == '' || $updateInfo == '') {
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
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            $result = DB::name('apk')->insert($data);
            if ($result > 0) {
                $data['count'] = $result;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '数据插入错误');
                exit;
            }
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }


    /**
     *  获取最新版本
     */
    public function getLatestVersion()
    {
        if (request()->isPost()) {
            $CDBApk = new CDBApk();
            $result = $CDBApk->showApkLatestVersion();
            if ($result) {
                $data = [
                    'apk_url' => $result['apk_url'],
                    'update_info' => $result['update_info'],
                    'version_number' => $result['version_number'],
                    'size' => $result['size'],
                    'create_time' => $result['create_time']
                ];
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            }
            Util::printResult($GLOBALS['ERROR_SQL_QUERY'], '查询错误');
            exit;

        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }


    /**
     * 分页获取apk列表
     */
    public function getApkListByPage()
    {
        if (request()->isPost()) {

            $row = Check::checkInteger(trim(isset($_POST['row']) ? $_POST['row'] : 15));
            $currentPage = Check::checkInteger(trim(isset($_POST['currentPage']) ? $_POST['currentPage'] : 1));
            if ($row == '' || $currentPage == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }
            $CDBApk = new CDBApk();
            $result = $CDBApk->getApkListPage($row, $currentPage);
            $data['apkList'] = $result;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_POST'], '不是POST传值');
            exit;
        }
    }


    /**
     * 上传多张图片
     */
    public function uploadImages()
    {
//        $CDBAccount = new CDBAccount();
//        if (!$CDBAccount->isLogin()) {
//            util::printResult($GLOBALS['ERROR_LOGIN'], '登录错误');
//            exit;
//        }

        if ($_FILES == []) {
            Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], '上传出错');
            exit;
        }
        $upload = new Upload();
        $size = isset($_POST['size']) ? $_POST['size'] : '';
        $ext = isset($_POST['ext']) ? $_POST['ext'] : '';
        $uploadImages = $upload->uploadImages($size, $ext);
        if ($uploadImages) {
            $data['imagesList'] = $uploadImages;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        }

    }
}