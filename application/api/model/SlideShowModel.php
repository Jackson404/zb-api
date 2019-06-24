<?php

namespace app\api\model;

use think\Model;

class SlideShowModel extends Model
{
    protected $name = 'slide_show';
    protected $pk = 'id';

    public function getIndexSlideShow()
    {
        return $this->where('isDelete', 0)->order('id', 'desc')->select();
    }

}