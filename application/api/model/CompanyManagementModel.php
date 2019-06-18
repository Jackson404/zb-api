<?php

namespace app\api\model;

use think\Model;

class CompanyManagementModel extends Model
{
    protected $name = 'company_management';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function edit($companyId, $data)
    {
        return $this->where('id', '=', $companyId)->where('isDelete', '=', 0)->update($data);
    }

    public function getDetail($companyId)
    {
        return $this->where('id', '=', $companyId)->where('isDelete', '=', 0)->find();
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

    public function updatePositionCountInc($companyId, $count)
    {
        return $this->where('id', '=', $companyId)
            ->where('isDelete', '=', 0)
            ->inc('positionCount', $count)
            ->update();
    }

    public function updatePositionCountDec($companyId, $count)
    {
        return $this->where('id', '=', $companyId)
            ->where('isDelete', '=', 0)
            ->dec('positionCount', $count)
            ->update();
    }
}