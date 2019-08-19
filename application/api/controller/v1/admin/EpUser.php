<?php

namespace app\api\controller\v1\admin;

use app\api\model\EpUserModel;
use Util\Util;

class EpUser extends AdminBase
{
    public function getEpUserList()
    {
        $epUserModel = new EpUserModel();
        $list = $epUserModel->getUserList();
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}