<?php

namespace app\api\model;

use think\Model;

class ResumeModel extends Model
{
    protected $name = 'resume';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function checkUserHasCreateResume($userId)
    {
        $count = $this->where('isDelete', '=', 0)->where('userId', '=', $userId)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserResume($userId)
    {
        return $this->alias('r')
            ->join('zb_user u','r.userId=u.id','left')
            ->where('r.userId','=',$userId)
            ->where('r.isDelete','=',0)
            ->field('r.id,r.userId,u.avatar,r.name,r.phone,r.gender,r.age,r.workYear,r.education')
            ->find();
//        return $this->where('isDelete','=',0)->where('userId','=',$userId)->find();
    }

    public function edit($resumeId, $data)
    {
        return $this->where('id', '=', $resumeId)
            ->where('isDelete', '=', 0)
            ->update($data);
    }

    public function del($resumeId, $userId)
    {
        return $this->where('isDelete', '=', 0)
            ->where('id', '=', $resumeId)
            ->where('createBy', '=', $userId)
            ->update(['isDelete' => 1]);
    }

    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('isDelete', '=', 0)
            ->paginate(null, false, $config);
    }

    public function getByUserId($userId)
    {
        return $this->where('createBy', '=', $userId)
            ->where('isDelete', '=', 0)
            ->select();
    }

    public function getDetail($resumeId)
    {
        return $this->where('isDelete', '=', 0)->where('id', '=', $resumeId)->find();
    }
}