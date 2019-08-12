<?php

namespace app\api\controller\v1\ep;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use think\Request;
use Util\Util;

class Mail extends EpUserBase
{
    public function send()
    {
        $params = Request::instance()->request();
        $toAddress = $params['toAddress'] ?? '';
        $toUsername = $params['toUsername'] ?? '';
        $title = $params['title'] ?? '';
        $content = $params['content'] ?? '';

        try {
            $mail = new PHPMailer();
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host = 'smtp.mxhichina.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'xuliulei@zhengbu121.com';                     // SMTP username
            $mail->Password = '0xxRoot@@121';                               // SMTP password
            $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('xuliulei@zhengbu121.com', '正步网络科技');
            $mail->addAddress($toAddress, $toUsername);     // Add a recipient

//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            // Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $title;
            $mail->Body = $content;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                Util::printResult($GLOBALS['ERROR_SUCCESS'], '邮件发送成功');
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '邮件发送失败');
                exit;
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}