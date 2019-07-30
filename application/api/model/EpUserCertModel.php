<?php

namespace app\api\model;

use think\Exception;
use think\Model;

class EpUserCertModel extends Model
{
    protected $table = 'zb_enterprise_user_cert';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function addCert($data, $type, $userId)
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
                            'type' => $type
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

    public function checkCertStatus($userId)
    {
        $count = $this->where('userId', '=', $userId)
            ->where('isDelete', '=', 0)
            ->where('pass', '=', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getDetail($certId)
    {
        $res = $this->where('isDelete', '=', 0)
            ->where('id', '=', $certId)
            ->find();
        return $res->toArray();
    }

    public function updateCertStatus($certId, $pass, $epUserId)
    {

        $this->startTrans();
        try {
            $updateRow = $this->where('id', '=', $certId)
                ->where('isDelete', '=', 0)
                ->update(
                    ['pass' => $pass, 'updateTime' => currentTime()]
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
                        'isReview' => 2,
                        'updateTime' => currentTime()
                    ]
                );
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
}