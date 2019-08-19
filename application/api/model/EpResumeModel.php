<?php

namespace app\api\model;

use think\Model;

class EpResumeModel extends Model
{
    protected $table = 'zb_enterprise_resume';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    /**
     * 申请的简历是否存在
     * @param $userId
     * @param $resumeId
     * @return bool
     * @throws \think\Exception
     */
    public function resumeExistsSource1($userId, $resumeId)
    {
        $count = $this->where('userId', '=', $userId)
            ->where('resumeId', '=', $resumeId)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 下载的简历是否存在
     * @param $userId
     * @param $idCard
     * @param $phone
     * @return bool
     * @throws \think\Exception
     */
    public function resumeExistsSource2($userId, $idCard, $phone)
    {
        $count = $this->where('userId', '=', $userId)
            ->where('idCard', '=', $idCard)
            ->where('phone', '=', $phone)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用户一天下载的简历数量
     * @param $date
     * @param $userId
     * @return int|string
     * @throws \think\Exception
     */
    public function getDownloadNumOneDay($date, $userId)
    {
        $count = $this->where('userId', '=', $userId)
            ->where('createTime', 'like', "$date%")
            ->where('source', '=', 2)
            ->where('isDelete', '=', 0)
            ->count();
        return $count;
    }

    /**
     * 获取用户的简历列表
     * @param $userId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByUserId($userId)
    {
        return $this->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->select();
    }

    /**
     * 分页获取企业用户的简历列表
     * @param $userId
     * @param $pageIndex
     * @param $pageSize
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getListByUserIdPage($userId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->paginate(null, false, $config);
    }

    public function getListByUserIdPageWithCate($userId, $resumeCateId,$pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('userId', '=', $userId)
            ->where('resumeCateId','=',$resumeCateId)
            ->where('isDelete', '=', 0)
            ->paginate(null, false, $config);
    }

    /**
     * 获取用户下载的简历列表
     * @param $userId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDownResumeListByUserId($userId)
    {
        return $this->where('userId', '=', $userId)
            ->where('source', '=', 2)
            ->where('isDelete', '=', 0)
            ->select();
    }

    /**
     * 获取企业简历列表  （申请的简历）
     * @param $userId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEpResumeListApply($userId)
    {
        return $this->alias('er')
            ->join('zb_resume r', 'er.resumeId=r.id', 'left')
            ->join('zb_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_resume_cate c', 'er.resumeCateId=c.id', 'left')
            ->where('er.userId', '=', $userId)
            ->where('er.source', '=', 1)
            ->where('er.isDelete', '=', 0)
            ->field('er.id,er.resumeId,er.resumeCateId,c.name as resumeCateName,r.userId,u.avatar,r.name,r.phone,r.gender,r.age,r.workYear,r.education,
            r.skills,r.selfEvaluation,r.militaryTime,r.attendedTime,r.corps,r.exPosition,r.salary,
            r.nature,r.exCity,r.curStatus,r.arrivalTime,r.isSoldierPriority,r.createTime,r.createBy,r.updateTime,r.updateBy')
            ->select();
    }

    /**
     * 获取企业用户的简历列表 前端展示用 跟下载的简历列表字段做别名
     * @param $userId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEpResumeListApplyForShowPage($userId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('er')
            ->join('zb_resume r', 'er.resumeId=r.id', 'left')
            ->join('zb_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_resume_cate c', 'er.resumeCateId=c.id', 'left')
            ->where('er.userId', '=', $userId)
            ->where('er.source', '=', 1)
            ->where('er.isDelete', '=', 0)
            ->field('er.id,er.resumeId,r.name,r.gender,r.age,r.workYear,r.education,r.exPosition,r.salary,
            r.curStatus,er.source')
            ->paginate(null, false, $config);
    }


    public function getEpResumeListApplyByCate($userId, $resumeCateId)
    {
        return $this->alias('er')
            ->join('zb_resume r', 'er.resumeId=r.id', 'left')
            ->join('zb_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_resume_cate c', 'er.resumeCateId=c.id', 'left')
            ->where('er.userId', '=', $userId)
            ->where('er.source', '=', 1)
            ->where('er.resumeCateId', '=', $resumeCateId)
            ->where('er.isDelete', '=', 0)
            ->field('er.id,er.resumeId,er.resumeCateId,c.name as resumeCateName,r.userId,u.avatar,r.name,r.phone,r.gender,r.age,r.workYear,r.education,
            r.skills,r.selfEvaluation,r.militaryTime,r.attendedTime,r.corps,r.exPosition,r.salary,
            r.nature,r.exCity,r.curStatus,r.arrivalTime,r.isSoldierPriority,r.createTime,r.createBy,r.updateTime,r.updateBy')
            ->select();
    }

    public function getDownResumeListByUserIdWithCate($userId, $resumeCateId)
    {
        return $this->where('userId', '=', $userId)
            ->where('resumeCateId', '=', $resumeCateId)
            ->where('source', '=', 2)
            ->where('isDelete', '=', 0)
            ->select();
    }

}