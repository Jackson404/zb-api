<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 12:57
 */

namespace app\admin\controller;

use app\admin\common\Check;
use app\admin\common\SendMail;
use app\admin\common\Util;
use think\Controller;

class PushMail extends Controller
{
    public function index()
    {
        $toemail = '1983766950@qq.com';
        $name = '';
        $subject = '';
        $content = '恭喜你，邮件测试成功。';
        $CDBMail = SendMail::GetInstance();
        dump($CDBMail->sendMail($toemail, $name, $subject, $content));

    }

    public function pushMail()
    {
        $address = Check::checkEmail(trim(isset($_POST['address']) ? $_POST['address'] : ''));
        $content = Check::check(trim(isset($_POST['content']) ? $_POST['content'] : ''));
        if ($address=='' || $content ==''){
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $SendMail = SendMail::GetInstance();
        $result = $SendMail->send_mail($address, '', '', $content);
        if ($result) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '发送成功');
            exit;
        }
        Util::printResult($GLOBALS['ERROR_SEND_FAILED'], '发送失败');
        exit;
    }

}