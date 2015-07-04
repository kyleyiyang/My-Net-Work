<?php
//It can use gmail server to send out emails to remind a user having a new message.
//To set up PHPMailer, from https://github.com/PHPMailer/PHPMailer
//download 'class.phpmailer.php' and 'class.smtp.php'
//The code was modified according to https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
// and http://phpmailer.worxware.com/index.php?pg=examplebgmail
//Need to select "php/extensions/php_openssl" from the WampServer.
//Run the file on a browser.

require_once('phpmailer/class.phpmailer.php');
include('phpmailer/class.smtp.php');
$address = $subject = "";

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    if ($text != "") {
        $address = queryMysql("SELECT email FROM members WHERE user='$view'");
        $address = mysql_fetch_array($address);
        $address = $address['email'];        
        $subject = "You have a new message from $user";
$mail = new PHPMailer();

$mail->IsSMTP();
// $mail->Host = 'MyNetWork.com';
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->Username = 'your.user.name@gmail.com';
$mail->Password = 'your.user.password';

$mail->SetFrom('info@MyNetWork.com', 'Information');

$mail->AddReplyTo('info@MyNetWork.com', 'Information');

$mail->Subject = $subject;
$body = "<h3>Please <a href='http://localhost/MyNetWork/messages.php'>click here</a> to see the message.</h3>";
//$mail->Body = 'This is the HTML message body <b> in bold!</b>';
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer';

$mail->MsgHTML($body);

$mail->AddAddress($address);

//$mail->AddAttachment('images/phpmailer.gif');

if (!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent';
}
    }
}
?> 
