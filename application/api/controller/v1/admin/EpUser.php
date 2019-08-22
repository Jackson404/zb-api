<?php

namespace app\api\controller\v1\admin;

use app\api\model\CompanyManagementModel;
use app\api\model\EpCertModel;
use app\api\model\EpUserCertModel;
use app\api\model\EpUserModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpUser extends AdminBase
{
    public function getEpUserList()
    {
        $epUserModel = new EpUserModel();
        $list = $epUserModel->getUserList();
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 后台添加企业用户
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function addEpUser()
    {
        $params = Request::instance()->param();
        $realname = Check::check($params['realname'] ?? '');
        $realphone = Check::check($params['realphone'] ?? '', 1, 11);
        $companyName = Check::check($params['companyName'] ?? ''); //公司名称
        $companyAddr = Check::check($params['companyAddr'] ?? ''); //公司地址

        if ($realname == '' || $realphone == '' || $companyAddr == '' || $companyName == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $adminUserId = $GLOBALS['userId'];

        $companyModel = new CompanyManagementModel();
        $epUserModel = new EpUserModel();
        $epUserCertModel = new EpUserCertModel();

        if ($epUserModel->checkPhoneExist($realphone)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '手机号已注册');
            exit;
        }

        if ($epUserCertModel->checkEpHasCert($companyName)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '公司已认证过了');
            exit;
        }

        $detail = $companyModel->getDetailByCompanyName($companyName);
        if ($detail == null) {
            $companyId = $companyModel->insertGetId([
                'name' => $companyName,
                'address' => $companyAddr,
                'isCert' => 1,
                'createBy' => $adminUserId,
                'createTime' => currentTime(),
                'updateBy' => $adminUserId,
                'updateTime' => currentTime()
            ]);

        } else {
            $companyId = $detail->id;
        }

        $avatar = '/avatar/a1.png';
        $data = [
            'avatar' => $avatar,
            'name' => $realname,
            'realname' => $companyName,
            'phone' => $realphone,
            'epId' => $companyId,
            'isReview' => 2,
            'type' => 1,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ];

        $epUserModel->startTrans();
        $userId = $epUserModel->insertGetId($data);

        if ($userId < 0) {
            $epUserModel->rollback();
            util::printResult($GLOBALS['ERROR_REGISTER'], '注册失败');
            exit;
        }

        $arr = [
            'userId' => $userId,
            'applyEpId' => $companyId,
            'type' => 1,
            'realname' => $realname,
            'realphone' => $realphone,
            'companyName' => $companyName,
            'companyAddr' => $companyAddr,
            'pass' => 1,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $reviewId = $epUserCertModel->insertGetId($arr);
        if ($reviewId == 0) {
            $epUserModel->rollback();
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

        $certModel = new EpCertModel();
        $i = $certModel->insertGetId([
            'userId' => $userId,
            'epId' => $companyId,
            'reviewCertId' => $reviewId,
            'type' => 1,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ]);

        if ($i == 0) {
            $epUserModel->rollback();
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

        $epUserModel->commit();
        Util::printResult($GLOBALS['ERROR_SUCCESS'], '添加成功');

    }

    public function addEmUser()
    {
        $params = Request::instance()->param();
        $companyName = Check::check($params['companyName'] ?? '');
        $realname = Check::check($params['realname'] ?? '');
        $realphone = Check::check($params['realphone'] ?? '', 1, 11);


        if ($companyName == '' || $realname == '' || $realphone == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $companyModel = new CompanyManagementModel();
        $epUserModel = new EpUserModel();
        $epUserCertModel = new EpUserCertModel();

        if ($epUserModel->checkPhoneExist($realphone)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '手机号已注册');
            exit;
        }

        if (!$epUserCertModel->checkEpHasCert($companyName)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '公司已未认证过');
            exit;
        }

        $detail = $companyModel->getDetailByCompanyName($companyName);
        if ($detail == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '公司不存在');
            exit;
        } else {
            $companyId = $detail->id;
            $companyAddr = $detail->province . $detail->city . $detail->area . $detail->address;
        }

        $avatar = '/avatar/a1.png';
        $data = [
            'avatar' => $avatar,
            'name' => $realname,
            'realname' => $realname,
            'phone' => $realphone,
            'epId' => $companyId,
            'isReview' => 2,
            'type' => 1,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ];

        $epUserModel->startTrans();
        $userId = $epUserModel->insertGetId($data);

        if ($userId < 0) {
            $epUserModel->rollback();
            util::printResult($GLOBALS['ERROR_REGISTER'], '注册失败');
            exit;
        }

        $arr = [
            'userId' => $userId,
            'applyEpId' => $companyId,
            'type' => 2,
            'realname' => $realname,
            'realphone' => $realphone,
            'companyName' => $companyName,
            'companyAddr' => $companyAddr,
            'pass' => 1,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $reviewId = $epUserCertModel->insertGetId($arr);
        if ($reviewId == 0) {
            $epUserModel->rollback();
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

        $certModel = new EpCertModel();
        $i = $certModel->insertGetId([
            'userId' => $userId,
            'epId' => $companyId,
            'reviewCertId' => $reviewId,
            'type' => 2,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ]);

        if ($i == 0) {
            $epUserModel->rollback();
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

        $epUserModel->commit();
        Util::printResult($GLOBALS['ERROR_SUCCESS'], '添加成功');

    }

    public function getEpUserPage()
    {

    }

}