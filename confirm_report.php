<?php
include 'config.php';
include 'includes/mailer.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    echo "Invalid token.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM report_pending WHERE token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    if (strtotime($row['expires_at']) < time()) {
        echo "This report link has expired. Please submit a new report.";
        exit;
    }

    // INSERT FINAL REPORT
    $stmt2 = $conn->prepare("
        INSERT INTO reports (email, url, reason, details, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt2->bind_param(
        "ssss",
        $row['email'],
        $row['url'],
        $row['reason'],
        $row['details']
    );

    $stmt2->execute();

    // DELETE PENDING
    $del = $conn->prepare("DELETE FROM report_pending WHERE token=?");
    $del->bind_param("s", $token);
    $del->execute();

    // 🚨 SEND ADMIN EMAIL NOTIFICATION
    sendReportAlert(
        $row['url'],
        $row['reason'],
        $row['details'],
        $row['email']
    );

    echo "Report confirmed successfully.";

} else {
    echo "Invalid or expired token.";
}
?>