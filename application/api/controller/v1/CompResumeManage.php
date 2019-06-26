<?php

namespace app\api\controller\v1;

use app\api\model\DataResume;
use think\Request;
use Util\Util;

class CompResumeManage extends AdminBase
{
    public function getResumeByPage()
    {
        $params = Request::instance()->request();

        $pageIndex = $params['pageIndex'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $dataResumeM = new DataResume();

        $page = $dataResumeM->getByPage($pageIndex, $pageSize);

        $data['page'] = $page;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);


    }
}