<?php

namespace app\api\controller\v1;

use app\api\model\EpOrderModel;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use think\Controller;
use think\Exception;
use think\Request;
use Util\Check;
use Util\Util;

class EmUser extends Controller
{
    /**
     * 接单
     */
    public function receiveOrder()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $userId = $GLOBALS['userId'];
        $epOrderModel = new EpOrderModel();
        if ($epOrderModel->checkUserRecOrder($positionId, $userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户已经接过该单');
            exit;
        }
        $orderId = get_order_sn();

        try {

            $json = json_encode(
                [
                    'userId' => $userId,
                    'positionId' => $positionId,
                    'orderId' => $orderId
                ]
            );
            //生成二维码
            $qrCode = new QrCode($json);
            $qrCode->setSize(300);
            $qrCode->setWriterByName('png');
            $qrCode->setMargin(10);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
            $qrCode->setLabel('扫描二维码报名', 12);
            $qrCode->setLogoPath(ROOT_PATH . 'public/avatar/a1.png');
            $qrCode->setLogoSize(50, 50);
            $qrCode->setRoundBlockSize(true);
            $qrCode->setValidateResult(false);
            $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
//            header('Content-Type: ' . $qrCode->getContentType());
            // Save it to a file
            $qrCodeUrl = ROOT_PATH . 'public/order/' . $orderId . '.png';
            $saveUrl = '/order/' . $orderId . '.png';
            $qrCode->writeFile($qrCodeUrl);

            $arr = [
                'orderId' => $orderId,
                'userId' => $userId,
                'positionId' => $positionId,
                'qrCode' => $saveUrl,
                'createBy' => $userId,
                'createTime' => currentTime(),
                'updateBy' => $userId,
                'updateTime' => currentTime()
            ];
            $recId = $epOrderModel->add($arr);
            if ($recId > 0) {
                $data['recId'] = $recId;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            }
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], '出现异常');
            exit;
        }

    }

    /**
     * 获取用户的接单列表
     */
    public function getUserRecOrdersPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);
        $userId = $GLOBALS['userId'];

        $epOrderModel = new EpOrderModel();
        $page = $epOrderModel->getUserRecOrdersPage($userId, $pageIndex, $pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    public function share()
    {

    }


}