<?php

namespace app\api\controller\v1\admin;

use app\api\model\EpOrderApplyModel;
use app\api\model\EpOrderModel;
use app\api\model\PositionManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpOrderApply extends AdminBase
{
    public function getApplyListByOrderIdPage()
    {
        $params = Request::instance()->param();
        $orderId = $params['orderId'] ?? '';
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        if ($orderId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $epOrderApplyModel = new EpOrderApplyModel();
        $page = $epOrderApplyModel->getApplyListByOrderIdPage($orderId, $pageIndex, $pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 更新面试状态 1面试 0为面试
     */
    public function updateInterviewStatus()
    {
        $params = Request::instance()->param();
        $orderId = Check::check($params['orderId'] ?? '');
        $interviewStatus = Check::checkInteger($params['interviewStatus'] ?? 1);
        $epOrderApplyModel = new EpOrderApplyModel();
        $epOrderApplyModel->startTrans();
        $updateRow = $epOrderApplyModel->updateInterviewStatus($orderId, $interviewStatus);
        if ($updateRow == 0) {
            $epOrderApplyModel->rollback();
        }
        $epOrderModel = new EpOrderModel();
        if ($interviewStatus == 1) {
            $epOrderModel->incInterviewNum($orderId, 1);
        }
        if ($interviewStatus == 0) {
            $epOrderModel->decInterviewNum($orderId, 1);
        }

        $epOrderApplyModel->commit();
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 入职状态 1入职 0未入职
     */
    public function updateEntryStatus()
    {
        $params = Request::instance()->param();
        $orderId = Check::check($params['orderId'] ?? '');
        $entryStatus = Check::checkInteger($params['entryStatus'] ?? 1);

        $epOrderModel = new EpOrderModel();
        $orderDetail = $epOrderModel->getDetailByOrderId($orderId);
        if ($orderDetail == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '订单不存在');
            exit;
        }
        $orderDetailData = $orderDetail->toArray();
        $positionId = $orderDetailData['positionId'];
        $positionModel = new PositionManagementModel();
        $pDetail = $positionModel->getDetail($positionId);
        if ($pDetail == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '职位不存在');
            exit;
        }
        $pData = $pDetail->toArray();
        $unitPrice = $pData['unitPrice'];

        $epOrderApplyModel = new EpOrderApplyModel();
        $epOrderApplyModel->startTrans();
        $updateRow = $epOrderApplyModel->updateEntryStatus($orderId, $entryStatus);
        if ($updateRow == 0) {
            $epOrderApplyModel->rollback();
        }

        if ($entryStatus == 1) {
            $epOrderModel->incEntryNum($orderId, 1);
            $epOrderModel->incIncome($orderId, $unitPrice);
        }
        if ($entryStatus == 0) {
            $epOrderModel->decEntryNum($orderId, 1);
            $epOrderModel->decIncome($orderId, $unitPrice);
        }

        $epOrderApplyModel->commit();
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}