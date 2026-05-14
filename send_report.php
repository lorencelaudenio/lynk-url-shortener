<?php
session_start();
$reported_by = "Guest";

if (!empty($_SESSION['user_id'])) {

    include 'config.php';

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $reported_by = $row['username'];
    }
}
include 'config.php';
include 'includes/mailer.php';

$url = trim($_POST['url'] ?? '');
$reason = trim($_POST['reason'] ?? '');
$details = trim($_POST['details'] ?? '');

/* 1. CHECK REQUIRED FIELDS */
if (empty($url) || empty($reason)) {
    echo "error";
    exit;
}

/* 2. VALIDATE DOMAIN */
if (!preg_match('#^https?://(www\.)?lynk\.page\.gd/[a-zA-Z0-9\-]+$#', $url)) {
    echo "invalid_domain";
    exit;
}

/* 3. EXTRA SAFETY (optional but good) */
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo "invalid_url";
    exit;
}

/* 4. LOG REPORT */
$log = date("Y-m-d H:i:s") .
       " | URL: $url | Reason: $reason | Details: $details\n";

file_put_contents("reports.txt", $log, FILE_APPEND);

/* 5. SEND EMAIL */
sendReportAlert($url, $reason, $details, $reported_by);
/* 6. SUCCESS RESPONSE */
echo "success";
exit;
?>