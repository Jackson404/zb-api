<?php

namespace app\api\controller\v1\admin;

use app\api\model\EpUserCertModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpUserCert extends AdminBase
{
    public function review()
    {
        $params = Request::instance()->request();
        $certId = Check::checkInteger($params['certId'] ?? '');
        $pass = Check::checkInteger($params['pass'] ?? ''); // -1 不通过 1通过 0待审核

        $epUserCertModel = new EpUserCertModel();
        $detail = $epUserCertModel->getDetail($certId);
        if (empty($detail)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }
        $epUserId = $detail['userId'];
        $companyName = $detail['companyName'];
        $type = $detail['type'];

        $updateRow = $epUserCertModel->updateCertStatus($certId, $pass, $epUserId,$companyName,$type);
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