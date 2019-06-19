<?php

namespace app\api\model;

use think\Model;

class NewsCategoryModel extends Model
{
    protected $name = 'news_category';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function edit($categoryId, $data)
    {
        return $this->where('isDelete', '=', 0)->where('id', '=', $categoryId)->update($data);
    }

    public function checkName($name)
    {
        $count = $this->where('name', '=', $name)->where('isDelete', '=', 0)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getDetail($categoryId)
    {
        return $this->where('id', 'eq', $categoryId)->where('isDelete', 'eq', 0)->find();

    }
}