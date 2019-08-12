<?php

namespace app\api\model;

use think\Model;

class EpResumeModel extends Model
{
    protected $table = 'zb_enterprise_resume';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

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
     * 获取
     * @param $userId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDownResumeListByUserId($userId)
    {
        return $this->where('userId', '=', $userId)
            ->where('source','=',2)
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
            ->where('er.userId', '=', $userId)
            ->where('er.source', '=', 1)
            ->where('er.isDelete', '=', 0)
            ->field('r.id,r.userId,u.avatar,r.name,r.phone,r.gender,r.age,r.workYear,r.education,
            r.skills,r.selfEvaluation,r.militaryTime,r.attendedTime,r.corps,r.exPosition,r.salary,
            r.nature,r.exCity,r.curStatus,r.arrivalTime,r.isSoldierPriority,r.createTime,r.createBy,r.updateTime,r.updateBy')
            ->select();
    }

}