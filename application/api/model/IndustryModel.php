<?php

namespace app\api\model;

use think\Model;

class IndustryModel extends Model
{
    protected $table = 'zb_industry';
    protected $resultSetType = 'collection';

    public function getAll()
    {
        return $this->where('isDelete', '=', 0)
            ->select();
    }

    public function getTopIndustryPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('isDelete', '=', 0)
            ->where('pid', '=', 0)
            ->paginate(null, false, $config);
    }

    public function getAllTopIndustry()
    {
        return $this->where('isDelete', '=', 0)
            ->where('pid', '=', 0)
            ->select();
    }

    public function getNextIndustryPage($code, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('isDelete', '=', 0)
            ->where('pid', '=', $code)
            ->paginate(null, false, $config);
    }

    public function getAllNextIndustry($code)
    {
        return $this->where('isDelete', '=', 0)
            ->where('pid', '=', $code)
            ->select();
    }

    public function getDetail($industryId)
    {
        return $this->where('isDelete', '=', 0)
            ->where('id', '=', $industryId)
            ->find();
    }
}