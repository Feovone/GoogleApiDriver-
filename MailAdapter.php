<?php
require 'PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
//$mail->Port = 25;
$mail->Host = 'ssl://smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'cawacawa7@gmail.com';    //Логин
$mail->Password = 'Yokyredx7';                   //Пароль
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->addAddress('cawacawa17@gmail.com', 'You');
$mail->isHTML(true);

$mail->Subject = 'Тема письма';
$mail->Body    = 'BoDY';
$mail->addAttachment('25mb-file.txt');
$mail->AltBody = 'Текстовая версия письма, без HTML тегов (для клиентов не поддерживающих HTML)';

for($i=0; $i<50;$i++){
$mail->Body    = 'BoDY'.$i;
$mail->setFrom($i.'@gmail.com', 'You'.$i);
if(!$mail->send()) {
    echo 'Ошибка при отправке. Ошибка: ' . $mail->ErrorInfo;
} else {

}}