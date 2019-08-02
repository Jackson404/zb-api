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
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->where('p.id', '=', $positionId)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.interviewTime,p.unitPrice,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->find();
    }

    public function getDetailForApply($positionId)
    {
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->where('p.id', '=', $positionId)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->find();
    }


    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
            ->paginate(null, false, $config);
    }

    public function getByLimit($limit)
    {
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
            ->limit(0,$limit)
            ->select();
    }

    public function getPageBySolider($solider,$pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isSoldierPriority','=',$solider)
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
            ->paginate(null, false, $config);
    }

    public function getLimitBySolider($solider,$limit)
    {
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isSoldierPriority','=',$solider)
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
            ->limit(0,$limit)
            ->select();
    }

    public function getAll()
    {
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
            ->select();
    }

    public function getPageByCompanyId($companyId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->where('p.companyId', '=', $companyId)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
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
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->whereLike('p.name', '%' . $value . '%')
            ->where('p.isDelete', '=', 0)
            ->field('p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('p.id', 'desc')
            ->select();
    }

    public function filter($positionSql, $salarySql, $educationSql, $workYearSql, $isSoldierPrioritySql, $labelIdsSql, $provinceSql, $citySql, $areaSql, $pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;

        $sql = "SELECT p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy FROM zb_position_management as p
            LEFT JOIN zb_position_cate as zcm ON p.positionCateId = zcm.id 
            LEFT JOIN zb_company_management as zco ON p.companyId = zco.id 
            WHERE p.isDelete = 0  $positionSql  $salarySql  $educationSql  $workYearSql  $isSoldierPrioritySql $labelIdsSql  $provinceSql  $citySql  $areaSql  
            order by concat(p.isSoldierPriority,p.id) desc limit $offset,$pageSize";

        $countSql = "SELECT count('p.*') FROM zb_position_management as p
            LEFT JOIN zb_position_cate as zcm ON p.positionCateId = zcm.id 
            LEFT JOIN zb_company_management as zco ON p.companyId = zco.id 
            WHERE p.isDelete = 0  $positionSql  $salarySql  $educationSql  $workYearSql  $isSoldierPrioritySql $labelIdsSql  $provinceSql  $citySql  $areaSql ";

        $result = $this->query($sql);
        $countResult = $this->query($countSql);
        $total = $countResult[0]["count('p.*')"];
        return [$result, $total];
    }

    public function filterOrder( $areaInfoSql,$priceOrderSql,$keywordsSql, $pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;

        $sql = "SELECT p.id,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,zco.province,zco.city,zco.area,zco.address,
            p.positionRequirement,p.isShow,p.applyCount,p.interviewTime,p.unitPrice,p.createTime,p.createBy,p.updateTime,p.updateBy FROM zb_position_management as p
            LEFT JOIN zb_position_cate as zcm ON p.positionCateId = zcm.id 
            LEFT JOIN zb_company_management as zco ON p.companyId = zco.id 
            WHERE p.isDelete = 0    $areaInfoSql $keywordsSql   $priceOrderSql limit $offset,$pageSize";

        $countSql = "SELECT count('p.*') FROM zb_position_management as p
            LEFT JOIN zb_position_cate as zcm ON p.positionCateId = zcm.id 
            LEFT JOIN zb_company_management as zco ON p.companyId = zco.id 
            WHERE p.isDelete = 0    $areaInfoSql $keywordsSql ";

        $result = $this->query($sql);
        $countResult = $this->query($countSql);
        $total = $countResult[0]["count('p.*')"];
        return [$result, $total];
    }

    public function getIndexHotPosition()
    {
        return $this->alias('p')
            ->join('zb_position_cate zcm', 'p.positionCateId = zcm.id', 'left')
            ->join('zb_company_management zco', 'p.companyId = zco.id', 'left')
            ->where('p.isDelete', '=', 0)
            ->where('p.isShow', '=', 1)
            ->field('p.id,p.name')
            ->order('p.id', 'desc')
            ->limit(0, 4)
            ->select();
    }

    public function updateApplyCountInc($positionId, $count)
    {
        return $this->where('id', '=', $positionId)
            ->where('isDelete', '=', 0)
            ->inc('applyCount', $count)
            ->update();
    }

    public function updateApplyCountDec($positionId, $count)
    {
        return $this->where('id', '=', $positionId)
            ->where('isDelete', '=', 0)
            ->dec('applyCount', $count)
            ->update();
    }

    public function getPositionByCateIdWithLimit($cateId, $limit)
    {
        return $this->alias('p')
            ->join('zb_company_management c', 'p.companyId = c.id', 'left')
            ->where('p.positionCateId', '=', $cateId)->where('p.isDelete', '=', 0)
            ->where('p.isShow', '=', 1)
            ->field('p.id,p.name,p.positionCateId,p.companyId,c.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,c.province,c.city,c.area,c.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->limit(0, $limit)
            ->select();
    }

    public function getRandomPositionListLimit($positionId,$limit)
    {
        $sql = "SELECT p.id, p.name,c.name as companyName,p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,
            p.workExp,p.education,p.age,p.num,p.education,p.isSoldierPriority,p.address,p.applyCount,p.interviewTime,p.unitPrice FROM zb_position_management as p 
            LEFT JOIN zb_company_management as c ON p.companyId = c.id 
            WHERE p.isDelete=0 AND p.isShow=1 AND p.id <> '$positionId'
             ORDER BY rand() LIMIT 0,$limit";

        return $this->query($sql);

    }

    public function getRandomPositionLimit()
    {
        $sql = "SELECT p.id, p.name,c.name as companyName,p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,
            p.workExp,p.education,p.age,p.num,p.education,p.isSoldierPriority,p.address,p.applyCount FROM zb_position_management as p LEFT JOIN zb_company_management as c
            ON p.companyId = c.id 
            WHERE p.isDelete=0 AND p.isShow=1 
             ORDER BY rand() LIMIT 0,5";

        return $this->query($sql);

    }
}