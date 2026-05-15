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
            You can now shorten URLs, manage your links, and track clicks anytime from your dashboard.
        </p>

        <p style='font-size:16px;line-height:1.7;color:#cbd5e1;'>
            You can also set up your Link in Bio page to organize all your important links in one place.
        </p>

        <!-- MAIN CTA -->
        <div style='margin:30px 0;'>

            <a href='https://lynk.page.gd/login.php'
               style='background:#3b82f6;
                      color:#ffffff;
                      padding:14px 24px;
                      text-decoration:none;
                      border-radius:8px;
                      font-weight:bold;
                      display:inline-block;'>

                Login to your account

            </a>

        </div>

        <hr style='border:none;border-top:1px solid #1f2937;margin:30px 0;'>

        <p style='font-size:13px;color:#94a3b8;line-height:1.6;'>
            You're receiving this email because you created an account on Lynk URL Shortener.
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

function sendReportAlert($url, $reason, $details, $reported_by = "Guest") {
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
        $mail->addAddress(ADMIN_EMAIL, "Admin");

        $mail->isHTML(true);
        $mail->Subject = "New Abuse Report - Lynk";

$mail->Body = "
<div style='background:#0b1220;padding:40px 20px;font-family:Arial,sans-serif;'>

    <div style='max-width:600px;margin:auto;background:#111827;border-radius:14px;overflow:hidden;border:1px solid #1f2937;'>

        <!-- HEADER -->
        <div style='padding:20px 30px;background:#0f172a;border-bottom:1px solid #1f2937;'>
            <h2 style='margin:0;color:#ffffff;font-size:18px;'>
                🚨 New Abuse Report
            </h2>
            <p style='margin:5px 0 0;color:#94a3b8;font-size:13px;'>
                Lynk Moderation System
            </p>
        </div>

        <!-- BODY -->
        <div style='padding:30px;color:#e5e7eb;'>

            <!-- REPORTER -->
            <div style='margin-bottom:20px;'>
                <p style='margin:0;color:#94a3b8;font-size:12px;'>Reported By</p>
                <p style='margin:5px 0;font-size:15px;color:#ffffff;'>
                    " . htmlspecialchars($reported_by) . "
                </p>
            </div>

            <!-- LINK -->
            <div style='background:#0f172a;padding:15px;border-radius:10px;margin-bottom:15px;'>
                <p style='margin:0;color:#94a3b8;font-size:12px;'>Reported Link</p>
                <p style='margin:5px 0 0;color:#60a5fa;word-break:break-all;'>
                    " . htmlspecialchars($url) . "
                </p>
            </div>

            <!-- REASON -->
            <div style='background:#0f172a;padding:15px;border-radius:10px;margin-bottom:15px;'>
                <p style='margin:0;color:#94a3b8;font-size:12px;'>Reason</p>
                <p style='margin:5px 0 0;color:#ffffff;'>
                    " . htmlspecialchars($reason) . "
                </p>
            </div>

            <!-- DETAILS -->
            <div style='background:#0f172a;padding:15px;border-radius:10px;'>
                <p style='margin:0;color:#94a3b8;font-size:12px;'>Details</p>
                <p style='margin:5px 0 0;color:#cbd5e1;line-height:1.5;'>
                    " . nl2br(htmlspecialchars($details)) . "
                </p>
            </div>

        </div>

        <!-- FOOTER -->
        <div style='padding:20px 30px;border-top:1px solid #1f2937;background:#0f172a;text-align:center;'>
            <p style='margin:0;font-size:11px;color:#64748b;'>
                This report was automatically generated by Lynk system
            </p>
            <p style='margin:5px 0 0;font-size:11px;color:#475569;'>
                © " . date('Y') . " Lynk URL Shortener
            </p>
        </div>

    </div>

</div>
";

        $mail->send();

    } catch (Exception $e) {
        error_log("Report mail error: " . $mail->ErrorInfo);
    }
}