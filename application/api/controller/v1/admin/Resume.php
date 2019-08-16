<?php

namespace app\api\controller\v1\admin;

use app\api\model\ResumeModel;
use app\api\model\UserApplyPositionModel;
use think\Request;
use Check;
use Util;

class Resume extends AdminBase
{

    /**
     * 分页获取简历 (admin use)
     */
    public function getByPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $resumeModel = new ResumeModel();
        $page = $resumeModel->getByPage($pageIndex, $pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取简历详情
     */
    public function getDetail()
    {
        $params = Request::instance()->param();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); // 简历id
        $resumeModel = new ResumeModel();
        $detail = $resumeModel->getDetail($resumeId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取简历申请的职位记录
     */
    public function getResumeApplyList()
    {

        $params = Request::instance()->param();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $userApplyPositionModel = new UserApplyPositionModel();
        $list = $userApplyPositionModel->getResumeApplyList($resumeId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页获取简历申请的职位记录
     */
    public function getResumeApplyPage()
    {
        $params = Request::instance()->param();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $userApplyPositionModel = new UserApplyPositionModel();
        $page = $userApplyPositionModel->getResumeApplyPage($resumeId,$pageIndex,$pageSize);
        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页获取职位下的简历列表
     */
    public function getResumePageByPositionId(){
        $params = Request::instance()->param();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $userApplyPositionModel = new UserApplyPositionModel();
        $page = $userApplyPositionModel->getResumePageByPositionId($positionId,$pageIndex,$pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}