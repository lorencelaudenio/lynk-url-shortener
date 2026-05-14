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
        $mail->Subject = "Welcome to Lynk";

$mail->Body = "
<div style='background:#0f172a;padding:40px 20px;font-family:Arial,sans-serif;'>

    <div style='max-width:600px;margin:auto;background:#111827;border-radius:12px;padding:40px;color:#e5e7eb;'>

        <h1 style='margin-top:0;font-size:28px;color:#ffffff;'>
            Welcome to Lynk
        </h1>

        <p style='font-size:16px;line-height:1.7;color:#cbd5e1;'>
            Hi <strong>$username</strong>,
        </p>

        <p style='font-size:16px;line-height:1.7;color:#cbd5e1;'>
            Your account has been successfully created.
            You can now shorten URLs, manage links,
            and track clicks from your dashboard.
        </p>

        <div style='margin:35px 0;'>

            <a href='https://lynk.page.gd/login.php'
               style='background:#3b82f6;
                      color:#ffffff;
                      padding:14px 24px;
                      text-decoration:none;
                      border-radius:8px;
                      font-weight:bold;
                      display:inline-block;'>

                Open Dashboard

            </a>

        </div>

        <hr style='border:none;border-top:1px solid #1f2937;margin:30px 0;'>

        <p style='font-size:13px;color:#94a3b8;line-height:1.6;'>

            You're receiving this email because you created
            an account on Lynk URL Shortener.

        </p>

        <p style='font-size:13px;color:#64748b;'>

            © " . date('Y') . " Lynk URL Shortener

        </p>

    </div>

</div>
";

        $mail->send();

    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}

function sendResetEmail($email, $username, $token) {

    $resetLink = "https://lynk.page.gd/reset-password.php?token=$token";

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = SMTP_EMAIL;
        $mail->Password = SMTP_PASSWORD;

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(SMTP_EMAIL, SMTP_NAME);

        $mail->addAddress($email, $username);

        $mail->isHTML(true);

        $mail->Subject = "Reset Your Password";

        $mail->Body = "
        <div style='font-family:Arial;padding:30px;background:#0f172a;color:#e5e7eb;'>

            <div style='max-width:600px;margin:auto;background:#111827;padding:40px;border-radius:12px;'>

                <h2 style='color:#fff;'>Password Reset Request</h2>

                <p>Hello <strong>$username</strong>,</p>

                <p>
                    We received a request to reset your password.
                </p>

                <p>
                    Click the button below to create a new password:
                </p>

                <a href='$resetLink'
                   style='display:inline-block;
                          padding:14px 24px;
                          background:#3b82f6;
                          color:white;
                          text-decoration:none;
                          border-radius:8px;
                          margin-top:15px;'>

                    Reset Password

                </a>

                <p style='margin-top:30px;font-size:13px;color:#94a3b8;'>
                    This link will expire in 1 hour.
                </p>

            </div>

        </div>";

        $mail->send();

    } catch (Exception $e) {

        error_log($mail->ErrorInfo);
    }
}