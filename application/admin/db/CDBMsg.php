<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:08
 */

namespace app\admin\db;

use app\admin\common\Util;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class CDBMsg
{
    /*
    * 取得已发送的信息的日志文件
    * @param $fileArray 目录路径
    * */

    public function getMsgByFileArray($fileArray)
    {
        $msg = array();
        foreach ($fileArray as $file) {
            $position = strrchr($file, '/');

            if ($position == '/msgLogger.log') {
                array_push($msg, $file);
            }
        }
        return $msg;
    }


    /*
     * 通过目录　获取文件内容
     * @param $dir 目录路径
     * @param $createBy 数据库记录的创建者
     * */
    public function getMsgContents($fileArray, $serverId, $createBy)
    {
        $files = $this->getMsgByFileArray($fileArray);
        $data = array();
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $arr = explode('[]', $content);
            array_pop($arr);

            foreach ($arr as $v) {
                $v = trim($v);
                $position = stripos($v, ']');
                $sentTime = substr($v, 1, $position - 1);

                $content = strchr($v, '{');

                $contentTr = trim($content, "{}");

                $contentArr = explode(',', $contentTr);

                if ($contentArr[0] != '') {
                    $companyArr = explode(':', $contentArr[0]);

                    if ($companyArr[0] === '"company"') {
                        $company = $companyArr[1];

                        $company = trim($company, '""');
                    }

                }
                $sqlArr = [
                    'sent_time' => isset($sentTime) ? $sentTime : '0',
                    'content' => "$content",
                    'company' => isset($company) ? $company : '0',
                    'create_time' => date('Y-m-d H:i:s', time()),
                    'create_by' => $createBy,
                    'server_id' => $serverId
                ];
                array_push($data, $sqlArr);
            }
        }
        return $data;
    }


    /*
     * 把信息文件内容添加进数据库
     * @param $file 文件或者是目录
     * @param $createBy
     * */
    public function msgInsert($fileArray, $serverId, $createBy)
    {
        $data = $this->getMsgContents($fileArray, $serverId, $createBy);
        $count = DB::name('msg')->insertAll($data);
        return $count;
    }

    /*
     * 通过目录或者文件名 找到需要更新的数据 并添加到数据库中
     * @param $file 文件名或者目录名
     *
     * */
    public function updateMsg($fileArr, $serverId, $createBy)
    {
        $sentTime = DB::name('msg')->where('server_id', 'eq', $serverId)->order('id', 'DESC')->value('sent_time');
        if ($sentTime == null) {
            $count = $this->msgInsert($fileArr, $serverId, $createBy);
            return $count;
        }
        $data = $this->getMsgContents($fileArr, $serverId, $createBy);
        $sum = 0;
        foreach ($data as $v) {
            if ($v['sent_time'] < $sentTime) continue;
            if ($v['sent_time'] > $sentTime) {
                $count = DB::name('msg')->insert($v);
                $sum += $count;
            }
        }
        return $sum;
    }

    /*
     * 分页查询所有的已经发送的信息记录列表
     * @param $perPage 每页数量
     * @param $currentPage  当前页
     * */
    public function showAllMsgListPaging($serverId, $perPage, $currentPage)
    {
        try {
            $msgList = DB::name('msg as a')
                ->field(['a.id,a.content', 'a.company', 'a.sent_time', 'a.create_time', 'a.create_by', 'a.server_id', 'b.id' => 'companyId'])
                ->join(['familymanage_msg_company' => 'b'], 'a.company = b.company', 'LEFT')
                ->where('a.server_id', 'eq', $serverId)
                ->order('a.id', 'DESC')
                ->paginate($perPage, false, ['page' => $currentPage]);
            return $msgList;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /*
     * 查询所有已发送的信息记录列表
     * */
    public function showAllMsgList($serverId)
    {
        try {
            $msgList = DB::name('msg as a')
                ->field(['a.id,a.content', 'a.company', 'a.sent_time', 'a.create_time', 'a.create_by', 'a.server_id', 'b.id' => 'companyId'])
                ->join(['familymanage_msg_company' => 'b'], 'a.company = b.company', 'LEFT')
                ->where('a.server_id', 'eq', $serverId)
                ->order('a.id', 'DESC')
                ->select();
        } catch (DataNotFoundException $e) {
            Util::printResult($GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'], $e->getMessage());
            exit;
        } catch (ModelNotFoundException $e) {
            Util::printResult($GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'], $e->getMessage());
            exit;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        return $msgList;
    }

    /*
     * 添加公司名字到数据库
     * */
    public function insertCompany($company, $createBy)
    {
        $data = [
            'company' => $company,
            'create_time' => date('Y-m-d H:i:s', time()),
            'create_by' => $createBy
        ];
        $count = DB::name('msg_company')->insert($data);
        if ($count > 0) {
            return $count;
        }
        return false;
    }

    /*
     * 获取全部的公司类型
     * */
    public function showAllCompany()
    {
        try {
            $result = DB::name('msg_company')->select();
            return $result;
        } catch (DataNotFoundException $e) {
            Util::printResult($GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'], $e->getMessage());
            exit;
        } catch (ModelNotFoundException $e) {
            Util::printResult($GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'], $e->getMessage());
            exit;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /*
     * 根据公司名称获取信息记录列表
     * */
    public function showMsgListByCompanyIdPage($serverId, $companyId, $perPage, $currentPage)
    {
        $map = [
            'b.id' => $companyId,
            'a.server_id' => $serverId
        ];
        try {
            $msg_list = DB::name('msg as a')
                ->field(['a.id,a.content', 'a.company', 'a.sent_time', 'a.create_time', 'a.create_by', 'a.server_id', 'b.id' => 'companyId'])
                ->join(['familymanage_msg_company' => 'b'], 'a.company = b.company', 'INNER')
                ->where($map)
                ->order('a.id', 'DESC')
                ->paginate($perPage, false, ['page' => $currentPage]);
            return $msg_list;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
    }
}