<?php

namespace app\api\model;

use think\Model;

class LabelManagementModel extends Model
{
    protected $name = 'label_management';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function edit($labelId, $data)
    {
        return $this->where('isDelete', '=', 0)->where('id', '=', $labelId)->update($data);
    }

    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('isDelete', '=', 0)->paginate(null, false, $config);
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

    public function getDetail($labelId)
    {
        return $this->where('id', 'eq', $labelId)->where('isDelete', 'eq', 0)
            ->find();
    }
}