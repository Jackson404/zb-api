<?php

namespace app\api\controller\v1\ep;

use app\api\model\EpOrderModel;
use app\api\model\PositionManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpOrder extends EpUserBase
{
    /**
     * 筛选职位
     */
    public function filter()
    {
        $params = Request::instance()->request();
        $areaInfo = Check::check($params['areaInfo'] ?? '');  //区域信息
        $priceOrder = Check::checkInteger($params['priceOrder'] ?? 1); //价格高低  1高-低   0低-高
        $keywords = Check::check($params['keywords'] ?? ''); //搜索关键词

        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        if ($areaInfo != '') {
            $areaInfoSql = "  and  (zco.province like '%$areaInfo%' or zco.city like '%$areaInfo%' or zco.area like '%$areaInfo%' or  zco.address like '%$areaInfo%')";
        } else {
            $areaInfoSql = "";
        }

        if ($priceOrder == 1) {
            $priceOrderSql = "  order by p.unitPrice desc";
        } else if ($priceOrder == 0) {
            $priceOrderSql = "  order by p.unitPrice asc";
        } else {
            $priceOrderSql = "";
        }

        if ($keywords != '') {
            $keywordsSql = "  and  (p.name like '%$keywords%'  or  zco.name  like '%$keywords%')";
        } else {
            $keywordsSql = "";
        }

        $positionModel = new PositionManagementModel();
        list($result, $total) = $positionModel->filterOrder($areaInfoSql, $priceOrderSql, $keywordsSql, $pageIndex, $pageSize);

        foreach ($result as $k => $v) {
            // $result[$k]['createDate'] = date('Y-m-d',strtotime($v['createTime']));
            // $result[$k]['updateDate'] = date('Y-m-d',strtotime($v['updateTime']));
            $result[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $page['pageIndex'] = $pageIndex;
        $page['pageSize'] = $pageSize;
        $page['total'] = $total;
        $page['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $page);
    }

    /**
     * 获取发布的职位订单详情 需登陆
     * @todo 检查用户是否接过该订单
     * hasRecOrder 1 接过单  0未接单
     */
    public function getDetailWithLogin()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $epOrderModel = new EpOrderModel();
        $userId = $GLOBALS['userId'];
        if ($epOrderModel->checkUserRecOrder($positionId, $userId)) {
            $data['hasRecOrder'] = 1;
        } else {
            $data['hasRecOrder'] = 0;
        }

        $positionManagementModel = new PositionManagementModel();
        $detail = $positionManagementModel->getDetail($positionId);
        $randomList = $positionManagementModel->getRandomPositionOrderListLimit($positionId, 2);
        $detail['labelIds'] = json_decode($detail['labelIds'], true);
        $data['detail'] = $detail;
        $data['randomList'] = $randomList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取发布的职位订单详情 不需要登录 扫描二维码的时候使用
     * @throws \think\Exception
     */
    public function getDetailNoLogin()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id

        $positionManagementModel = new PositionManagementModel();
        $detail = $positionManagementModel->getDetail($positionId);
//        $randomList = $positionManagementModel->getRandomPositionOrderListLimit($positionId, 2);
        $detail['labelIds'] = json_decode($detail['labelIds'], true);
        $data['detail'] = $detail;
//        $data['randomList'] = $randomList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 扫描二维码，报名申请这个职位
     */
//    public function apply()
//    {
//        $params = Request::instance()->request();
//
//        $orderId = Check::check($params['orderId'] ?? ''); //订单id
//        $shareUserId = Check::checkInteger($params['shareUserId'] ?? ''); //分享人的用户id
//        $name = Check::check($params['name'] ?? ''); //申请人的姓名
//        $phone = Check::check($params['phone'] ?? '', 11, 11);
//        $gender = Check::checkInteger($params['gender'] ?? 0);// 0 未知 1男 2 女
//        $age = Check::checkInteger($params['age'] ?? 0);
//        $workYear = Check::checkInteger($params['workYear'] ?? 0); //工作年限
//        $education = Check::check($params['education'] ?? ''); //学历
//        $skills = Check::check($params['skills'] ?? ''); //技能描述
//        $selfEvaluation = Check::check($params['selfEvaluation'] ?? ''); //自我评价
//        $militaryTime = Check::check($params['militaryTime'] ?? ''); //入伍时间
//        $attendedTime = Check::check($params['attendedTime'] ?? 0); //服役时长
//        $corps = Check::check($params['corps'] ?? ''); //兵种
//        $exPosition = Check::check($params['exPosition'] ?? ''); //期望职位
//        $nature = Check::check($params['nature'] ?? ''); //期望工作性质
//        $exCity = Check::check($params['exCity'] ?? ''); //期望城市
//        $salary = Check::check($params['salary'] ?? ''); //期望薪资
//        $curStatus = Check::check($params['curStatus'] ?? ''); //目前状态
//        $arrivalTime = Check::check($params['arrivalTime'] ?? ''); //到岗时间
//        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0); //是否是退役军人 默认0 0否 1是
//
//        $data = [
//            'orderId' => $orderId,
//            'shareUserId' => $shareUserId,
//            'name' => $name,
//            'phone' => $phone,
//            'gender' => $gender,
//            'age' => $age,
//            'workYear' => $workYear,
//            'education' => $education,
//            'salary' => $salary,
//            'skills' => $skills,
//            'selfEvaluation' => $selfEvaluation,
//            'militaryTime' => $militaryTime,
//            'attendedTime' => $attendedTime,
//            'corps' => $corps,
//            'exPosition' => $exPosition,
//            'nature' => $nature,
//            'exCity' => $exCity,
//            'curStatus' => $curStatus,
//            'arrivalTime' => $arrivalTime,
//            'isSoldierPriority' => $isSoldierPriority,
//            'createTime' => currentTime(),
//            'updateTime' => currentTime()
//        ];
//
//
//    }
}