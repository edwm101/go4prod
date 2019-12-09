<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'Exception.php';
require 'SMTP.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 2;
$mail->isSMTP();
$mail->Host = 'ssl0.ovh.net';
$mail->SMTPAuth = true;
$mail->Username = 'test@test.com';
$mail->Password = 'test';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
