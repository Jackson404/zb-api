<?php

namespace app\api\model;

use think\Model;

class CategoryManagementModel extends Model
{
    protected $name = 'category_management';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getTopCate($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('isDelete', '=', 0)->where('pid', '=', 0)->paginate(null, false, $config);
    }

    public function getTopCateWithoutPage()
    {
        return $this->where('isDelete', '=', 0)->where('pid', '=', 0)
            ->select();
    }

    public function getNextCate($categoryId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];

        return $this->where('pid', '=', $categoryId)->where('isDelete', '=', 0)
            ->paginate(null, false, $config);
    }

    public function getNextCateWithoutPage($categoryId)
    {
        return $this->where('pid', '=', $categoryId)->where('isDelete', '=', 0)
            ->select();
    }

    public function del($ids)
    {
        return $this->whereIn('id', $ids)->update(['isDelete' => 1]);
    }

    public function checkName($name)
    {
        $count = $this->where('name', '=', $name)->where('isDelete', '=', 0)
            ->limit(0, 1)
            ->count();
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

    public function getAll()
    {
        return $this->where('isDelete', '=', 0)->order('id', 'desc')->select();
    }

}