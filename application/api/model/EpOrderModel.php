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

}