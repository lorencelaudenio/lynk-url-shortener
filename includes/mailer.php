<?php

require_once __DIR__ . '/../config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function sendWelcomeEmail($email, $username) {

    $mail = new PHPMailer(true);

    try {

        // SMTP CONFIG
        $mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = SMTP_EMAIL;
$mail->Password = SMTP_PASSWORD;

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;


        // SENDER
        $mail->setFrom(SMTP_EMAIL, SMTP_NAME);
        $mail->addAddress($email, $username);

        // EMAIL CONTENT
        $mail->isHTML(true);
        $mail->Subject = "Welcome to Lynk 🚀";

        $mail->Body = "
        <div style='font-family:Arial;padding:20px'>
            <h2>Hi $username 👋</h2>
            <p>Welcome to <b>Lynk</b>!</p>
            <p>You can now shorten links and track clicks easily.</p>
            <a href='https://lynk.page.gd/login.php'>Login to Dashboard</a>
        </div>";

        $mail->send();

    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}