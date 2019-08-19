<?php

namespace app\api\model;

use think\Model;

class EpOrderApplyModel extends Model
{
    protected $table = 'zb_enterprise_order_apply';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getApplyListByOrderId($orderId)
    {
        return $this->alias('py')
            ->join('zb_user u', 'py.applyUserId=u.id', 'left')
            ->join('zb_enterprise_user eu', 'py.shareUserId=eu.id', 'left')
            ->where('py.orderId', '=', $orderId)
            ->where('py.isDelete', '=', 0)
            ->field('py.id,py.orderId,py.resumeId,py.shareUserId,eu.name as shareUserName,py.applyUserId,
            u.name as applyUserName,u.phone as applyUserPhone,
            py.interviewStatus,py.entryStatus,py.createTime,py.updateTime')
            ->select();
    }

    /**
     * 检查用户是否已经申请该订单
     * @param $orderId
     * @param $userId
     * @return bool
     * @throws \think\Exception
     */
    public function checkUserHasApply($orderId, $applyUserId)
    {
        $count = $this->where('orderId', '=', $orderId)
            ->where('applyUserId', '=', $applyUserId)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据订单id分页获取申请列表
     * @param $orderId
     * @param $pageIndex
     * @param $pageSize
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getApplyListByOrderIdPage($orderId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('py')
            ->join('zb_user u', 'py.applyUserId=u.id', 'left')
            ->join('zb_enterprise_user eu', 'py.shareUserId=eu.id', 'left')
            ->where('py.orderId', '=', $orderId)
            ->where('py.isDelete', '=', 0)
            ->field('py.id,py.orderId,py.resumeId,py.shareUserId,eu.name as shareUserName,py.applyUserId,
            u.name as applyUserName,u.phone as applyUserPhone,
            py.interviewStatus,py.entryStatus,py.createTime,py.updateTime')
            ->paginate(null, false, $config);
    }

    public function updateInterviewStatus($orderId, $interviewStatus)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->update(
                [
                    'interviewStatus' => $interviewStatus,
                    'updateTime' => currentTime()
                ]
            );
    }

    public function updateEntryStatus($orderId, $entryStatus)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->update(
                [
                    'entryStatus' => $entryStatus,
                    'updateTime' => currentTime()
                ]
            );
    }

    /**
     * 删除员工时 清空员工的订单申请表中的信息
     * @param $emUserId
     * @return EpOrderApplyModel
     */
    public function delEmUserOrderApplyInfo($emUserId)
    {
        return $this->where('shareUserId', '=', $emUserId)
            ->where('isDelete', '=', 0)
            ->update(['isDelete' => 1]);
    }


}