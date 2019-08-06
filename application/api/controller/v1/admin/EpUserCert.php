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

        $res = $epUserCertModel->getDetail($certId);
        if ($res == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }
        $detail = $res->toArray();
        $epUserId = $detail['userId'];
        $realname = $detail['realname'];
        $phone = $detail['realphone'];
        $idCard = $detail['idCard'];
        $idCardFrontPic = $detail['idCardFrontPic'];
        $idCardBackPic = $detail['idCardBackPic'];
        $companyName = $detail['companyName'];
        $companyAddr = $detail['companyAddr'];
        $businessLic = $detail['businessLic'];
        $otherQuaLic = $detail['otherQuaLic'];
        $type = $detail['type'];

        $updateRow = $epUserCertModel->updateCertStatus($certId, $pass, $epUserId, $realname, $phone, $idCard, $idCardFrontPic, $idCardBackPic,
            $companyName, $companyAddr, $businessLic, $otherQuaLic, $type);

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