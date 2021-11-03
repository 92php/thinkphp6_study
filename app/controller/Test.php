<?php

namespace app\controller;

use app\logic\LeackBucketApi;
use app\logic\SlideTimeWindow;
use app\logic\TokenBucketApi;
use PHPMailer\PHPMailer\PHPMailer;
use think\App;
use think\Request;
use app\logic\Aes;
use app\logic\BloomFilter;
use app\logic\SpeedCounterApi;

class Test extends \app\controller\Admin
{
    protected $req;
    protected $app;

    public function __construct(Request $request, App $app)
    {
        $this->req = $request;
        $this->app = $app;
    }

    public function index()
    {
        return show(1, 200);
    }

    public function test($name = "world")
    {
        return "hello," . $name . "! this is " . $this->req->action();
    }

    public function test1()
    {
        $this->app->bind('Cache1', 'think\Cache');
        $cache1 = app('Cache1');
        $cache1->set('name', '123123');
        $r = $cache1->get('name');
        dump($r);
    }

    public function test2()
    {
        return "hello1";
    }

    public function test3()
    {
        $tmp = new Aes("aes-128-gcm", "123456789WANGchao");
        $plaintext = "message to be encrypted";
        $ciphertext = $tmp->encrypt($plaintext);
        dump($ciphertext);
        $original_plaintext = $tmp->decrypt($ciphertext);
        dump($original_plaintext);
    }

    public function test4()
    {
        $toemail = '812178731@qq.com';//定义收件人的邮箱
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "tingtaigeoa@163.com";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">
        $mail->Password = "OLJGYMUKCTUOWKBT";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="color:#333333;">
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式</span><span style="color:#333333;">
        $mail->Port = 465;// 163邮箱的ssl协议方式端口号是465/994

        $mail->setFrom("tingtaigeoa@163.com", "Mailer");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail, 'Miss Yi');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->addReplyTo("tingtaigeoa@163.com", "Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件
        $mail->Subject = "这是一个测试邮件";// 邮件标题
        $mail->Body = "邮件内容是 <b>您的验证码是：123456</b>，哈哈哈！";// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if (!$mail->send()) {// 发送邮件
            echo "Message could not be sent.";
            echo "Mailer Error: " . $mail->ErrorInfo;// 输出错误信息
        } else {
            echo '发送成功';
        }
    }

    public function test5()
    {
        d("初始化布隆过滤器");
        $f = new BloomFilter;
        $l1 = 10000;
        $l2 = 10;

        d("添加初始数据");
        for ($i = 0; $i < $l1; $i++) {
            $f->add("#" . $i);
            //d("add #{$i}");
        }

        d("测试判断随机数 {$l2}个");
        for ($i = 0; $i < $l2; $i++) {
            //$s = "#" . rand($l2, $l2 + 1000);
            //$s = "#0";
            $s = "#" . rand(0, $l1 * 2);
            $r = $f->has($s);
            d("判断数字 {$s} >> " . ($r ? "true" : "false"));
        }

    }

    public function test6()
    {
        $s = new SpeedCounterApi();
        $r = $s->getApi();
        dump($r);
    }

    public function test7()
    {
        $s = new SlideTimeWindow();
        $r = $s->getApi();
        dump($r);
    }

    public function test8()
    {
        $s = new LeackBucketApi();
        $r = $s->getApi();
        dump($r);
    }

    public function test9()
    {
        $s = new TokenBucketApi();
        $r = $s->getApi();
        dump($r);
    }

}