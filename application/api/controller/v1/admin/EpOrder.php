<?php

namespace app\api\controller\v1\admin;

use app\api\model\EpOrderModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpOrder extends AdminBase
{
    /**
     * 获取订单列表
     * @throws \think\exception\DbException
     */
    public function getOrderPage()
    {

        $params = Request::instance()->param();
        $orderDate = $params['orderDate'] ?? ''; //筛选订单时间
        $isFinish = $params['isFinish'] ?? 1; //默认已完成
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $orderModel = new EpOrderModel();

        $page = $orderModel->getOrderListByPageAdmin($isFinish, $pageIndex, $pageSize);
        $listData = $page->toArray();

        $newList = array();
        if ($orderDate != '') {
            foreach ($listData as $k => $v) {
                if ($v['orderDate'] == $orderDate) {
                    array_push($newList, $v);
                }
            }
        }
        $listData['data'] = $newList;
        $data['page'] = $listData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }
}