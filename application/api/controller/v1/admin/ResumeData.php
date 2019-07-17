<?php

namespace app\api\controller\v1\admin;

use app\api\model\DataResume;
use think\cache\driver\Redis;
use think\Controller;
use think\Request;
use Util\Util;

class ResumeData extends Controller
{
    public function getCount()
    {
        ini_set('max_execution_time', 0);
        $dataResumeModel = new DataResume();
        $result = $dataResumeModel->getCount();
        $total = $result[0]['count(*)'];

        $redis = new Redis();
        $redis->set('resumeDataCount', $total);
        $redis->handler()->close();
        $data['total'] = $total;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getByPage()
    {
        ini_set('max_execution_time', 0);
        $params = Request::instance()->request();

        $pageIndex = $params['pageIndex'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;
        $redis = new Redis();
        $dataResumeModel = new DataResume();
        $content = $dataResumeModel->getByPage($pageIndex, $pageSize);
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;

        $data['total'] = $redis->get('resumeDataCount');
        $redis->handler()->close();
        $data['page'] = $content;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    public function delResume()
    {
        $params = Request::instance()->request();
        $resumeId = $params['resumeId'] ?? '';
        $dataResumeModel = new DataResume();
        $delRow = $dataResumeModel->delResume($resumeId);
        $data['delRow'] = $delRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function editResume()
    {
        $params = Request::instance()->request();
        $resumeId = $params['resumeId'] ?? '';
        $name = $params['name'] ?? '';
        $sex = $params['sex'] ?? '';
        $birth = $params['birth'] ?? '';
        $work = $params['work'] ?? '';
        $wage = $params['wage'] ?? '';
        $profession = $params['profession'] ?? '';
        $position = $params['position'] ?? '';
        $qua = $params['qua'] ?? '';
        $gra = $params['gra'] ?? '';
        $spe = $params['spe'] ?? '';
        $bonus = $params['bonus'] ?? '';
        $allow = $params['allow'] ?? '';
        $resume = $params['resume'] ?? '';
        $phone = $params['phone'] ?? '';
        $mail = $params['mail'] ?? '';
        $habitation = $params['habitation'] ?? '';
        $profe = $params['profe'] ?? '';
//        $from = $params['from'] ?? '';

        $dataResumeModel = new DataResume();
        $data = [
            'name' => $name,
            'sex' => $sex,
            'birth' => $birth,
            'work' => $work,
            'wage' => $wage,
            'profession' => $profession,
            'position' => $position,
            'qua' => $qua,
            'gra' => $gra,
            'spe' => $spe,
            'bonus' => $bonus,
            'allow' => $allow,
            'resume' => $resume,
            'phone' => $phone,
            'mail' => $mail,
            'habitation' => $habitation,
            'profe' => $profe,
//            'from' => $from
        ];

        $updateRow = $dataResumeModel->editResume($resumeId,$data);
        $arr['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function getDetail()
    {
        $params = Request::instance()->request();
        $resumeId = $params['resumeId'] ?? '';

        $dataResumeModel = new DataResume();
        $detail = $dataResumeModel->getDetail($resumeId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}