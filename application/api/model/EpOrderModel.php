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
     * 获取用户是否结果该职位订单  详情
     * @param $positionId
     * @param $userId
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function verifyUserRecOrderDetail($positionId, $userId)
    {
        return $this->where('positionId', '=', $positionId)
            ->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->find();
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
        $res = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->join('zb_enterprise_user eu', 'o.userId=eu.id', 'left')
            ->where('o.orderId','=',$orderId)
            ->where('o.isDelete', '=', 0)
            ->field('o.id,o.orderId,o.userId,
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.entryNum,o.income,
            o.recOrderYear,o.recOrderMonth,
            o.createTime,o.createBy,o.updateTime,o.updateBy')
            ->find();
        return $res;
    }

    /**
     * 员工获取订单列表
     * @param $userId
     * @param $isFinish
     * @param $recOrderYear
     * @param $recOrderMonth
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderListWithOrderDateWithEmUser($userId, $isFinish, $recOrderYear, $recOrderMonth)
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
            ->where('p.endTime', $con, time())
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.userId', '=', $userId)
            ->where('o.isDelete', '=', 0)
            ->field('o.id,o.orderId,o.userId,
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.entryNum,o.income,
            o.recOrderYear,o.recOrderMonth,
            o.createTime,o.createBy,o.updateTime,o.updateBy')
            ->select();
        return $res;
    }

    /**
     * 企业用户获取订单列表
     * @param $epId
     * @param $isFinish
     * @param $recOrderYear
     * @param $recOrderMonth
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderListWithOrderDateWithEpUser($epId, $isFinish, $recOrderYear, $recOrderMonth)
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
            ->where('p.endTime', $con, time())
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.epId', '=', $epId)
            ->where('o.isDelete', '=', 0)
            ->field('o.id,o.orderId,o.userId,
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.entryNum,o.income,
            o.recOrderYear,o.recOrderMonth,
            o.createTime,o.createBy,o.updateTime,o.updateBy')
            ->select();
        return $res;
    }

    public function getOrderInfoByMonthWithEpUser($recOrderYear, $recOrderMonth, $epId,$isFinish)
    {
        if ($isFinish == 1) {
            $con = '<';
        }
        if ($isFinish == 0) {
            $con = '>';
        }
        $entryNumMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.epId', '=', $epId)
            ->where('p.endTime',$con,$isFinish)
            ->where('o.isDelete','=',0)
            ->sum('o.entryNum');
        $incomeMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.epId', '=', $epId)
            ->where('p.endTime',$con,$isFinish)
            ->where('o.isDelete','=',0)
            ->sum('income');
        $orderNumMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.epId', '=', $epId)
            ->where('p.endTime',$con,$isFinish)
            ->where('o.isDelete','=',0)
            ->count();
        $incomeTotal = $this->where('epId', '=', $epId)
            ->where('isDelete','=',0)
            ->sum('income');
        $orderNum  = $this->where('epId', '=', $epId)
            ->where('isDelete','=',0)
            ->count();
        return [$entryNumMonth, $incomeMonth,$orderNumMonth,$incomeTotal,$orderNum];
    }

    public function getOrderInfoByMonthWithEmUser($recOrderYear, $recOrderMonth, $userId,$isFinish)
    {
        if ($isFinish == 1) {
            $con = '<';
        }
        if ($isFinish == 0) {
            $con = '>';
        }
        $entryNumMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.userId', '=', $userId)
            ->where('p.endTime',$con,$isFinish)
            ->where('o.isDelete','=',0)
            ->sum('o.entryNum');
        $incomeMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.userId', '=', $userId)
            ->where('p.endTime',$con,$isFinish)
            ->where('o.isDelete','=',0)
            ->sum('income');
        $orderNumMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.recOrderYear', '=', $recOrderYear)
            ->where('o.recOrderMonth', '=', $recOrderMonth)
            ->where('o.userId', '=', $userId)
            ->where('p.endTime',$con,$isFinish)
            ->where('o.isDelete','=',0)
            ->count();
        $incomeTotal = $this->where('userId', '=', $userId)
            ->where('isDelete','=',0)
            ->sum('income');
        $orderNum  = $this->where('userId', '=', $userId)
            ->where('isDelete','=',0)
            ->count();
        return [$entryNumMonth, $incomeMonth,$orderNumMonth,$incomeTotal,$orderNum];
    }

    /**
     * 获取员工本月的接单信息
     * @param $userId
     * @return array
     * @throws \think\Exception
     */
    public function getOrderInfoByMonthWithEmUserNowMonth($userId)
    {
        $entryNumMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.userId', '=', $userId)
            ->where('o.isDelete','=',0)
            ->sum('o.entryNum');
        $incomeMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.userId', '=', $userId)
            ->where('o.isDelete','=',0)
            ->sum('income');
        $orderNumMonth = $this->alias('o')
            ->join('zb_position_management p', 'o.positionId=p.id', 'left')
            ->where('o.userId', '=', $userId)
            ->where('o.isDelete','=',0)
            ->count();

        return [$entryNumMonth, $incomeMonth,$orderNumMonth];
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
            ->where('p.endTime', $con, time())
            ->where('o.userId', '=', $userId)
            ->where('o.isDelete', '=', 0)
            ->field('DATE_FORMAT(o.createTime, "%Y-%m") as orderDate,o.orderId,o.userId,eu.orderNum,eu.incomeTotal,
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.entryNum,o.income,
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
            eu.name,o.positionId,p.name as positionName,p.unitPrice,o.applyNum,o.interviewNum,o.entryNum,o.income,
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