<?php

namespace app\api\controller\v1;

use app\api\model\CategoryManagementModel;
use app\api\model\NewsModel;
use app\api\model\PositionManagementModel;
use app\api\model\SlideShowModel;
use think\Db;
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

        $cateModel = new CategoryManagementModel();

        $positionModel = new PositionManagementModel();

        $topCateList = $cateModel->getTopCateWithoutPage();
        $topCateListData = $topCateList->toArray();
        foreach($topCateListData as $k=>$v){
            if ($k == 0){
                $topCateListData[$k]['check'] = true;
            }else{
                $topCateListData[$k]['check'] = false;
            }
            $positionCateId = $v['id'];
            $positionList = $positionModel->getPositionByCateIdWithLimit($positionCateId,6);
            $positionListData = $positionList->toArray();
            foreach ($positionListData as $k1=>$v1){
                $positionListData[$k1]['labelIds'] = json_decode($v1['labelIds'],true);
            }
            $topCateListData[$k]['list'] = $positionListData;
        }


        $hotPosition = $positionModel->getIndexHotPosition();
        $hotPositionData = $hotPosition->toArray();

        $newsModel = new NewsModel();
        $positionNewsList = $newsModel->getIndexPageNewsByCateId(3,3);
        $soldierNewsList = $newsModel->getIndexPageNewsByCateId(4,3);
        $positionNewsListData = $positionNewsList->toArray();
        $soldierNewsListData = $soldierNewsList->toArray();

        foreach ($positionNewsListData as $k => $v) {
            $positionNewsListData[$k]['yearMonth'] = date('m-d',strtotime($v['createTime']));
        }
        foreach ($soldierNewsListData as $k => $v) {
            $soldierNewsListData[$k]['yearMonth'] = date('m-d',strtotime($v['createTime']));
        }

        $data['hotPositionList'] = $hotPositionData;
        $data['positionCateList'] = $topCateListData;
        $data['slideShowList'] = $slideList;
        $data['adsList'] = $adsList;
        $data['positionNewsList'] = $positionNewsListData;
        $data['soldierNewsList'] = $soldierNewsListData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

