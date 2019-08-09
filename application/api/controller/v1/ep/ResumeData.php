<?php

namespace app\api\controller\v1\ep;

use app\api\model\DataResume;
use app\api\model\EpResumeCateModel;
use app\api\model\EpResumeModel;
use think\Request;
use Util\Check;
use Util\Util;

class ResumeData extends EpUserBase
{
    public function filterResumeData()
    {
        ini_set('max_execution_time', 0);
        $params = Request::instance()->request();
        $posKey = Check::check($params['posKey'] ?? ''); //职位关键词
        $exWorkLocation = Check::check($params['exWorkLocation'] ?? '');//期望工作地点
        $workExp = Check::check($params['workExp'] ?? ''); //工作经验
        $educationName = Check::check($params['educationName'] ?? '');//学历
        $minAge = Check::check($params['minAge'] ?? 0);//最小年龄
        $maxAge = Check::check($params['maxAge'] ?? 0); //最大年龄
        $sex = Check::check($params['sex'] ?? ''); //性别 1男 0女 -1 未知

        if ($posKey != '') {
            $posKeySql = "  and  exPosition  like  '%$posKey%'";
        } else {
            $posKeySql = '';
        }

        if ($exWorkLocation != '') {
            $exWorkLocationSql = " and  (exCity like '%$exWorkLocation%' or habitation like '%$exWorkLocation%')";
        } else {
            $exWorkLocationSql = "";
        }

//        1-3年 3-5年 5-10年 10年以上

//        if ($workExp == '不限'){
//            $workExpSql = "";
//        }else if ($workExp == '1-3年'){
//
//        }
        if ($workExp != '' && $workExp != '不限') {
            $workExpSql = " and  workYear='$workExp'";
        } else {
            $workExpSql = "";
        }
//        if ($educationName != '' && $educationName != '不限') {
//            $educationNameSql = "  and educationName = '$educationName'";
//        } else {
//            $educationNameSql = "";
//        }
        //限 高中及以下 大专 本科及以上

        if ($educationName == '不限') {
            $educationNameSql = "";
        } else if ($educationName == '高中及以下') {
            $educationNameSql = "  and (educationName like '%高中%' or educationName like '%初中%')";
        } else if ($educationName == '大专') {
            $educationNameSql = "  and educationName like '%大专%'";
        } else if ($educationName == '本科及以上') {
            $educationNameSql = "  and (educationName like '%本科%' or educationName like '%硕士%' or educationName like '%博士%')";
        } else {
            $educationNameSql = "";
        }

        if ($minAge != 0) {
            $year = date('Y', time());
            $birthYear = $year - $minAge;
            $minAgeSql = "  and birthYear <= $birthYear";
        } else {
            $minAgeSql = "";
        }

        if ($maxAge != 0) {
            $year = date('Y', time());
            $birthYear = $year - $maxAge;
            $maxAgeSql = "  and birthYear >= $birthYear";
        } else {
            $maxAgeSql = "";
        }

        if ($sex != '' && $sex != '不限') {
            if ($sex == '男') {
                $sex = 1;
            }
            if ($sex == '女') {
                $sex = 0;
            }
            $sexSql = " and sex = $sex";
        } else {
            $sexSql = "";
        }

        $pageIndex = $params['pageIndex'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;
        $dataResumeModel = new DataResume();
        $content = $dataResumeModel->filterByPageWithEp($posKeySql, $exWorkLocationSql, $workExpSql, $educationNameSql, $minAgeSql, $maxAgeSql, $sexSql, $pageIndex, $pageSize);
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;

        $data['total'] = $dataResumeModel->filterCountWithEp($posKeySql, $exWorkLocationSql, $workExpSql, $educationNameSql, $minAgeSql, $maxAgeSql, $sexSql);
        $data['page'] = $content;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function addResumeCate()
    {
        $params = Request::instance()->request();
        $name = Check::check($params['name'] ?? '');

        $userId = $GLOBALS['userId'];
        $epResumeCateModel = new EpResumeCateModel();
        $arr = [
            'name' => $name,
            'userId' => $userId,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $epResumeCateModel->save($arr);
        if ($insertRow > 0) {
            $data['insertRow'] = $insertRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    public function editResumeCate()
    {

        $params = Request::instance()->request();
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? ''); //简历分类id
        $name = Check::check($params['name'] ?? '');

        $userId = $GLOBALS['userId'];
        $epResumeCateModel = new EpResumeCateModel();
        $arr = [
            'id' => $resumeCateId,
            'userId' => $userId,
            'name' => $name,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $epResumeCateModel->isUpdate(true)->save($arr);
        if ($updateRow > 0) {
            $data['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
            exit;
        }
    }

    public function delResumeCate()
    {
        $params = Request::instance()->request();
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? ''); //简历分类id

        $epResumeCateModel = new EpResumeCateModel();
        $arr = [
            'id' => $resumeCateId,
            'isDelete' => 1,
        ];

        $delRow = $epResumeCateModel->isUpdate(true)->save($arr);
        if ($delRow > 0) {
            $data['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }
    }

    /**
     * 获取简历分类列表
     */
    public function getResumeCateList()
    {
        $userId = $GLOBALS['userId'];
        $epResumeCateModel = new EpResumeCateModel();

        $list = $epResumeCateModel->getResumeCateListByUserId($userId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 移动简历到简历分类中
     */
    public function moveResumeToCate()
    {
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? ''); //简历分类id
        $userId = $GLOBALS['userId'];

        $arr = [
            'resumeId' => $resumeId,
            'resumeCateId' => $resumeCateId,
            'userId' => $userId,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $epResumeCateModel = new EpResumeCateModel();
        $insertRow = $epResumeCateModel->save($arr);
        if ($insertRow > 0) {
            $data['insertRow'] = $insertRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    public function downLoadResume111()
    {
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $userId = $GLOBALS['userId'];

        $epResumeModel = new EpResumeModel();
        $date = date('Y-m-d', time());
        $count = $epResumeModel->getDownloadNumOneDay($date, $userId);
        if ($count > 100) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '一天下载数量超出限制100');
            exit;
        }
        $arr = [
            'userId' => $userId,
            'resumeId' => $resumeId,
            'source' => 2,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $epResumeModel->save($arr);

        if ($insertRow > 0) {
            $data['insertRow'] = $insertRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '下载失败');
            exit;
        }

    }


    public function downLoadResume()
    {
        $params = Request::instance()->request();
        $idCard = Check::check($params['idCard'] ?? ''); //身份证号
        $phone = Check::check($params['phone'] ?? ''); //手机号
        $userId = $GLOBALS['userId'];

        $epResumeModel = new EpResumeModel();
        $date = date('Y-m-d', time());
        $count = $epResumeModel->getDownloadNumOneDay($date, $userId);
        if ($count > 100) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '一天下载数量超出限制100');
            exit;
        }
        $arr = [
            'userId' => $userId,
            'resumeId' => 0,
            'idCard' => $idCard,
            'phone' => $phone,
            'source' => 2,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $epResumeModel->save($arr);

        if ($insertRow > 0) {
            $data['insertRow'] = $insertRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '下载失败');
            exit;
        }

    }

    /**
     * 获取企业用户的简历列表
     */
    public function getDownloadResumeList()
    {
        $userId = $GLOBALS['userId'];
        $epResumeModel = new EpResumeModel();
        $list = $epResumeModel->getListByUserId($userId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}