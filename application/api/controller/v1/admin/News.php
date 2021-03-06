<?php

namespace app\api\controller\v1\admin;

use app\api\model\NewsModel;
use think\Request;
use Util\Check;
use Util\Util;

class News extends AdminBase
{
    /**
     * 添加新闻
     */
    public function add()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $title = Check::check($params['title'] ?? '');
        $keywords = Check::check($params['keywords'] ?? '');
        $description = Check::check($params['description'] ?? '');
        $content = Check::check($params['content'] ?? '');
        $content = stripslashes($content);
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $imgUrl = stripslashes($imgUrl);
        $isShow = Check::checkInteger($params['isShow'] ?? 1);
        $userId = $GLOBALS['userId'];

        if ($title == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $newsModel = new NewsModel();
        $data = [
            'categoryId' => $categoryId,
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
            'content' => htmlspecialchars_decode($content),
            'imgUrl' => $imgUrl,
            'isShow' => $isShow,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $newsModel->save($data);
        if ($insertRow > 0) {
            $arr['id'] = $newsModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

    }

    /**
     * 编辑新闻
     */
    public function edit()
    {
        $params = Request::instance()->param();
        $newsId = Check::checkInteger($params['newsId'] ?? '');
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $title = Check::check($params['title'] ?? '');
        $keywords = Check::check($params['keywords'] ?? '');
        $description = Check::check($params['description'] ?? '');
        $content = Check::check($params['content'] ?? '');
        $content = stripslashes($content);
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $imgUrl = stripslashes($imgUrl);
        $isShow = Check::checkInteger($params['isShow'] ?? 1);
        $userId = $GLOBALS['userId'];

        if ($title == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $newsModel = new NewsModel();
        $data = [
            'categoryId' => $categoryId,
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
            'content' => htmlspecialchars_decode($content),
            'imgUrl' => $imgUrl,
            'isShow' => $isShow,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $newsModel->edit($newsId, $data);
        if ($updateRow > 0) {
            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
            exit;
        }

    }

    /**
     * 删除新闻
     */
    public function del()
    {
        $params = Request::instance()->param();
        $newsId = Check::checkInteger($params['newsId'] ?? '');

        $newsModel = new NewsModel();
        $data = [
            'id' => $newsId,
            'isDelete' => 1
        ];
        $delRow = $newsModel->isUpdate(true)->save($data);
        if ($delRow > 0) {
            $arr['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }

    }


    /**
     * 后台分页获取 展示和不展示都有
     */
    public function getByPageWithAdmin()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $newsModel = new NewsModel();
        $page = $newsModel->getByPageWithAdmin($pageIndex, $pageSize);

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

    /**
     * 获取新闻详情
     * @throws \think\Exception
     */
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

    /**
     * 根据新闻分类分页获取
     */
    public function getNewsPageByCateIdWithAdmin()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $newsModel = new NewsModel();
        $page = $newsModel->getNewsByCateIdPageAdmin($categoryId, $pageIndex, $pageSize);

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


}