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
        return $this->alias('p')
            ->join('zb_category_management zcm', 'p.positionCateId = zcm.id')
            ->join('zb_company_management zco', 'p.companyId = zco.id')
            ->where('p.isDelete', '=', 0)
            ->where('p.id', '=', $positionId)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,p.address,
            p.positionRequirement,p.isShow,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->find();
    }

    public function getDetailForApply($positionId)
    {
        return $this->alias('p')
            ->join('zb_category_management zcm', 'p.positionCateId = zcm.id')
            ->join('zb_company_management zco', 'p.companyId = zco.id')
            ->where('p.isDelete', '=', 0)
            ->where('p.id', '=', $positionId)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.isSoldierPriority,p.address,
            p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->find();
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
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,p.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id','desc')
            ->paginate(null, false, $config);
    }

    public function getPageByCompanyId($companyId,$pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('p')
            ->join('zb_category_management zcm', 'p.positionCateId = zcm.id')
            ->join('zb_company_management zco', 'p.companyId = zco.id')
            ->where('p.isDelete', '=', 0)
            ->where('p.companyId','=',$companyId)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,p.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id','desc')
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

    public function search($value)
    {
        return $this->where('isDelete', '=', 0)
            ->whereLike('name', '%' . $value . '%')
            ->order('id','desc')
            ->select();
    }

    public function filter($positionSql, $salarySql, $educationSql, $workYearSql, $isSoldierPrioritySql,$labelIdsSql, $pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;
        $sql = "select * from zb_position_management where isDelete = 0   $positionSql  $salarySql  $educationSql  $workYearSql  $isSoldierPrioritySql $labelIdsSql  order by id desc limit $offset,$pageSize";

        $countSql = "select count(*) from zb_position_management where isDelete = 0   $positionSql  $salarySql  $educationSql  $workYearSql  $isSoldierPrioritySql $labelIdsSql";

        $result = $this->query($sql);
        $countResult = $this->query($countSql);
        $total = $countResult[0]["count(*)"];
        return [$result, $total];
    }

    public function getIndexHotPosition()
    {
        return $this->alias('p')
            ->join('zb_category_management zcm', 'p.positionCateId = zcm.id')
            ->join('zb_company_management zco', 'p.companyId = zco.id')
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,p.address,
            p.positionRequirement,p.isShow,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->where('p.isShow', '=', 1)
            ->order('p.id', 'desc')
            ->limit(0, 6)
            ->select();
    }

    public function updateApplyCountInc($positionId, $count)
    {
        return $this->where('id', '=', $positionId)
            ->where('isDelete', '=', 0)
            ->inc('positionCount', $count)
            ->update();
    }
    public function updateApplyCountDec($positionId, $count)
    {
        return $this->where('id', '=', $positionId)
            ->where('isDelete', '=', 0)
            ->dec('positionCount', $count)
            ->update();
    }
}