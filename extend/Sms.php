<?php

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Util\Util;

class Sms
{
    public function __construct()
    {
        // Create a global client
        AlibabaCloud::accessKeyClient(
            $GLOBALS['ACCESS_KEY_ID'],
            $GLOBALS['ACCESS_KEY_SECRET']
        )->regionId('cn-shanghai')->asDefaultClient();

    }

    public function send($phone,$signName,$templateCode,$code)
    {
        try {

            $result = AlibabaCloud::dysmsapi()
                ->v20170525()
                ->sendSms()
                ->withPhoneNumbers($phone)
                ->withSignName($signName)
                ->withTemplateCode($templateCode)
                ->withTemplateParam(
                    json_encode(
                        [
                            'code' => $code
                        ],
                        JSON_UNESCAPED_UNICODE
                    ))
                ->request();
            // Convert the result to an array and print
//            print_r($result->toArray());
            return $result->toArray();
        } catch (ClientException $e) {
            Util::printResult($e->getErrorCode(),$e->getErrorMessage());
//            // Print the error code
//            echo $e->getErrorCode() . PHP_EOL;
//            // Print the error message
//            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            Util::printResult($e->getErrorCode(),$e->getResult()->toArray());
            // Print the error code
//            echo $e->getErrorCode() . PHP_EOL;
//            // Print the error message
//            echo $e->getErrorMessage() . PHP_EOL;
//            // Print the RequestId
//            echo $e->getRequestId() . PHP_EOL;
//            // Convert the result to an array and print
//            print_r($e->getResult()->toArray());
        }
    }
}