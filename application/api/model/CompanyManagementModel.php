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
        return $this->alias('c')
            ->join('zb_industry i', 'c.industryId = i.code','left')
            ->where('c.id', '=', $companyId)
            ->where('c.isDelete', '=', 0)
            ->field('c.id,c.name,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
            c.profile,c.positionCount,c.remark,c.dataBank,c.createTime,c.createBy,c.updateTime,c.updateBy')
            ->find();
    }

    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];

        return $this->alias('c')
            ->join('zb_industry i', 'c.industryId = i.code','left')
            ->where('c.isDelete', '=', 0)
            ->field('c.id,c.name,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
            c.profile,c.positionCount,c.remark,c.dataBank,c.createTime,c.createBy,c.updateTime,c.updateBy')
            ->order('c.id', 'desc')
            ->paginate(null, false, $config);
    }

    public function getAll()
    {
        return $this->alias('c')
            ->join('zb_industry i', 'c.industryId = i.code','left')
            ->where('c.isDelete', '=', 0)
            ->field('c.id,c.name,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
            c.profile,c.positionCount,c.remark,c.dataBank,c.createTime,c.createBy,c.updateTime,c.updateBy')
            ->order('c.id', 'desc')
            ->select();
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