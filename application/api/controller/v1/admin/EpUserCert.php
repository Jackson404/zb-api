<?php

namespace app\api\controller\v1\admin;

use app\api\model\EpUserCertModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpUserCert extends AdminBase
{
    /**
     * 审核企业
     */
    public function review()
    {
        $params = Request::instance()->param();
        $certId = Check::checkInteger($params['certId'] ?? '');
        $pass = Check::checkInteger($params['pass'] ?? ''); // -1 不通过 1通过

        $adminUserId = $GLOBALS['userId'];
        $epUserCertModel = new EpUserCertModel();

        $res = $epUserCertModel->getDetail($certId);
        if ($res == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }
        $detail = $res->toArray();
        $userId = $detail['userId'];
        $companyName = $detail['companyName'];
        $companyAddr = $detail['companyAddr'];
        $type = $detail['type'];

        $updateRow = $epUserCertModel->reviewEpByAdmin($adminUserId, $certId, $pass, $userId,
            $companyName, $companyAddr, $type);

        if ($updateRow > 0) {
            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '更新失败');
            exit;
        }

    }
}