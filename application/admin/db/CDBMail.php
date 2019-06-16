<?php

namespace app\admin\db;

use app\admin\common\Util;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class CDBMail
{

    /*
     * 取得已发送的邮件的日志文件
     * @param $dir 目录路径
     * */

    public function getMailByFileArray($fileArray)
    {
        $mail = array();
        foreach ($fileArray as $file) {
            $position = strrchr($file, '/');

            if ($position == '/mailLogger.log') {
                array_push($mail, $file);
            }
        }
        return $mail;
    }

    /*
     * 通过目录获取已经发送的邮件记录
     * */
    public function getMailContents($fileArr, $serverId, $createBy)
    {
        $files = $this->getMailByFileArray($fileArr);
        $data = array();
        foreach ($files as $file) {
            $fileContent = file_get_contents($file);

            $fileArr = explode('[] []', $fileContent);
            array_pop($fileArr);
            foreach ($fileArr as $v) {
                $v = trim($v);
                $position = stripos($v, ']');
                $sent_time = substr($v, 1, $position - 1);

                $content = substr($v, $position + 1);
                $content = trim($content);

                $arr = [
                    'sent_time' => $sent_time,
                    'content' => $content,
                    'create_time' => date('Y-m-d H:i:s', time()),
                    'create_by' => $createBy,
                    'server_id' => $serverId
                ];
                array_push($data, $arr);
            }
        }
        return $data;
    }

    /*
     * 通过目录或者文件名把记录添加进数据库
     *
     * */
    public function mailInsert($fileArr, $serverId, $createBy)
    {
        $data = $this->getMailContents($fileArr, $serverId, $createBy);
        $count = DB::name('mail')->insertAll($data);
        return $count;
    }

    /*
     * 分页查询已经发送的邮件记录
     *
     * */
    public function showAllMailListPaging($serverId, $perPage, $currentPage)
    {
        try {
            $msgList = DB::name('mail')
                ->where('server_id', 'eq', $serverId)
                ->order('id', 'DESC')
                ->paginate($perPage, false, ['page' => $currentPage]);
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        return $msgList;
    }

    /*
     * 查询所有已发送的邮件记录列表
     * */
    public function showAllMailList($serverId)
    {
        try {
            $msgList = DB::name('mail')
                ->where('server_id', 'eq', $serverId)
                ->order('id', 'DESC')->select();
            return $msgList;
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
      * 通过目录或者文件名 找到需要更新的数据 并添加到数据库中
      * @param $file 文件名或者目录名
      *
      * */
    public function updateMail($fileArr, $serverId, $createBy)
    {
        $sentTime = DB::name('mail')
            ->where('server_id', 'eq', $serverId)
            ->order('id', 'DESC')
            ->value('sent_time');
        if ($sentTime == null) {
            $count = $this->mailInsert($fileArr, $serverId, $createBy);
            return $count;
        }
        $data = $this->getMailContents($fileArr, $serverId, $createBy);
        $sum = 0;
        foreach ($data as $v) {
            if ($v['sent_time'] <= $sentTime) continue;
            if ($v['sent_time'] > $sentTime) {
                $count = DB::name('mail')->insert($v);
                $sum += $count;
            }
        }
        return $sum;
    }

}