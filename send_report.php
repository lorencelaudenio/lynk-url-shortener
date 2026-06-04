<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: text/plain; charset=utf-8');

ob_start();

include 'config.php';
include 'includes/mailer.php';

/* INPUTS */
$email   = trim($_POST['email'] ?? '');
$url     = trim($_POST['url'] ?? '');
$reason  = trim($_POST['reason'] ?? '');
$details = trim($_POST['details'] ?? '');

/* VALIDATION */
if ($email === '' || $url === '' || $reason === '') {
    ob_clean();
    echo "missing_fields";
    exit;
}

/* TOKEN + EXPIRY */
$token = bin2hex(random_bytes(32));
$expiresAt = date("Y-m-d H:i:s", strtotime("+24 hours"));

/* INSERT DB */
$stmt = $conn->prepare("
    INSERT INTO report_pending (email, url, reason, details, token, created_at, expires_at)
    VALUES (?, ?, ?, ?, ?, NOW(), ?)
");

if (!$stmt) {
    ob_clean();
    echo "db_error";
    exit;
}

$stmt->bind_param("ssssss", $email, $url, $reason, $details, $token, $expiresAt);

if (!$stmt->execute()) {
    ob_clean();
    echo "db_insert_error";
    exit;
}

/* CONFIRM LINK */
$confirmLink = "https://cutthis.link/confirm_report.php?token=$token";

/* EMAIL CONTENT */
$subject = "Confirm your report";

$message = "
<h3>Confirm your report</h3>
<p>Please click the button below to confirm your report:</p>

<a href='$confirmLink'
style='display:inline-block;padding:10px 15px;background:#ef4444;color:#fff;text-decoration:none;border-radius:6px;'>
Confirm Report
</a>

<p style='margin-top:15px;color:#666;font-size:12px;'>
This link will expire in 24 hours.
</p>
";

/* SEND EMAIL */
$sent = sendReportEmail($email, $subject, $message);

if (!$sent) {
    ob_clean();
    echo "mail_failed";
    exit;
}

/* SUCCESS */
ob_clean();
echo "success";
exit;