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
$mail->Username = 'contact@fodman.com';
$mail->Password = 'WeareZebest2019';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
