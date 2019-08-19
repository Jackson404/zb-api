<?php

namespace app\api\model;

use think\Exception;
use think\Model;

class EpUserCertModel extends Model
{
    protected $table = 'zb_enterprise_cert_review';
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


    /**
     * 测试用户是否正在审核
     * @param $companyName
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public function verifyByCompanyNameAndUserId($companyName, $userId)
    {
        $res = $this->where('companyName', '=', $companyName)
            ->where('userId', '=', $userId)
            ->where('pass', '=', 0)
            ->where('isDelete', '=', 0)
            ->count();
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 检查是否已经通过审核
     * @param $companyName
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public function verifyCompanyNameAndUserId($companyName, $userId)
    {
        $res = $this->where('companyName', '=', $companyName)
            ->where('userId', '=', $userId)
            ->where('pass', '=', 1)
            ->where('isDelete', '=', 0)
            ->count();
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查该企业是否已经认证过
     * @param $companyName
     * @return bool
     * @throws Exception
     */
    public function checkEpHasCert($companyName)
    {
        $count = $this->where('companyName', '=', $companyName)
            ->where('type', '=', 1)
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
        return $res;
    }

    /**
     * 企业审核员工
     * @param $epUserId
     * @param $certId
     * @param $pass
     * @param $emUserId
     * @param $companyName
     * @param $realname
     * @param $phone
     * @param $idCard
     * @param $idCardFrontPic
     * @param $idCardBackPic
     * @param $type
     * @return int|mixed
     * @throws \think\exception\PDOException
     */
    public function reviewEmByEp($certId, $pass, $emUserId, $companyName, $type)
    {

        $this->startTrans();
        try {
            if ($pass == 1) {
                $updateRow = $this->where('id', '=', $certId)
                    ->update(
                        ['pass' => 1, 'updateTime' => currentTime()]
                    );
                if ($updateRow == 0) {
                    $this->rollback();
                    return -1;
                }

                $x = $this->table('zb_company_management')
                    ->where('isDelete', '=', 0)
                    ->where('name', '=', $companyName)
                    ->find();
                $xData = $x->toArray();
                $companyId = $xData['id'];
                $certInsertGetId = $this->table('zb_enterprise_cert')->insertGetId(
                    [
                        'userId' => $emUserId,
                        'epId' => $companyId,
                        'reviewCertId' => $certId,
                        'type' => $type,
                        'createTime' => currentTime(),
                        'updateTime' => currentTime()
                    ]
                );
                if ($certInsertGetId == 0) {
                    $this->rollback();
                    return -1111;
                }
                $up = $this->table('zb_enterprise_user')->where('id', '=', $emUserId)->where('isDelete', '=', 0)
                    ->update(
                        [
                            'epId' => $companyId,
                            'isReview' => 2,
                            'type' => $type
                        ]
                    );
                if ($up == 0) {
                    $this->rollback();
                    return -11111;
                }
                $this->commit();
                return $up;
            }

            if ($pass == -1) {
                $updateRow = $this->where('id', '=', $certId)
                    ->update(
                        ['pass' => -1, 'updateTime' => currentTime()]
                    );
                if ($updateRow == 0) {
                    $this->rollback();
                    return -1;
                }
                $this->commit();
                return $updateRow;
            }

        } catch (Exception $e) {
            $this->rollback();
            return $e->getCode();
        }
    }

    /**
     * 正步后台审核企业
     * @param $adminUserId
     * @param $certId
     * @param $pass
     * @param $userId
     * @param $companyName
     * @param $companyAddr
     * @param $businessLic
     * @param $otherQuaLic
     * @param $type
     * @return EpUserCertModel|int|mixed
     * @throws \think\exception\PDOException
     */
    public function reviewEpByAdmin($adminUserId, $certId, $pass, $userId, $companyName, $companyAddr, $type)
    {
        $this->startTrans();
        try {
            if ($pass == 1) {
                $updateRow = $this->where('id', '=', $certId)
                    ->update(
                        ['pass' => 1, 'updateTime' => currentTime()]
                    );
                if ($updateRow == 0) {
                    $this->rollback();
                    return -1;
                }
                // 企业
                $x = $this->table('zb_company_management')
                    ->where('isDelete', '=', 0)
                    ->where('name', '=', $companyName)
                    ->find();
                if ($x != null) {
                    $xData = $x->toArray();
                    $companyId = $xData['id'];
                    $companyData = [
                        'id' => $companyId,
                        'name' => $companyName,
                        'address' => $companyAddr,
                        'isCert' => 1,
                        'createTime' => currentTime(),
                        'createBy' => $adminUserId,
                        'updateTime' => currentTime(),
                        'updateBy' => $adminUserId
                    ];

                    $updateRow = $this->table('zb_company_management')->update($companyData);
                    if ($updateRow == 0) {
                        $this->rollback();
                        return -111;
                    }
                } else {
                    $companyData = [
                        'name' => $companyName,
                        'address' => $companyAddr,
                        'profile' => '',
                        'isCert' => 1,
                        'createTime' => currentTime(),
                        'createBy' => $adminUserId,
                        'updateTime' => currentTime(),
                        'updateBy' => $adminUserId
                    ];
                    $companyId = $this->table('zb_company_management')->insertGetId($companyData);
                    if ($companyId == 0) {
                        $this->rollback();
                        return -11;
                    }
                }

                $certInsertGetId = $this->table('zb_enterprise_cert')->insertGetId(
                    [
                        'userId' => $userId,
                        'epId' => $companyId,
                        'reviewCertId' => $certId,
                        'type' => $type,
                        'createTime' => currentTime(),
                        'updateTime' => currentTime()
                    ]
                );
                if ($certInsertGetId == 0) {
                    $this->rollback();
                    return -1111;
                }

                $up = $this->table('zb_enterprise_user')->where('id', '=', $userId)->where('isDelete', '=', 0)
                    ->update(
                        [
                            'epId' => $companyId,
                            'isReview' => 2,
                            'type' => $type
                        ]
                    );
                if ($up == 0) {
                    $this->rollback();
                    return -11111;
                }
                $this->commit();
                return $up;
            }

            if ($pass == -1) {
                $updateRow = $this->where('id', '=', $certId)
                    ->update(
                        ['pass' => -1, 'updateTime' => currentTime()]
                    );
                if ($updateRow == 0) {
                    $this->rollback();
                    return -1;
                }
                $this->commit();
                return $updateRow;
            }


        } catch (Exception $e) {
            $this->rollback();
            var_dump($e->getMessage());
            return -2;
        }
    }

