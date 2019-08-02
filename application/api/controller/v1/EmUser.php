<?php

namespace app\api\controller\v1;

use app\api\model\EpOrderModel;
use think\Request;
use Util\Check;
use Util\Util;

class EmUser extends EpUserBase
{
    /**
     * 接单
     */
    public function receiveOrder()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $emUserId = $GLOBALS['userId'];

        $epOrderModel = new EpOrderModel();
        $arr = [
            'orderId'=>get_order_sn(),
            'emUserId'=>$emUserId,
            'positionId'=>$positionId,
            'createBy'=>$emUserId,
            'createTime'=>currentTime(),
            'updateBy'=>$emUserId,
            'updateTime'=>currentTime()
        ];

        $recId = $epOrderModel->add($arr);
        $data['recId'] = $recId;
        Util::printResult($GLOBALS['ERROR_SUCCESS'],$data);
    }


}