<?php

namespace app\api\controller\v1\ep;

use app\api\model\EpOrderApplyModel;
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
     * 获取已经完成/未完成的订单列表
     */
    public function getOrderList()
    {
        $params = Request::instance()->request();
        $orderDate = $params['orderDate'] ?? ''; //筛选订单时间
        $isFinish = $params['isFinish'] ?? 1; //默认已完成

        $userId = $GLOBALS['userId'];
        $orderModel = new EpOrderModel();

        $list = $orderModel->getOrderList($userId, $isFinish);
        $listData = $list->toArray();

        $newList = array();
        if ($orderDate != '') {
            foreach ($listData as $k => $v) {
                if ($v['orderDate'] == $orderDate) {
                    array_push($newList, $v);
                }
            }
        }
        $data['list'] = $newList;
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
        $params = Request::instance()->request();
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