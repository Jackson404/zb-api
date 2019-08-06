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
            $this->rollback();
            var_dump($e->getMessage());
            return -3;
        }
    }


    public function verifyByCompanyNameAndUserId($companyName, $userId)
    {
        $res = $this->where('companyName', '=', $companyName)
            ->where('userId', '=', $userId)
            ->where('pass', '=', 0)
            ->where('isDelete', '=', 0)
            ->find();
        return $res;
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
        return $res;
    }

    /**
     * 后台审核认证状态
     * @param $certId
     * @param $pass
     * @param $epUserId
     * @param $realname
     * @param $phone
     * @param $idCard
     * @param $idCardFrontPic
     * @param $idCardBackPic
     * @param $companyName
     * @param $companyAddr
     * @param $businessLic
     * @param $otherQuaLic
     * @param $type
     * @return EpUserCertModel|int
     * @throws \think\exception\PDOException
     */
    public function updateCertStatus($certId, $pass, $epUserId, $realname, $phone, $idCard, $idCardFrontPic, $idCardBackPic,
                                     $companyName, $companyAddr, $businessLic, $otherQuaLic, $type)
    {

        $this->startTrans();
        try {
            if ($pass == 1) {
                $this->where('userId', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        ['pass' => -1, 'isDelete' => 1]
                    );
                $updateRow = $this->where('id', '=', $certId)
                    ->update(
                        ['pass' => 1, 'isDelete' => 0, 'updateTime' => currentTime()]
                    );
                if ($updateRow == 0) {
                    $this->rollback();
                    return -1;
                }

                $up = $this->table('zb_enterprise_user')
                    ->where('id', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'realname' => $realname,
                            'realphone' => $phone,
                            'idCard' => $idCard,
                            'idCardFrontPic' => $idCardFrontPic,
                            'idCardBackPic' => $idCardBackPic,
                            'companyName' => $companyName,
                            'companyAddr' => $companyAddr,
                            'businessLic' => $businessLic,
                            'otherQuaLic' => $otherQuaLic,
                            'type' => $type,
                            'isReview' => 2,
                            'updateTime' => currentTime()
                        ]
                    );
            }
            if ($pass == -1) {
                $this->where('id', '=', $certId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        ['isDelete' => 1, 'pass' => -1]
                    );
                $up = $this->table('zb_enterprise_user')
                    ->where('id', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'realname' => '',
                            'realphone' => '',
                            'idCard' => '',
                            'idCardFrontPic' => '',
                            'idCardBackPic' => '',
                            'companyName' => '',
                            'companyAddr' => '',
                            'businessLic' => '',
                            'otherQuaLic' => '',
                            'type' => 0,
                            'isReview' => 0,
                            'updateTime' => currentTime()
                        ]
                    );
            }

            if ($pass == 0) {
                $this->where('id', '=', $certId)
                    ->update(
                        ['isDelete' => 0, 'pass' => 0]
                    );
                $up = $this->table('zb_enterprise_user')
                    ->where('id', '=', $epUserId)
                    ->where('isDelete', '=', 0)
                    ->update(
                        [
                            'realname' => '',
                            'realphone' => '',
                            'idCard' => '',
                            'idCardFrontPic' => '',
                            'idCardBackPic' => '',
                            'companyName' => '',
                            'companyAddr' => '',
                            'businessLic' => '',
                            'otherQuaLic' => '',
                            'type' => 0,
                            'isReview' => 0,
                            'updateTime' => currentTime()
                        ]
                    );
            }
            if ($up > 0) {
                $this->commit();
                return $up;
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

        return $this->where('pid', '=', $epUserId)
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
            ->where('pid', '=', $epUserId)
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