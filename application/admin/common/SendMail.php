<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 13:02
 */

namespace app\admin\common;


use PHPMailer;

class SendMail
{

    public static $_instance = null;

    private function __construct()
    {
        # code...
    }

    public static function GetInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 系统邮件发送函数
     * @param string $tomail 接收邮件者邮箱
     * @param string $name 接收邮件者名称
     * @param string $subject 邮件主题
     * @param string $body 邮件内容
     * @param string $attachment 附件列表
     * @return boolean
     * @author static7 <static7@qq.com>
     */
    public function send_mail($tomail, $name, $subject = '', $body = '', $attachment = null)
    {
        $mail = new PHPMailer();           //实例化PHPMailer对象
        $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP();                    // 设定使用SMTP服务
        //$mail->SMTPDebug = 1;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
        $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
        $mail->SMTPSecure = 'tls';          // 使用安全协议
        $mail->Host = "smtp.mxhichina.com"; // SMTP 服务器
        $mail->Port = 587;                  // SMTP服务器的端口号
        $mail->Username = "xinhuo_mailer@xinhuotech.com";    // SMTP服务器用户名
        $mail->Password = "Mail123456";     // SMTP服务器密码
        $mail->setFrom('xinhuo_mailer@xinhuotech.com', 'xinhuo_mailer');
        $replyEmail = 'xinhuo_mailer@xinhuotech.com';                   //留空则为发件人EMAIL
        $replyName = 'xinhuo_mailer';                    //回复名称（留空则为发件人名称）
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($tomail, $name);
        if (is_array($attachment)) { // 添加附件
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        return $mail->Send() ? true : $mail->ErrorInfo;
    }
    public function sendMail($tomail, $name, $subject = '', $body = '', $attachment = null)
    {
        $mail = new PHPMailer();           //实例化PHPMailer对象
        $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP();                    // 设定使用SMTP服务
        //$mail->SMTPDebug = 1;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
        $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
        $mail->SMTPSecure = 'tls';          // 使用安全协议
        $mail->Host = "smtp.163.com"; // SMTP 服务器
        $mail->Port = 25;                  // SMTP服务器的端口号
        $mail->Username = "18937126028@163.com";    // SMTP服务器用户名
        $mail->Password = "xuliulei19931012";     // SMTP服务器密码
        $mail->setFrom('18937126028@163.com', 'xinhuo_mailer');
        $replyEmail = 'xinhuo_mailer@xinhuotech.com';                   //留空则为发件人EMAIL
        $replyName = 'xinhuo_mailer';                    //回复名称（留空则为发件人名称）
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($tomail, $name);
        if (is_array($attachment)) { // 添加附件
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        return $mail->Send() ? true : $mail->ErrorInfo;
    }
}