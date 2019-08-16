<?php

namespace app\api\model;

use think\Model;

class SlideShowModel extends Model
{
    protected $name = 'slide_show';
    protected $pk = 'id';

    public function getIndexSlideShow()
    {
        return $this->where('isDelete', '=',0)
            ->where('type','=',1)
            ->order('sort', 'desc')
            ->select();
    }

    public function getIndexAds()
    {
        return $this->where('isDelete', '=',0)
            ->where('type','=',2)
            ->order('sort', 'desc')
            ->select();
    }

    public function getDetail($slideId){
        return $this->where('isDelete','=',0)->where('id','=',$slideId)->find();
    }

}