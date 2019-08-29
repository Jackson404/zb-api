<?php

namespace app\api\model;

use think\Model;

class EpResumeCateModel extends Model
{
    protected $table = 'zb_enterprise_resume_cate';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getResumeCateListByUserId($userId)
    {
        return $this->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->field('id,name')
            ->select();
    }

    public function getDetail($resumeCateId)
    {
        return $this->where('id','=',$resumeCateId)
            ->where('isDelete','=',0)
            ->find();
    }

    /**
     * 删除员工时，清空员工简历分类表的信息
     * @param $emUserId
     * @return EpResumeCateModel
     */
    public function delEmUserResumeCateInfo($emUserId){
        return $this->where('userId','=',$emUserId)
            ->where('isDelete','=',0)
            ->update(['isDelete'=>1]);
    }

}