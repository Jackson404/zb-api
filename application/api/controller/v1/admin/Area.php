<?php

namespace app\api\controller\v1\admin;

use app\api\model\AreaModel;
use think\Controller;
use think\Request;
use Util\Check;
use Util\Util;

class Area extends Controller
{
    public function getProvince()
    {
        $areaModel = new AreaModel();
        $list = $areaModel->getProvince();
        $list = array_column($list, 'province');
        $data['province'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getCity()
    {

        $params = Request::instance()->request();
        $province = Check::check($params['province'] ?? '');

        $areaModel = new AreaModel();
        $list = $areaModel->getCity($province);
        $list = array_column($list, 'city');
        $data['city'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getArea()
    {

        $params = Request::instance()->request();
        $city = Check::check($params['city'] ?? '');

        $areaModel = new AreaModel();
        $list = $areaModel->getArea($city);
        $list = array_column($list, 'area');
        $data['area'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function filterAreaInfo()
    {
        $params = Request::instance()->request();
        $info = Check::check($params['info'] ?? '');

        $areaModel = new AreaModel();
        list($res1, $res2, $res3) = $areaModel->areaInfo();
        $r1 = array_column($res1, 'province');
        $r2 = array_column($res2, 'city');
        $r3 = array_column($res3, 'area');

        $r = array_merge($r1, $r2, $r3);

        if ($info == '') {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $r);
            exit;
        } else {
            $input = preg_quote($info, '~'); // don't forget to quote input string!
            $result = preg_grep('~' . $input . '~', $r);
            $result = array_values($result);
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $result);
        }
    }


}