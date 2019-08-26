<?php

namespace app\api\controller\v1\ep;

use app\api\model\DataResume;
use app\api\model\EpResumeCateModel;
use app\api\model\EpResumeModel;
use app\api\model\ResumeModel;
use think\Request;
use Util\Check;
use Util\Util;

class ResumeData extends EpUserBase
{
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

        if ($workExp != '' && $workExp != '不限') {
            $workExpSql = " and  workYear='$workExp'";
        } else {
            $workExpSql = "";
        }

        //限 高中及以下 大专 本科及以上

        if ($educationName != '' && $educationName != '不限') {

            $educationNameArr = explode(',', $educationName);

            $educationNameSql = "  and  (educationName like '`' ";
            foreach ($educationNameArr as $k => $v) {
                if ($v == '不限') {
                    $educationNameSql .= "";
                } else if ($v == '高中及以下') {
                    $educationNameSql .= "  or educationName like '%高中%' or educationName like '%初中%' ";
                } else if ($v == '大专') {
                    $educationNameSql .= "  or educationName like '%大专%'";
                } else if ($v == '本科及以上') {
                    $educationNameSql .= "  or educationName like '%本科%' or educationName like '%硕士%' or educationName like '%博士%'";
                } else {
                    $educationNameSql .= "";
                }
            }

            $educationNameSql = $educationNameSql . ')';

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

    /**
     * 创建简历分类
     */
    public function addResumeCate()
    {
        $params = Request::instance()->param();
        $name = Check::check($params['name'] ?? '');

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

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

    /**
     * 编辑简历分类
     */
    public function editResumeCate()
    {

        $params = Request::instance()->param();
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? ''); //简历分类id
        $name = Check::check($params['name'] ?? '');

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $userId = $GLOBALS['userId'];
        $epResumeCateModel = new EpResumeCateModel();
        $arr = [
            'id' => $resumeCateId,
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

    /**
     * 删除简历分类
     */
    public function delResumeCate()
    {
        $params = Request::instance()->param();
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
     * 下载简历  下载简历来源source是2  申请简历source来源是1
     */
    public function downLoadResume()
    {
        $params = Request::instance()->param();
        $idCard = Check::check($params['idCard'] ?? ''); //身份证号
        $phone = Check::check($params['phone'] ?? ''); //手机号
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? 0); //简历分组id
        $userId = $GLOBALS['userId'];

        $epResumeModel = new EpResumeModel();
        if ($epResumeModel->resumeExistsSource2($userId, $idCard, $phone)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '简历已经下载过了');
            exit;
        }

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
            'resumeCateId' => $resumeCateId,
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
     * 批量下载简历
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function downLoadMultiResume()
    {
//        [{"idCard":0,"phone":123222222},{"idCard":0,"phone":123222222}]
        $params = Request::instance()->param();
        $json = $params['resumeJson'] ?? '';
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? 0);

        $userId = $GLOBALS['userId'];

        if ($json == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $jsonArr = json_decode($json, true, JSON_UNESCAPED_UNICODE);

        $epResumeModel = new EpResumeModel();

        $date = date('Y-m-d', time());
        $count = $epResumeModel->getDownloadNumOneDay($date, $userId);
        if ($count > 100) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '一天下载数量超出限制100');
            exit;
        }

        $epResumeModel->startTrans();
        $all = 0;
        foreach ($jsonArr as $k => $v) {
            $idCard = $v['idCard'];
            $phone = $v['phone'];
            if ($epResumeModel->resumeExistsSource2($userId, $idCard, $phone)) {
                continue;
            }
            $arr = [
                'userId' => $userId,
                'resumeId' => 0,
                'idCard' => $idCard,
                'phone' => $phone,
                'resumeCateId' => $resumeCateId,
                'source' => 2,
                'createTime' => currentTime(),
                'createBy' => $userId,
                'updateTime' => currentTime(),
                'updateBy' => $userId
            ];
            $insertRow = $epResumeModel->save($arr);
            $all += $insertRow;
            if ($insertRow == 0) {
                $epResumeModel->rollback();
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '下载失败');
                exit;
            }
        }
        $epResumeModel->commit();

        $data['insertRow'] = $all;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    /**
     * 获取企业用户的简历列表
     */
    public function getEpResumeList()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $userId = $GLOBALS['userId'];
        $epResumeModel = new EpResumeModel();

        $page = $epResumeModel->getListByUserIdPage($userId, $pageIndex, $pageSize);
        $pageData = $page->toArray();

        $pageData = $pageData['data'];

        $resumeModel = new ResumeModel();
        $resumeData = new DataResume();
        $total = $page->total();

        $list = array();
        foreach ($pageData as $k => $v) {
            $source = $v['source'];

            if ($source == 1) {
                $resumeId = $v['resumeId'];
                $xDetail = $resumeModel->getDetailForShow($resumeId);
                $xData = $xDetail->toArray();
                $xData['birthYear'] = '';
                $xData['habitation'] = '';
                $xData['source'] = 1;
                array_push($list, $xData);
            }

            if ($source == 2) {
                $xxDetail = $detail = $resumeData->detailForShowPage($v['idCard'], $v['phone']);
                $xxData = $xxDetail->toArray();
                $xxData['age'] = '';
                $xxData['curStatus'] = '';
                $xxData['source'] = 2;
                array_push($list, $xxData);
            }

        }
        $x['pageIndex'] = $pageIndex;
        $x['pageSize'] = $pageSize;
        $x['total'] = $total;
        $x['data'] = $list;

        $data['page'] = $x;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 简历修改分组
     */
    public function moveResumeToCate()
    {
        $params = Request::instance()->param();
        $epResumeRecordId = Check::checkInteger($params['epResumeRecordId'] ?? ''); //企业简历的记录id
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? ''); //简历分类id
        $userId = $GLOBALS['userId'];

        $arr = [
            'id' => $epResumeRecordId,
            'resumeCateId' => $resumeCateId,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $epResumeCateModel = new EpResumeModel();
        $updateRow = $epResumeCateModel->isUpdate(true)->save($arr);
        if ($updateRow > 0) {
            $data['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '更新失败');
            exit;
        }
    }

    /**
     * 根据分类获取用户的简历列表
     * @throws \think\Exception
     */
    public function getEpResumeListByCate()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);
        $resumeCateId = Check::checkInteger($params['resumeCateId'] ?? ''); //简历分类id

        $userId = $GLOBALS['userId'];
        $epResumeModel = new EpResumeModel();

        list($count, $page) = $epResumeModel->getListByUserIdPageWithCate($userId, $resumeCateId, $pageIndex, $pageSize);

        $resumeModel = new ResumeModel();
        $resumeData = new DataResume();
        $total = $page->total();
        $pageData = $page->getCollection();

        $list = array();
        foreach ($pageData as $k => $v) {
            $source = $v->source;
            if ($source == 1) {
                $resumeId = $v->resumeId;
                $xDetail = $resumeModel->getDetailForShow($resumeId);
                $xData = $xDetail->toArray();
                $xData['source'] = 1;
                $xData['epResumeRecordId'] = $v->id;
                array_push($list, $xData);
            }

            if ($source == 2) {
                $xxDetail = $detail = $resumeData->detailForShowPage($v['idCard'], $v['phone']);
                $xxData = $xxDetail->toArray();
                $xxData['source'] = 2;
                $xxData['epResumeRecordId'] = $v->id;
                array_push($list, $xxData);
            }
        }
        $x['pageIndex'] = $pageIndex;
        $x['pageSize'] = $pageSize;
        $x['total'] = $total;
        $x['resumeCount'] = $count;
        $x['data'] = $list;

        $data['page'] = $x;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    public function getEpApplyResumeDetail()
    {
        $params = Request::instance()->param();
        $resumeId = Check::checkInteger($params['resumeId'] ?? '');
        $resumeModel = new ResumeModel();
        $detail = $resumeModel->getDetail($resumeId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    public function getEpDownloadResumeDetail()
    {
        $params = Request::instance()->param();
        $idCard = Check::checkInteger($params['idCard'] ?? '');
        $phone = Check::check($params['phone'] ?? '');

        $resumeData = new DataResume();
        $detail = $resumeData->detail($idCard, $phone);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getEpResumeDetail()
    {
        $params = Request::instance()->param();

        $source = Check::checkInteger($params['source'] ?? '');

        if ($source == 1) {
            $resumeId = Check::checkInteger($params['resumeId'] ?? '');
            $resumeModel = new ResumeModel();
            $detail = $resumeModel->getDetail($resumeId);
        }
        if ($source == 2) {
            $idCard = Check::checkInteger($params['idCard'] ?? '');
            $phone = Check::check($params['phone'] ?? '');

            $resumeData = new DataResume();
            $detail = $resumeData->detail($idCard, $phone);
        }

        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}