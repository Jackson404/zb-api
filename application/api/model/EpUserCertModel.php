<?php

namespace app\api\model;

use think\Exception;
use think\Model;

class EpUserCertModel extends Model
{
    protected $table = 'zb_enterprise_user_cert';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function addCert($data, $userId)
    {
        $this->startTrans();
        try {
            $id = $this->insertGetId($data);
            if ($id > 0) {
                $updateRow = $this->table('zb_enterprise_user')
                    ->where('id', '=', $userId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'isReview' => 1,
                            'updateTime' => currentTime()
                        ]
                    );
                if ($updateRow > 0) {
                    $this->commit();
                    return $id;
                } else {
                    $this->rollback();
                    return -1;
                }
            } else {
                $this->rollback();
                return -2;
            }
        } catch (Exception $e) {
//            var_dump($e->getMessage());
            $this->rollback();
            return -3;
        }
    }

    /**
     * 检查审核的状态 0在待审核  -1拒绝  1 通过
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public function checkCertStatus($userId)
    {
        $count = $this->where('userId', '=', $userId)
            ->where('pass', '=', 0)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检测公司名字是否存在
     * @param $companyName
     * @return bool
     * @throws Exception
     */
    public function checkCompanyNameExist($companyName)
    {
        $count = $this->where('companyName', '=', $companyName)
            ->where('isDelete', '=', 0)
            ->where('pass', '=', 1)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取认证详情
     * @param $certId
     * @return array
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDetail($certId)
    {
        $res = $this->where('isDelete', '=', 0)
            ->where('id', '=', $certId)
            ->find();
        return $res->toArray();
    }

    /**
     * 修改认证状态
     * @param $certId
     * @param $pass
     * @param $epUserId
     * @param $companyName
     * @param $type
     * @return EpUserCertModel|int
     * @throws \think\exception\PDOException
     */
    public function updateCertStatus($certId, $pass, $epUserId, $companyName, $type)
    {

        $this->startTrans();
        try {
            $updateRows = $this->where('userId', '=', $epUserId)
                ->where('isDelete', '=', 0)
                ->update(
                    ['pass' => -1, 'updateTime' => currentTime()]
                );
            $updateRow = $this->where('id', '=', $certId)
                ->where('isDelete', '=', 0)
                ->update(
                    ['pass' => $pass, 'updateTime' => currentTime()]
                );
            if ($updateRow == 0) {
                $this->rollback();
                return -1;
            }

            if ($pass == 1) {
                $up = $this->table('zb_enterprise_user')
                    ->where('id', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'type' => $type,
                            'companyName' => $companyName,
                            'isReview' => 2,
                            'updateTime' => currentTime()
                        ]
                    );
            }
            if ($pass == -1) {
                $up = $this->table('zb_enterprise_user')
                    ->where('id', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'type' => $type,
                            'isReview' => -1,
                            'updateTime' => currentTime()
                        ]
                    );
            }

            if ($pass == 0) {
                $up = $this->table('zb_enterprise_user')
                    ->where('id', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'type' => $type,
                            'isReview' => 1,
                            'updateTime' => currentTime()
                        ]
                    );
            }

            if ($up > 0) {
                $this->commit();
                return $updateRow;
            } else {
                $this->rollback();
                return -2;
            }


        } catch (Exception $e) {
            $this->rollback();
            return -3;
        }

    }

    /**
     * 获取企业的员工申请列表
     * @param $epUserId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEmApplyListByEpId($epUserId)
    {

        return $this->where('epId', '=', $epUserId)
            ->where('isDelete', '=', 0)
            ->select();
    }

    /**
     * 判断是否是该企业下的员工
     * @param $emCertId
     * @param $epUserId
     * @return bool
     * @throws Exception
     */
    public function verifyEmCertIdAndEpUserId($emCertId, $epUserId)
    {
        $count = $this->where('id', '=', $emCertId)
            ->where('epId', '=', $epUserId)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateGroupIdByEpUser($emCertId, $epUserId, $groupId)
    {
        $data = [
            'groupId' => $groupId,
            'updateBy' => $epUserId,
            'updateTime' => currentTime()
        ];
        return $this->where('id', '=', $emCertId)
            ->where('isDelete', '=', 0)
            ->update($data);
    }
}