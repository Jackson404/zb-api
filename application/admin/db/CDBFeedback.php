<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 18:07
 */

namespace app\admin\db;

use app\admin\common\Util;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;

class CDBFeedback
{
    /*
     * 把数据库服务器地址 名字添加进数据库、
     * @param $serverName 服务器名字
     * @param $serverIp   服务器Ip
     * @param $createBy   创建者
     * */
    public function insertServers($serverName, $serverIp, $createBy)
    {
        $data = [
            'server_name' => $serverName,
            'server_ip' => $serverIp,
            'create_by' => $createBy,
            'create_time' => date('Y-m-d H:i:s', time())
        ];
        $count = DB::name('servers')->insert($data);
        if ($count > 0) {
            return $count;
        }
        return false;
    }

    /*
     * 通过数据库服务器ID 获取服务器IP
     * @param $serverId 服务器Id
     *
     * */
    public function getServerIpByServerId($serverId)
    {
        $serverIp = DB::name('servers')->where('id', 'eq', $serverId)->value('server_ip');
        if ($serverIp) {
            return $serverIp;
        }
    }

    /*
     * 通过数据库服务器IP地址链接数据库
     * @param $serverIp 服务Ip地址
     *
     * */
    public function connectDbByServerIp($serverIp)
    {
        $config = [

            'type' => 'mysql',
            // 服务器地址
            'hostname' => $serverIp,
            // 数据库名
            'database' => 'family',
            // 用户名
            'username' => 'family',
            // 密码
            'password' => 'Flare1111',
            // 端口
            'hostport' => '3306',
            // 数据库编码默认采用utf8
            'charset' => 'utf8',
            // 数据库表前缀
            'prefix' => 'gener_',
            // 数据库调试模式
            'debug' => true,
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 数据集返回类型
            'resultset_type' => 'array',
            // 自动写入时间戳字段
            'auto_timestamp' => false,
            // 时间字段取出后的默认时间格式
            'datetime_format' => 'Y-m-d H:i:s',
            // 是否需要进行SQL性能分析
            'sql_explain' => false,

        ];
        try {
            $DB = DB::connect($config, true);
            return $DB;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /*
     * 通过serverId 获得反馈信息
     * @param $serverId 服务IP
     * */
    public function getFeedbackMsg($serverId)
    {
        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $result = $DB->name('feedback')->order('id', 'DESC')->select();
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

        $data = array();
        foreach ($result as $v) {
            $problemType = $v['problem_type'];
            if ($problemType == 1) {
                $v['problem_name'] = '改善建议';

            } elseif ($problemType == 2) {
                $v['problem_name'] = '卡顿';

            } elseif ($problemType == 3) {
                $v['problem_name'] = '闪退';

            } elseif ($problemType == 9) {
                $v['problem_name'] = '其他建议';
            }
            $photoArr = explode(',', trim($v['photo'], '[]'));
            $photoArray = [];
            foreach ($photoArr as $photo) {
                $photo = trim($photo, '""');
                array_push($photoArray, $photo);
            }
            $v['photo'] = $photoArray;

            array_push($data, $v);

        }
        return $data;
    }

    /*
     * 分页获取反馈信息
     * @param $serverIp
     * @param $perPage 每页数量
     * @param $currentPage 当前页
     *
     * */
    public function getFeedbackMsgPage($serverId, $perPage, $currentPage)
    {

        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $result = $DB->name('feedback')->order('id', 'DESC')->paginate($perPage, false, ['page' => $currentPage]);
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        $lastPage = $result->lastPage();
        $total = $result->total();
        $res = $result->items();
        $arr = [];
        foreach ($res as $key => $v) {

            $problemType = $v['problem_type'];
            if ($problemType == 1) {
                $v['problem_name'] = '改善建议';

            } elseif ($problemType == 2) {
                $v['problem_name'] = '卡顿';

            } elseif ($problemType == 3) {
                $v['problem_name'] = '闪退';

            } elseif ($problemType == 9) {
                $v['problem_name'] = '其他建议';
            }
            $photoArr = explode(',', trim($v['photo'], '[]'));
            $photoArray = [];
            foreach ($photoArr as $photo) {
                $photo = trim($photo, '""');
                array_push($photoArray, $photo);
            }
            $v['photo'] = $photoArray;

            array_push($arr, $v);

        }
        $data = [
            'total' => $total,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'lastPage' => $lastPage,
            'feedback_list' => $arr

        ];
        return $data;
    }

    /*
     *获取服务器列表
     * */
    public function getAllServerList()
    {
        try {
            $result = DB::name('servers')->select();
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
     * 获取已经处理的反馈信息
     * */
    public function getAllHandled($serverId, $perPage, $currentPage)
    {
        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $result = $DB->name('feedback')->alias('fb')->join('user u',"u.id = fb.userId")->field('fb.id,fb.userId,fb.contact,fb.feedback_message,fb.handle_status,fb.remark,fb.problem_type,fb.photo,fb.create_by,fb.create_time,fb.update_by,fb.update_time,fb.sender,fb.check_state,fb.client_from,fb.version_number,fb.priority,u.username,u.nickname')->where('fb.handle_status', 'eq', '1')->order('fb.id', 'DESC')->paginate($perPage, false, ['page' => $currentPage]);
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        $lastPage = $result->lastPage();
        $total = $result->total();
        $res = $result->items();
        $arr = [];
        foreach ($res as $key => $v) {

            $problemType = $v['problem_type'];
            if ($problemType == 1) {
                $v['problem_name'] = '改善建议';

            } elseif ($problemType == 2) {
                $v['problem_name'] = '卡顿';

            } elseif ($problemType == 3) {
                $v['problem_name'] = '闪退';

            } elseif ($problemType == 9) {
                $v['problem_name'] = '其他建议';
            }
            $photoArr = explode(',', trim($v['photo'], '[]'));
            $photoArray = [];
            foreach ($photoArr as $photo) {
                $photo = trim($photo, '""');
                array_push($photoArray, $photo);
            }
            $v['photo'] = $photoArray;

            array_push($arr, $v);

        }
        $data = [
            'total' => $total,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'lastPage' => $lastPage,
            'feedback_list' => $arr

        ];
        return $data;
    }

    /*
     * 获取未处理的反馈信息
     * */
    public function getAllUntreated($serverId, $perPage, $currentPage)
    {
        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $result = $DB->name('feedback')->alias('fb')->join('user u',"u.id = fb.userId")->field('fb.id,fb.userId,fb.contact,fb.feedback_message,fb.handle_status,fb.remark,fb.problem_type,fb.photo,fb.create_by,fb.create_time,fb.update_by,fb.update_time,fb.sender,fb.check_state,fb.client_from,fb.version_number,fb.priority,u.username,u.nickname')->where('fb.handle_status', 'eq', '2')->order('fb.id', 'DESC')->paginate($perPage, false, ['page' => $currentPage]);
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        $lastPage = $result->lastPage();
        $total = $result->total();
        $res = $result->items();
        $arr = [];
        foreach ($res as $key => $v) {

            $problemType = $v['problem_type'];
            if ($problemType == 1) {
                $v['problem_name'] = '改善建议';

            } elseif ($problemType == 2) {
                $v['problem_name'] = '卡顿';

            } elseif ($problemType == 3) {
                $v['problem_name'] = '闪退';

            } elseif ($problemType == 9) {
                $v['problem_name'] = '其他建议';
            }
            $photoArr = explode(',', trim($v['photo'], '[]'));
            $photoArray = [];
            foreach ($photoArr as $photo) {
                $photo = trim($photo, '""');
                array_push($photoArray, $photo);
            }
            $v['photo'] = $photoArray;


            array_push($arr, $v);

        }
        $data = [
            'total' => $total,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'lastPage' => $lastPage,
            'feedback_list' => $arr

        ];
        return $data;
    }

    /*
     * 获取已经处理的反馈信息
     * */
    public function getAllHandling($serverId, $perPage, $currentPage)
    {
        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $result = $DB->name('feedback')->alias('fb')->join('user u',"u.id = fb.userId")->field('fb.id,fb.userId,fb.contact,fb.feedback_message,fb.handle_status,fb.remark,fb.problem_type,fb.photo,fb.create_by,fb.create_time,fb.update_by,fb.update_time,fb.sender,fb.check_state,fb.client_from,fb.version_number,fb.priority,u.username,u.nickname')
                ->where('handle_status', 'eq', '3')
                ->order('id', 'DESC')
                ->paginate($perPage, false, ['page' => $currentPage]);
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_DB_EXCEPTION'], $e->getMessage());
            exit;
        }
        $lastPage = $result->lastPage();
        $total = $result->total();
        $res = $result->items();
        $arr = [];
        foreach ($res as $key => $v) {

            $problemType = $v['problem_type'];
            if ($problemType == 1) {
                $v['problem_name'] = '改善建议';

            } elseif ($problemType == 2) {
                $v['problem_name'] = '卡顿';

            } elseif ($problemType == 3) {
                $v['problem_name'] = '闪退';

            } elseif ($problemType == 9) {
                $v['problem_name'] = '其他建议';
            }
            $photoArr = explode(',', trim($v['photo'], '[]'));
            $photoArray = [];
            foreach ($photoArr as $photo) {
                $photo = trim($photo, '""');
                array_push($photoArray, $photo);
            }
            $v['photo'] = $photoArray;
            array_push($arr, $v);
        }
        $data = [
            'total' => $total,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'lastPage' => $lastPage,
            'feedback_list' => $arr
        ];
        return $data;
    }

    /*
     * 更改处理状态
     * */
    public function updateHandleStatus($serverId, $handleStatus, $feedbackId)
    {
        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $count = $DB->name('feedback')->where('id', 'eq', $feedbackId)->update(['handle_status' => $handleStatus]);
            return $count;
        } catch (PDOException $e) {
            Util::printResult($GLOBALS['ERROR_PDO_EXCEPTION'], $e->getMessage());
            exit;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }

    /*
     * 检测serverId 是否存在
     * */
    public function checkServerIdExists($serverId)
    {
        $count = DB::name('servers')->where('id', 'eq', $serverId)->count('server_name');
        return $count;
    }

    public function updateFeedbackPriority($serverId, $type, $feedbackId)
    {
        $serverIp = $this->getServerIpByServerId($serverId);
        $DB = $this->connectDbByServerIp($serverIp);
        try {
            $count = $DB->name('feedback')
                ->where('id', 'eq', $feedbackId)
                ->update([
                    'priority' => $type
                ]);
            return $count;
        } catch (PDOException $e) {
            Util::printResult($GLOBALS['ERROR_PDO_EXCEPTION'], $e->getMessage());
            exit;
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
            exit;
        }
    }
}