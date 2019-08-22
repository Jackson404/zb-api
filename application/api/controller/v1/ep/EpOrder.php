<?php

namespace app\api\controller\v1\ep;

use app\api\model\EpOrderApplyModel;
use app\api\model\EpOrderModel;
use app\api\model\EpUserModel;
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
        $params = Request::instance()->param();
        $areaInfo = Check::check($params['areaInfo'] ?? '');  //区域信息
        $priceOrder = Check::checkInteger($params['priceOrder'] ?? 1); //价格高低  1高-低   0低-高
        $keywords = Check::check($params['keywords'] ?? ''); //搜索关键词

        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);
        $userId = $GLOBALS['userId'];

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

        $epOrderModel = new EpOrderModel();

        foreach ($result as $k => $v) {

            if ($epOrderModel->checkUserRecOrder($v['id'], $userId)) {
                $result[$k]['hasRecOrder'] = 1;
            } else {
                $result[$k]['hasRecOrder'] = 0;
            }

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
        $params = Request::instance()->param();
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
     * 分享订单
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shareOrder()
    {
        $params = Request::instance()->param();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $epOrderModel = new EpOrderModel();
        $userId = $GLOBALS['userId'];
        $x = $epOrderModel->verifyUserRecOrderDetail($positionId, $userId);
        if ($x != null) {
            $xData = $x->toArray();
            $qrCode = $xData['qrCode'];
            $data['hasRecOrder'] = 1;
            $data['qrCode'] = $qrCode;
        } else {
            $data['hasRecOrder'] = 0;
        }

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取发布的职位订单详情 不需要登录 扫描二维码的时候使用
     * @throws \think\Exception
     */
    public function getDetailNoLogin()
    {
        $params = Request::instance()->param();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id

        $positionManagementModel = new PositionManagementModel();
        $detail = $positionManagementModel->getDetail($positionId);
        $detail['labelIds'] = json_decode($detail['labelIds'], true);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 获取已经完成/未完成的订单列表
     */
    public function getOrderList()
    {
        $params = Request::instance()->param();
        $orderDate = $params['orderDate'] ?? ''; //筛选订单时间 格式2019-08
        $isFinish = $params['isFinish'] ?? 1; //默认已完成

        $userId = $GLOBALS['userId'];

        if ($orderDate == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $epUserModel = new EpUserModel();
        $userInfo = $epUserModel->getUserInfo($userId);

        if ($userInfo == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'],'用户不存在');
            exit;
        }

        $userData = $userInfo->toArray();
        $userType = $userData['type'];
        $epId = $userData['epId'];

        $orderModel = new EpOrderModel();

        $x = explode('-', $orderDate);
        $recOrderYear = $x[0];
        $recOrderMonth = $x[1];

        if ($userType == 1) {
            $list = $orderModel->getOrderListWithOrderDateWithEpUser($epId, $isFinish, $recOrderYear, $recOrderMonth);
            list($entryNumMonth, $incomeMonth, $orderNumMonth, $incomeTotal, $orderNum) = $orderModel->getOrderInfoByMonthWithEpUser($recOrderYear, $recOrderMonth, $epId, $isFinish);

            $data['incomeTotal'] = $incomeTotal;
            $data['orderNum'] = $orderNum;
            $data['orderNumMonth'] = $orderNumMonth;
            $data['entryNumMonth'] = $entryNumMonth;
            $data['incomeMonth'] = $incomeMonth;
            $data['recOrderYear'] = $recOrderYear;
            $data['recOrderMonth'] = $recOrderMonth;

        } else if ($userType == 2) {

            $list = $orderModel->getOrderListWithOrderDateWithEmUser($userId, $isFinish, $recOrderYear, $recOrderMonth);

            list($entryNumMonth, $incomeMonth, $orderNumMonth, $incomeTotal, $orderNum) = $orderModel->getOrderInfoByMonthWithEmUser($recOrderYear, $recOrderMonth, $userId, $isFinish);

            $data['incomeTotal'] = $incomeTotal;
            $data['orderNum'] = $orderNum;
            $data['orderNumMonth'] = $orderNumMonth;
            $data['entryNumMonth'] = $entryNumMonth;
            $data['incomeMonth'] = $incomeMonth;
            $data['recOrderYear'] = $recOrderYear;
            $data['recOrderMonth'] = $recOrderMonth;
        } else {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户未认证');
            exit;
        }

        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    /**
     * 获取订单详情
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderDetail()
    {
        $params = Request::instance()->param();
        $orderId = Check::check($params['orderId'] ?? ''); //订单id
        $epOrderModel = new EpOrderModel();
        $detail = $epOrderModel->getDetailByOrderId($orderId);
        if ($detail == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }
        $data['detail'] = $detail;
        $epOrderApplyModel = new EpOrderApplyModel();
        $applyList = $epOrderApplyModel->getApplyListByOrderId($orderId);
        $data['applyList'] = $applyList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}