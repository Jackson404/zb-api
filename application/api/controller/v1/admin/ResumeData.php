<?php

namespace app\api\controller\v1\admin;

use app\api\model\DataResume;
use app\api\model\DataResumeRecord;
use think\cache\driver\Redis;
use think\Controller;
use think\Request;
use Util\Check;
use Util\Util;

class ResumeData extends Controller
{
    public function getByPage()
    {
        ini_set('max_execution_time', 0);
        $params = Request::instance()->param();

        $pageIndex = $params['pageIndex'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;
        $dataResumeModel = new DataResume();
        $content = $dataResumeModel->getByPage($pageIndex, $pageSize);
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;

        $data['total'] = $dataResumeModel->getCount();
        $data['page'] = $content;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    public function filterResumeData()
    {
        ini_set('max_execution_time', 0);
        $params = Request::instance()->param();
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
        $content = $dataResumeModel->filterByPage($posKeySql, $exWorkLocationSql, $workExpSql, $educationNameSql, $minAgeSql, $maxAgeSql, $sexSql, $pageIndex, $pageSize);
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;

        $data['total'] = $dataResumeModel->filterCount($posKeySql, $exWorkLocationSql, $workExpSql, $educationNameSql, $minAgeSql, $maxAgeSql, $sexSql);
        $data['page'] = $content;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    public function addRecord()
    {
        $params = Request::instance()->param();
        $recordName = $params['recordName'] ?? '';
        $remark = $params['remark'] ?? '';
        $posKey = $params['posKey'] ?? '';
        $exWorkLocation = $params['exWorkLocation'] ?? '';
        $workExp = Check::check($params['workExp'] ?? ''); //工作经验
        $educationName = Check::check($params['educationName'] ?? '');//学历
        $minAge = Check::check($params['minAge'] ?? '');//最小年龄
        $maxAge = Check::check($params['maxAge'] ?? ''); //最大年龄
        $sex = Check::check($params['sex'] ?? ''); //性别 1男 0女 -1 未知
//        $filterData = $params['filterData'] ?? '';

        if ($recordName == '' || $remark == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $data = [
            'recordName' => $recordName,
            'remark' => $remark,
            'posKey' => $posKey,
            'exWorkLocation' => $exWorkLocation,
            'workExp' => $workExp,
            'educationName' => $educationName,
            'minAge' => $minAge,
            'maxAge' => $maxAge,
            'sex' => $sex,
            'createTime' => date('Y-m-d', time()),
            'updateTime' => date('Y-m-d', time())
        ];

//        $filterData = json_decode($filterData, true);

        $dataResumeRecord = new DataResumeRecord();
//        $recordId = $dataResumeRecord->addRecord($data, $filterData);
        $recordId = $dataResumeRecord->addRecordX($data);

        $arr['recordId'] = $recordId;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);

    }

    public function updateRecord()
    {
        $params = Request::instance()->param();
        $recordId = $params['recordId'] ?? '';
        $recordName = $params['recordName'] ?? '';
        $remark = $params['remark'] ?? '';
        $posKey = $params['posKey'] ?? '';
        $exWorkLocation = $params['exWorkLocation'] ?? '';
        $workExp = Check::check($params['workExp'] ?? ''); //工作经验
        $educationName = Check::check($params['educationName'] ?? '');//学历
        $minAge = Check::check($params['minAge'] ?? '');//最小年龄
        $maxAge = Check::check($params['maxAge'] ?? ''); //最大年龄
        $sex = Check::check($params['sex'] ?? ''); //性别 1男 0女 -1 未知

        if ($recordName == '' || $remark == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $data = [
            'recordName' => $recordName,
            'remark' => $remark,
            'posKey' => $posKey,
            'exWorkLocation' => $exWorkLocation,
            'workExp' => $workExp,
            'educationName' => $educationName,
            'minAge' => $minAge,
            'maxAge' => $maxAge,
            'sex' => $sex,
            'updateTime' => date('Y-m-d', time())
        ];

        $dataResumeRecord = new DataResumeRecord();
        $updateRow = $dataResumeRecord->updateRecord($recordId, $data);

        $arr['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);

    }


    public function getRecordByDate()
    {
        $params = Request::instance()->param();
        $date = $params['date'] ?? '';
        $dataResumeRecord = new DataResumeRecord();
        $list = $dataResumeRecord->getRecordListByDate($date);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    public function addStar()
    {
        $params = Request::instance()->param();
        $idCard = $params['idCard'] ?? '';
        $phone = $params['phone'] ?? '';
        $type = $params['type'] ?? 1;

        $dataResumeModel = new DataResume();
        $updateRow = $dataResumeModel->star($idCard, $phone, $type);
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function edit()
    {
        $params = Request::instance()->param();
        $idCard = $params['idCard'] ?? '';
        $phone = $params['phone'] ?? '';
        $name = $params['name'] ?? '';
        $sex = $params['sex'] ?? '';
        $birthYear = $params['birthYear'] ?? '';
        $birth = $params['birth'] ?? '';
        $school = $params['school'] ?? '';
        $education = $params['education'] ?? 0;
        $educationName = $params['educationName'] ?? '';
        $mail = $params['mail'] ?? '';
        $profession = $params['profession'] ?? '';
        $professionId = $params['professionId'] ?? 0;
        $workYear = $params['workYear'] ?? '';
        $exPosition = $params['exPosition'] ?? '';
        $exSalary = $params['exSalary'] ?? '';
        $exCity = $params['exCity'] ?? '';
        $habitation = $params['habitation'] ?? '';
        $houseLocation = $params['houseLocation'] ?? '';
        $workUnit = $params['workUnit'] ?? '';

        $data = [
            'idCard' => $idCard,
            'phone' => $phone,
            'name' => $name,
            'sex' => $sex,
            'birthYear' => $birthYear,
            'birth' => $birth,
            'school' => $school,
            'education' => $education,
            'educationName' => $educationName,
            'mail' => $mail,
            'profession' => $profession,
            'professionId' => $professionId,
            'workYear' => $workYear,
            'exPosition' => $exPosition,
            'exSalary' => $exSalary,
            'exCity' => $exCity,
            'habitation' => $habitation,
            'houseLocation' => $houseLocation,
            'workUnit' => $workUnit,
            'updateTime' => date('Y-m-d', time()),
            'type' => 2
        ];

        $dataResumeModel = new DataResume();
        $updateRow = $dataResumeModel->isUpdate(true)->save($data);
        $arr['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }


    /**
     * 真删除啊 对呀 真的删除了
     */
    public function delResume()
    {
        $params = Request::instance()->param();
        $idCard = $params['idCard'] ?? '';
        $phone = $params['phone'] ?? '';

        $dataResumeModel = new DataResume();
        $delRow = $dataResumeModel->del($idCard, $phone);
        $data['delRow'] = $delRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    public function getDetail()
    {
        $params = Request::instance()->param();
        $idCard = $params['idCard'] ?? '';
        $phone = $params['phone'] ?? '';

        $dataResumeModel = new DataResume();
        $detail = $dataResumeModel->detail($idCard, $phone);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}