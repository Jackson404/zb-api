<?php

namespace app\api\controller\v1\ep;

use app\api\model\EpMsgModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpMsg extends EpUserBase
{
    public function getList()
    {
//        $params = Request::instance()->param();
        $userId = $GLOBALS['userId'];
        $epMsgModel = new EpMsgModel();

        $list = $epMsgModel->getListByUserId($userId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getDetail()
    {
        $params = Request::instance()->param();
        $msgId = Check::checkInteger($params['msgId'] ?? '');
        $epMsgModel = new EpMsgModel();
        $detail = $epMsgModel->getDetail($msgId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}