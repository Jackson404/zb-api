<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ModifySlide extends Command
{
    protected function configure()
    {
        $this->setName('slide');
    }

    protected function execute(Input $input, Output $output)
    {
        $oldImg = Db::connect($GLOBALS['db_config'])->name('img_news_items')->select();

        foreach ($oldImg as $k => $v) {

            $arr['remark'] = $v['item_text'];
            $arr['turnUrl'] = $v['item_href'];

            $img_url = $v['item_img_url'];
            $img_urlArr = explode('/', $img_url);


            $arr['imgUrl'] = '/public/uploads/' . $img_urlArr[2] . '/' . $img_urlArr[3];
            $arr['createTime'] = currentTime();
            $arr['createBy'] = 1;
            $arr['updateTime'] = currentTime();
            $arr['updateBy'] = 1;


            $insertId = Db::table('zb_slide_show')->insert($arr);
            var_dump($insertId);
        }
    }
}