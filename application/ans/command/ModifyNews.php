<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ModifyNews extends Command
{
    protected function configure()
    {
        $this->setName('news');
    }

    protected function execute(Input $input, Output $output)
    {
        $oldNews = Db::connect($GLOBALS['db_config'])->name('articles')->select();

        foreach ($oldNews as $k => $v) {
//            var_dump($v);

            $arr['title'] = $v['article_title'];
            $arr['keywords'] = $v['article_keywords'];
            $arr['description'] = $v['article_description'];
            $arr['content'] = $v['article_content'];
//            $img = 'statics/images/201904/5cbd33a08d51a.jpg';
//            $newImg = '/public/uploads/201904/5cbd33a08d51a.jpg';

            $article_img_url = $v['article_img_url'];
            $article_img_urlArr = explode('/', $article_img_url);

            $arr['categoryId'] = $v['article_cate'];
            $arr['imgUrl'] = '/public/uploads/' . $article_img_urlArr[2] . '/' . $article_img_urlArr[3];
            $arr['createTime'] = date('Y-m-d H:i:s', $v['article_create_date']);
            $arr['createBy'] = 1;
            $arr['updateTime'] = date('Y-m-d H:i:s', $v['article_create_date']);
            $arr['updateBy'] = 1;


            $insertId = Db::table('zb_news')->insert($arr);
            var_dump($insertId);
        }
    }
}