    /**
     * 获取企业的员工申请列表
     * @param $epId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEmApplyListByEpId($epId)
    {
        return $this->alias('r')
            ->join('zb_enterprise_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_em_group g', 'r.groupId=g.groupId', 'left')
            ->where('r.applyEpId', '=', $epId)
            ->where('r.isDelete', '=', 0)
            ->field('r.id,r.userId,u.name as username,r.realphone,r.applyEpId,r.groupId,g.name as groupName,r.pass,r.createTime')
            ->select();

//        return $this->alias('r')
//            ->join('zb_enterprise_user u','r.userId=u.id','left')
//            ->join('zb_enterprise_em_group g','r.groupId=g.groupId','left')
//            ->where('r.applyEpId','=',$epId)
//            ->where('r.isDelete','=',0)
//            ->field('r.id,r.userId,u.name as username,r.applyEpId,r.groupId,g.name as groupName,
//            r.realname,r.realphone,r.idCard,r.idCardFrontPic,r.idCardBackPic,r.companyName,
//            r.pass,r.createTime,r.createBy,r.updateTime,r.updateBy')
//            ->select();
    }

    public function getEmApplyListByEpIdPage($epId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('r')
            ->join('zb_enterprise_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_em_group g', 'r.groupId=g.groupId', 'left')
            ->where('r.applyEpId', '=', $epId)
            ->where('r.isDelete', '=', 0)
            ->field('r.id,r.userId,u.name as username,r.realphone,r.applyEpId,r.groupId,g.name as groupName,r.pass,r.createTime')
            ->paginate(null, false, $config);
    }

    /**
     * 获取待审核的员工列表
     * @param $epId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getReviewEmApplyListByEpId($epId)
    {
        return $this->alias('r')
            ->join('zb_enterprise_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_em_group g', 'r.groupId=g.groupId', 'left')
            ->where('r.applyEpId', '=', $epId)
            ->where('r.pass', '=', 0)
            ->where('r.isDelete', '=', 0)
            ->field('r.id,r.userId,u.name as username,r.realphone,r.applyEpId,r.groupId,g.name as groupName,r.pass,r.createTime')
            ->select();
    }

    public function getReviewEmApplyListByEpIdPage($epId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('r')
            ->join('zb_enterprise_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_em_group g', 'r.groupId=g.groupId', 'left')
            ->where('r.applyEpId', '=', $epId)
            ->where('r.pass', '=', 0)
            ->where('r.isDelete', '=', 0)
            ->field('r.id,r.userId,u.name as username,r.realphone,r.applyEpId,r.groupId,g.name as groupName,r.pass,r.createTime')
            ->paginate(null, false, $config);
    }

    public function getEmNumByEpId($epId, $type)
    {
        if ($type == 1) {
            $count = $this
                ->whereOr('pass', '=', 0)
                ->whereOr('pass', '=', 1)
                ->where('isDelete', '=', 0)
                ->where('applyEpId', '=', $epId)
                ->count();
        }
        if ($type == 2) {
            $count = $this->where('applyEpId', '=', $epId)
                ->where('pass', '=', 0)
                ->where('isDelete', '=', 0)
                ->count();
        }
        return $count;
    }


    /**
     * 获取企业的员工申请列表 根据组别
     * @param $groupId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEmApplyListByGroupId($groupId)
    {
        return $this->alias('r')
            ->join('zb_enterprise_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_em_group g', 'r.groupId=g.groupId', 'left')
            ->where('r.groupId', '=', $groupId)
            ->where('r.isDelete', '=', 0)
            ->field('r.id,r.userId,u.name as username,r.realphone,r.applyEpId,r.groupId,g.name as groupName,r.pass,r.createTime')
            ->select();
    }

    public function getEmApplyListByGroupIdPage($epId,$groupId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('r')
            ->join('zb_enterprise_user u', 'r.userId=u.id', 'left')
            ->join('zb_enterprise_em_group g', 'r.groupId=g.groupId', 'left')
            ->where('r.applyEpId','=',$epId)
            ->where('r.groupId', '=', $groupId)
            ->where('r.isDelete', '=', 0)
            ->field('r.id,r.userId,u.name as username,r.realphone,r.applyEpId,r.groupId,g.name as groupName,r.pass,r.createTime')
            ->paginate(null, false, $config);
    }


    /**
     * 判断是否是该企业下的员工
     * @param $emCertId
     * @param $epUserId
     * @return bool
     * @throws Exception
     */
    public function verifyApplyEpIdAndCertId($epId, $certId)
    {
        $count = $this->where('id', '=', $certId)
            ->where('applyEpId', '=', $epId)
            ->where('isDelete', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateGroupIdByEpUser($certId, $epUserId, $groupId)
    {
        $data = [
            'groupId' => $groupId,
            'updateBy' => $epUserId,
            'updateTime' => currentTime()
        ];
        return $this->where('id', '=', $certId)
            ->where('isDelete', '=', 0)
            ->update($data);
    }
}