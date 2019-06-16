<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 14:05
 */

namespace app\admin\controller;


use app\admin\common\SendMail;
use app\admin\common\Util;
use app\admin\model\AppProject;
use app\admin\model\NoticePerson as NoticePersonModel;
use app\admin\validate\NoticePersonValidate;
use think\Controller;
use think\Db;
use think\exception\DbException;
use think\Request;

class Notice extends Controller
{
    public function addPerson()
    {
        $data = Request::instance()->param();
        $validate = new NoticePersonValidate();
        $result = $validate
            ->batch()
            ->scene('add')
            ->check($data);
        if (!$result) {
            Util::printResult($GLOBALS['ERROR_FAILED_VALIDATE'], $validate->getError());
            exit;
        }
        $name = $data['name'];
        $projectId = $data['project_id'];
        $count = (new AppProject())->where('id', 'eq', $projectId)->count();
        if ($count <= 0) {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '项目不存在');
            return;
        }
        $_result = NoticePersonModel::get(['name' => $name, 'project_id' => $projectId]);
        if ($_result != null && $_result->count('name') > 0) {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '名字已经存在');
            exit;
        }
        $result = NoticePersonModel::create($data, ['name', 'email', 'phone', 'project_id']);
        $personId = $result->getLastInsID();
        if ($personId > 0) {
            $arr['personId'] = $personId;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
        exit;

    }

    /**
     * 根据项目Id获取项目下人物列表
     */
    public function getPersonListByProjectId()
    {
        $projectId = Request::instance()->param('projectId');
        if ($projectId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            return;
        }
        try {
            $result = NoticePersonModel::all(['project_id' => $projectId]);
            $arr['personList'] = $result;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            return;
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
        }

    }

    public function getPersonAndSendEmail()
    {
        $params = Request::instance()->param();
        $appId = $params['appId'];
        $appKey = $params['appKey'];
        $content = json_encode($params);
        if ($appId == '' || $appKey == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $project = AppProject::get(['app_id' => $appId, 'app_key' => $appKey]);
        $projectId = $project->getAttr('id');
        $projectName = $project->getAttr('name');
        $result = NoticePersonModel::all(['project_id' => $projectId]);
        $data = [];

        foreach ($result as $v) {
            $vArr = $v->getData();
            $sendEmail = SendMail::GetInstance();
            $result = $sendEmail->sendMail($vArr['email'], $vArr['name'], $projectName . '项目出现错误', $content);
            array_push($data, $result);
        }

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    public function createProject()
    {

        $name = $_POST['name'];
        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $appId = time() . getRandLengthStr();
        $appKey = md5(sha1($appId));
        $result = AppProject::create(['name' => $name, 'app_id' => $appId, 'app_key' => $appKey]);
        $id = $result->getLastInsID();
        $data['id'] = $id;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;

    }

    /**
     * 获取项目列表
     */
    public function getProjectList()
    {
        try {
            //$projectList = AppProject::all();
            $projectList = Db::name('app_project')->select();
            $data['projectList'] = $projectList;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        } catch (DbException $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], $e->getMessage());
        }
    }

    /**
     * 删除创建的项目
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteProjectById()
    {
        $projectId = Request::instance()->param('projectId');
        $count = Db::name('app_project')->where('id', 'eq', $projectId)->count();
        if ($count > 0) {
            $delRow = Db::name('app_project')->where('id', 'eq', $projectId)->delete();
            if ($delRow > 0) {
                $delPersonRow = Db::name('notice_person')->where('project_id', 'eq', $projectId)->delete();
            }

            $data['deleteRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            return;
        } else {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '项目不存在');
        }

    }

    /**
     * 删除人物
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deletePersonById()
    {
        $personId = Request::instance()->param('personId');
        $delPersonRow = Db::name('notice_person')->where('id', 'eq', $personId)->delete();
        $data['delPersonRow'] = $delPersonRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}