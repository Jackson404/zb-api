<?php

namespace app\api\controller\v1;

use app\api\model\PositionManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpOrder extends EpUserBase
{
    /**
     * 筛选职位
     */
    public function filter()
    {
        $params = Request::instance()->request();
        $areaInfo = Check::check($params['areaInfo'] ?? '');  //区域信息
        $priceOrder = Check::checkInteger($params['priceOrder'] ?? 1); //价格高低  1高-低   0低-高
        $keywords = Check::check($params['keywords'] ?? ''); //搜索关键词

        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        if ($areaInfo != '') {
            $areaInfoSql = "  and  (zco.province like '%$areaInfo%' or zco.city like '%$areaInfo%' or zco.area like '%$areaInfo%' or  zco.address like '%$areaInfo%')";
        } else {
            $areaInfoSql = "";
        }

        if ($priceOrder == 1) {
            $priceOrderSql = "  order by p.unitPrice desc";
        } else if ($priceOrder == 0) {
            $priceOrderSql = "  order by p.unitPrice asc";
        } else {
            $priceOrderSql = "";
        }

        if ($keywords != '') {
            $keywordsSql = "  and  (p.name like '%$keywords%'  or  zco.name  like '%$keywords%')";
        } else {
            $keywordsSql = "";
        }

        $positionModel = new PositionManagementModel();
        list($result, $total) = $positionModel->filterOrder($areaInfoSql, $priceOrderSql,$keywordsSql, $pageIndex, $pageSize);

        foreach ($result as $k => $v) {
           // $result[$k]['createDate'] = date('Y-m-d',strtotime($v['createTime']));
           // $result[$k]['updateDate'] = date('Y-m-d',strtotime($v['updateTime']));
            $result[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $page['pageIndex'] = $pageIndex;
        $page['pageSize'] = $pageSize;
        $page['total'] = $total;
        $page['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $page);
    }

    /**
     * 获取发布的职位订单详情
     */
    public function getDetail()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $positionManagementModel = new PositionManagementModel();
        $detail = $positionManagementModel->getDetail($positionId);
        $randomList = $positionManagementModel->getRandomPositionListLimit($positionId,2);
        $detail['labelIds'] = json_decode($detail['labelIds'], true);
        $data['detail'] = $detail;
        $data['randomList'] = $randomList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}