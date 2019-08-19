<?php

namespace app\api\controller\v1;

use app\api\model\NewsModel;
use think\Request;
use Util\Check;
use Util\Util;

class News extends IndexBase
{
    public function getByPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $newsModel = new NewsModel();
        $page = $newsModel->getByPage($pageIndex, $pageSize);

        $pageData = $page->toArray();
        $data = $pageData['data'];
        foreach ($data as $k => $v) {
            $data[$k]['year'] = date('Y', strtotime($v['createTime']));
            $data[$k]['month'] = date('m', strtotime($v['createTime']));
            $data[$k]['day'] = date('d', strtotime($v['createTime']));
        }

        $pageData['data'] = $data;

        $arr['page'] = $pageData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function getDetail()
    {
        $params = Request::instance()->param();
        $newsId = Check::checkInteger($params['newsId'] ?? '');

        $newsModel = new NewsModel();
        $detail = $newsModel->getDetail($newsId);
        $detailData = $detail->toArray();
        $detailCreateTime = $detailData['createTime'];
        $detailTimeStamp = strtotime($detailCreateTime);
        $detailData['year'] = date('Y',$detailTimeStamp);
        $detailData['month'] = date('m',$detailTimeStamp);
        $detailData['day'] = date('d',$detailTimeStamp);
        $categoryId = $detailData['categoryId'];

        $randomNewsList = $newsModel->getRandomNewsListLimit($categoryId, $newsId);
        foreach ($randomNewsList as $k=>$randomNews){
            $randomNewsCreateTime = $randomNews['createTime'];
            $randomNewsTimeStamp = strtotime($randomNewsCreateTime);
            $randomNewsList[$k]['year'] = date('Y',$randomNewsTimeStamp);
            $randomNewsList[$k]['month'] = date('m',$randomNewsTimeStamp);
            $randomNewsList[$k]['day'] = date('d',$randomNewsTimeStamp);
        }

        $nextNews = $newsModel->getRandomNextNewsLimit($newsId);
        foreach ($nextNews as $k=>$v){
            $nextCreateTime = $v['createTime'];
            $nextTimeStamp = strtotime($nextCreateTime);
            $nextNews[$k]['year'] = date('Y',$nextTimeStamp);
            $nextNews[$k]['month'] = date('m',$nextTimeStamp);
            $nextNews[$k]['day'] = date('d',$nextTimeStamp);
        }

        $data['detail'] = $detailData;
        $data['randomNewsList'] = $randomNewsList;
        $data['nextNews'] = $nextNews;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getNewsPageByCateId()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $newsModel = new NewsModel();
        $positionNews = $newsModel->getIndexPageNewsByCateId(3, 1);
        $soldierNews = $newsModel->getIndexPageNewsByCateId(4, 1);

        $page = $newsModel->getNewsByCateIdPage($categoryId, $pageIndex, $pageSize);


        $pageData = $page->toArray();
        $data = $pageData['data'];
        foreach ($data as $k => $v) {
            $data[$k]['year'] = date('Y', strtotime($v['createTime']));
            $data[$k]['month'] = date('m', strtotime($v['createTime']));
            $data[$k]['day'] = date('d', strtotime($v['createTime']));
        }

        $pageData['data'] = $data;

        $arr['page'] = $pageData;
        $arr['positionNews'] = $positionNews;
        $arr['soldierNews'] = $soldierNews;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

}