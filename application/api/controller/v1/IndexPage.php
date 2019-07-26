<?php

namespace app\api\controller\v1;

use app\api\model\CategoryManagementModel;
use app\api\model\NewsModel;
use app\api\model\PositionCateModel;
use app\api\model\PositionManagementModel;
use app\api\model\SlideShowModel;
use Util\Util;

class IndexPage extends IndexBase
{
    /**
     * 前端首页
     * @throws \think\exception\DbException
     */
    public function index()
    {

        $slideModel = new SlideShowModel();
        $slideList = $slideModel->getIndexSlideShow();

        $adsList = $slideModel->getindexAds();

//        $cateModel = new CategoryManagementModel();
        $positionCateModel = new PositionCateModel();

        $positionModel = new PositionManagementModel();

        $CateList = $positionCateModel->getCateListGroupById();


        foreach ($CateList as $k => $v) {
            if ($k == 0) {
                $CateList[$k]['check'] = true;
            } else {
                $CateList[$k]['check'] = false;
            }
            $positionCateId = $v['positionCateId'];
            $positionList = $positionModel->getPositionByCateIdWithLimit($positionCateId, 6);
            $positionListData = $positionList->toArray();
            foreach ($positionListData as $k1 => $v1) {
                $positionListData[$k1]['labelIds'] = json_decode($v1['labelIds'], true);
            }
            $CateList[$k]['list'] = $positionListData;
        }

        $hotPosition = $positionModel->getIndexHotPosition();
        $hotPositionData = $hotPosition->toArray();

        $newsModel = new NewsModel();
        $positionNewsList = $newsModel->getIndexPageNewsByCateId(3, 3);
        $soldierNewsList = $newsModel->getIndexPageNewsByCateId(4, 3);
        $positionNewsListData = $positionNewsList->toArray();
        $soldierNewsListData = $soldierNewsList->toArray();

        foreach ($positionNewsListData as $k => $v) {
            $positionNewsListData[$k]['yearMonth'] = date('m-d', strtotime($v['createTime']));
        }
        foreach ($soldierNewsListData as $k => $v) {
            $soldierNewsListData[$k]['yearMonth'] = date('m-d', strtotime($v['createTime']));
        }

        $data['hotPositionList'] = $hotPositionData;
        $data['positionCateList'] = $CateList;
        $data['slideShowList'] = $slideList;
        $data['adsList'] = $adsList;
        $data['positionNewsList'] = $positionNewsListData;
        $data['soldierNewsList'] = $soldierNewsListData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

}