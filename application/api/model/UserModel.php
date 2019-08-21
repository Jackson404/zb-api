<?php

namespace app\api\model;

use think\Model;

class UserModel extends Model
{
    protected $name = 'user';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function checkPhoneExist($phone)
    {
        $count = $this->where('isDelete', '=', 0)->where('phone', '=', $phone)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkMiniOpenIdExist($miniOpenId)
    {
        $count = $this->where('isDelete', '=', 0)->where('mini_openid', '=', $miniOpenId)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkPhoneHasBind($phone, $miniOpenId)
    {
        $res = $this->where('isDelete', '=', 0)->where('phone', '=', $phone)->find();

        if ($res['mini_openid'] != '') {
            if ($res['mini_openid'] == $miniOpenId) {
                return -1; //绑定过了
            } else {
                return -2; //绑定其他的了
            }
        } else {
            return -3; //未绑定
        }
    }

    public function checkMiniOpenIdHasBind($miniOpenId)
    {
        $res = $this->where('isDelete', '=', 0)->where('mini_openid', '=', $miniOpenId)->find();

        if (empty($res)) {
            //no bind
            return false;
        } else {
            return true;
        }

    }

    public function checkMiniOpenIdBindPhone($miniOpenId, $phone)
    {
        $count = $this->where('isDelete', '=', 0)
            ->where('mini_openid', '=', $miniOpenId)
            ->where('phone', '=', $phone)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 手机号已经注册过的情况 绑定小程序
     * @param $miniOpenId
     * @param $phone
     * @return UserModel
     */
    public function bindMiniOpenIdAndPhone($miniOpenId, $phone)
    {
        $data = [
            'mini_openid' => $miniOpenId,
            'updateTime' => currentTime()
        ];
        return $this->where('phone', '=', $phone)
            ->where('isDelete', '=', 0)
            ->update($data);
    }

    public function getByPhone($phone)
    {
        return $this->where('phone', '=', $phone)->where('isDelete', '=', 0)->find();
    }

    public function getByMiniOpenId($miniOpenId)
    {
        return $this->where('mini_openid', '=', $miniOpenId)->where('isDelete', '=', 0)->find();
    }

    public function getUserInfoByUserId($userId)
    {
        return $this->where('id', '=', $userId)
            ->where('isDelete', '=', 0)
            ->field('id,avatar,name,phone')
            ->find();
    }
}