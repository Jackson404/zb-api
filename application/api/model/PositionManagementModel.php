<?php

namespace app\api\model;

use think\Model;

class PositionManagementModel extends Model
{
    protected $name = 'position_management';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function edit($positionId, $data)
    {
        return $this->where('id', '=', $positionId)->where('isDelete', '=', 0)->update($data);
    }

    public function getDetail($positionId)
    {
        return $this->where('id', '=', $positionId)->where('isDelete', '=', 0)->find();
    }

    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('p')
            ->join('zb_category_management zcm', 'p.positionCateId = zcm.id')
            ->join('zb_company_management zco', 'p.companyId = zco.id')
            ->field('*')
            ->paginate(null, false, $config);
    }

    public function checkName($name)
    {
        $count = $this->where('name', 'eq', $name)->where('isDelete', 'eq', 0)->count();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }

    }
}