<?php

namespace app\api\model;

use think\Model;

class EpOrderModel extends Model
{
    protected $table = 'zb_enterprise_order';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    /**
     * 添加接单
     * @param $data
     * @return int|string
     */
    public function add($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * 检查用户是否已经接单
     * @param $positionId
     * @param $userId
     * @return bool
     * @throws \think\Exception
     */
    public function checkUserRecOrder($positionId, $userId)
    {
        $count = $this->where('positionId', '=', $positionId)
            ->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 分页获取订单列表
     * @param $userId
     * @param $pageIndex
     * @param $pageSize
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getUserRecOrdersPage($userId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];

        return $this->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->paginate(null, false, $config);

    }

    /**
     * 根据订单id获取详情
     * @param $orderId
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDetailByOrderId($orderId)
    {
        $res = $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->find();
        return $res;
    }

    public function getOrderList($userId, $isFinish)
    {
        if ($isFinish == 1) {
            $con = '<';
        }
        if ($isFinish == 0) {
            $con = '>';
        }
        $res = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->join('zb_enterprise_user eu', 'o.userId=eu.id', 'left')
            ->where('p.interviewTime', $con, time())
            ->where('o.userId', '=', $userId)
            ->where('o.isDelete', '=', 0)
            ->field('DATE_FORMAT(o.createTime, "%Y-%m") as orderDate,o.orderId,o.userId,eu.orderNum,eu.incomeTotal,
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.emNum,o.income,
            o.createTime,o.createBy,o.updateTime,o.updateBy')
            ->select();
        return $res;
    }

    /**
     * 后台用户获取所有的订单列表
     * @param $userId
     * @param $isFinish
     * @param $pageIndex
     * @param $pageSize
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getOrderListByPageAdmin($isFinish, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        if ($isFinish == 1) {
            $con = '<';
        }
        if ($isFinish == 0) {
            $con = '>';
        }
        $res = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->join('zb_enterprise_user eu', 'o.userId=eu.id', 'left')
            ->where('p.interviewTime', $con, time())
            ->where('o.isDelete', '=', 0)
            ->field('DATE_FORMAT(o.createTime, "%Y-%m") as orderDate,o.orderId,o.userId,eu.orderNum,eu.incomeTotal,
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.emNum,o.income,
            o.createTime,o.createBy,o.updateTime,o.updateBy')
            ->paginate(null, false, $config);
        return $res;
    }

    public function incApplyNum($orderId, $inc)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setInc('applyNum', $inc);
    }

    public function decApplyNum($orderId, $dec)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setDec('applyNum', $dec);
    }

    public function incEntryNum($orderId, $inc)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setInc('entryNum', $inc);
    }

    public function decEntryNum($orderId, $dec)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setDec('entryNum', $dec);
    }

    public function incInterviewNum($orderId, $inc)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setInc('interviewNum', $inc);
    }

    public function decInterviewNum($orderId, $dec)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setDec('interviewNum', $dec);
    }

    public function incIncome($orderId, $inc)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setInc('income', $inc);
    }

    public function decIncome($orderId, $dec)
    {
        return $this->where('orderId', '=', $orderId)
            ->where('isDelete', '=', 0)
            ->setDec('income', $dec);
    }

}