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
            ->join('zb_industry i', 'c.industryId = i.code', 'left')
            ->where('c.id', '=', $companyId)
            ->where('c.isDelete', '=', 0)
            ->field('c.id,c.name,i.id as industryId,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
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
            ->join('zb_position_cate i', 'c.industryId = i.id', 'left')
            ->where('c.isDelete', '=', 0)
            ->field('c.id,c.name,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
            c.profile,c.positionCount,c.remark,c.dataBank,c.createTime,c.createBy,c.updateTime,c.updateBy')
            ->order('c.id', 'desc')
            ->paginate(null, false, $config);
    }

    public function getAll()
    {
        return $this->alias('c')
            ->join('zb_industry i', 'c.industryId = i.code', 'left')
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

    public function filterCompanyPage($areaInfo, $industryInfo, $pageIndex, $pageSize)
    {
        $offset = ($pageIndex - 1) * $pageSize;

        if ($areaInfo != '') {
            $areaStr = "  AND CONCAT(c.province,c.city,c.area,c.address) like '%$areaInfo%' ";
        } else {
            $areaStr = '';
        }

        if ($industryInfo != '') {
            $induStr = "  AND i.name like '%$industryInfo%' ";
        } else {
            $induStr = '';
        }

        $sql = "SELECT c.id,c.name,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
            c.profile,c.positionCount,c.remark,c.dataBank,c.createTime,c.createBy,c.updateTime,c.updateBy
              FROM zb_company_management as c LEFT JOIN zb_industry as i ON c.industryId=i.code 
              WHERE c.isDelete=0   $areaStr  $induStr  ORDER BY c.id DESC LIMIT $offset,$pageSize";

        $countSql = "SELECT count('c.*') FROM zb_company_management as c 
                    LEFT JOIN zb_industry as i ON c.industryId=i.code 
                    WHERE c.isDelete=0   $areaStr  $induStr ";
        $r1 = $this->query($sql);
        $r2 = $this->query($countSql);
        return [$r1, $r2[0]["count('c.*')"]];
    }


    public function filterCompanyByIndustryPage($info, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];

        return $this->alias('c')
            ->join('zb_industry i', 'c.industryId = i.code', 'left')
            ->where('c.isDelete', '=', 0)
            ->where('i.name', '=', $info)
            ->field('c.id,c.name,i.name as industryName,c.province,c.city,c.area,c.address,c.contact,c.phone,c.wxNumber,c.leader,c.nature,
            c.profile,c.positionCount,c.remark,c.dataBank,c.createTime,c.createBy,c.updateTime,c.updateBy')
            ->order('c.id', 'desc')
            ->paginate(null, false, $config);
    }

    /**
     * 根据公司名字获取详情  公司名字不可重复
     * @param $companyName
     */
    public function getDetailByCompanyName($companyName)
    {
        return $this->where('isDelete', '=', 0)
            ->where('name', '=', $companyName)
            ->where('isCert','=',1)
            ->find();
    }
}