//    public function copyNews()
//    {
//        $oldNews = Db::connect($GLOBALS['db_config'])->name('articles')->select();
//
//        foreach ($oldNews as $k => $v) {
////            var_dump($v);
//
//            $arr['title'] = $v['article_title'];
//            $arr['keywords'] = $v['article_keywords'];
//            $arr['description'] = $v['article_description'];
//            $arr['content'] = $v['article_content'];
////            $img = 'statics/images/201904/5cbd33a08d51a.jpg';
////            $newImg = '/public/uploads/201904/5cbd33a08d51a.jpg';
//
//            $article_img_url = $v['article_img_url'];
//            $article_img_urlArr = explode('/', $article_img_url);
//
//            $arr['categoryId'] = $v['article_cate'];
//            $arr['imgUrl'] = '/public/uploads/' . $article_img_urlArr[2] . '/' . $article_img_urlArr[3];
//            $arr['createTime'] = date('Y-m-d H:i:s', $v['article_create_date']);
//            $arr['createBy'] = 1;
//            $arr['updateTime'] = date('Y-m-d H:i:s', $v['article_create_date']);
//            $arr['updateBy'] = 1;
//
//            $insertId = Db::table('zb_news')->insert($arr);
//            var_dump($insertId);
//
//        }
//    }
//
//    public function copyBarToCategoryManagement()
//    {
//        $oldBar = Db::connect($GLOBALS['db_config'])->name('bar')->select();
//
////           TRUNCATE TABLE zb_category_management;
////          alter table zb_category_management  AUTO_INCREMENT=11;
//        foreach ($oldBar as $k => $v) {
//            $arr['id'] = $v['id'];
//            $arr['name'] = $v['name'];
//            $arr['pid'] = $v['t_cate'];
//            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
//            $arr['createBy'] = 1;
//            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
//            $arr['updateBy'] = 1;
//
//            $insertId = Db::table('zb_category_management')->insert($arr);
//            var_dump($insertId);
//        }
//    }
//
//    public function copyCompany()
//    {
//        $oldCompany = Db::connect($GLOBALS['db_config'])->name('company')->select();
//
////           TRUNCATE TABLE zb_category_management;
////          alter table zb_category_management  AUTO_INCREMENT=11;
//        foreach ($oldCompany as $k => $v) {
//            $arr['id'] = $v['c_id'];
//            $arr['name'] = $v['name'];
//            $arr['address'] = $v['address'];
//            $arr['phone'] = $v['phone'];
//            $arr['nature'] = $v['xz'];
//            $arr['profile'] = $v['des'];
//            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
//            $arr['createBy'] = 1;
//            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
//            $arr['updateBy'] = 1;
//
//            $insertId = Db::table('zb_company_management')->insert($arr);
//            var_dump($insertId);
//        }
//    }
//
//    public function copyImgToSlide()
//    {
//        $oldImg = Db::connect($GLOBALS['db_config'])->name('img_news_items')->select();
//
//        foreach ($oldImg as $k => $v) {
//
//            $arr['remark'] = $v['item_text'];
//            $arr['turnUrl'] = $v['item_href'];
//
//            $img_url = $v['item_img_url'];
//            $img_urlArr = explode('/', $img_url);
//
//
//            $arr['imgUrl'] = '/public/uploads/' . $img_urlArr[2] . '/' . $img_urlArr[3];
//            $arr['createTime'] = currentTime();
//            $arr['createBy'] = 1;
//            $arr['updateTime'] = currentTime();
//            $arr['updateBy'] = 1;
//
//
//            $insertId = Db::table('zb_slide_show')->insert($arr);
//            var_dump($insertId);
//
//        }
//
//    }
//
//    public function copyTagToLabel()
//    {
//        $oldTag = Db::connect($GLOBALS['db_config'])->name('tag')->select();
//
////           TRUNCATE TABLE zb_category_management;
////          alter table zb_category_management  AUTO_INCREMENT=11;
//        foreach ($oldTag as $k => $v) {
//            $arr['name'] = $v['name'];
//
//            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
//            $arr['createBy'] = 1;
//            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
//            $arr['updateBy'] = 1;
//
//            $insertId = Db::table('zb_label_management')->insert($arr);
//            var_dump($insertId);
//        }
//    }
//
//    public function copyTopicToPosition()
//    {
//        $oldTopPic = Db::connect($GLOBALS['db_config'])->name('topics')->select();
//
////           TRUNCATE TABLE zb_category_management;
////          alter table zb_category_management  AUTO_INCREMENT=11;
//        foreach ($oldTopPic as $k => $v) {
//            $arr['positionCateId'] = $v['t_cate'];
//            $arr['name'] = $v['t_title'];
//            $arr['companyId'] = $v['t_gs'];
//            $arr['minPay'] = $v['s_mon'];
//            $arr['maxPay'] = $v['e_mon'];
//            $arr['pay'] = $v['s_mon'].'-'.$v['e_mon'];
//            $arr['minWorkExp'] = 0;
//            $arr['maxWorkExp'] = 0;
//            $arr['workExp'] = 0;
//            $arr['education'] = $v['t_xl'];
//            $arr['age'] = $v['t_nj'];
//            $arr['num'] = $v['t_rs'];
//            $arr['labelIds'] = $v['t_bq'];
//            $arr['isSoldierPriority'] = $v['t_jr'];
//            $arr['address'] = $v['t_dz'];
//            $arr['positionRequirement'] = $v['t_yq'];
//
//            $arr['createTime'] = date('Y-m-d H:i:s', $v['t_date']);
//            $arr['createBy'] = 1;
//            $arr['updateTime'] = date('Y-m-d H:i:s', $v['t_date']);
//            $arr['updateBy'] = 1;
//
//            $insertId = Db::table('zb_position_management')->insert($arr);
//            var_dump($insertId);
//        }
//    }
}