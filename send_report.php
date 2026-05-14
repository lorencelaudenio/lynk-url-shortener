<?php
$url = $_POST['url'] ?? '';
$reason = $_POST['reason'] ?? '';
$details = $_POST['details'] ?? '';

if (empty($reason)) {
    echo "error";
    exit;
}

$log = date("Y-m-d H:i:s") . " | URL: $url | Reason: $reason | Details: $details\n";

file_put_contents("reports.txt", $log, FILE_APPEND);

echo "success";
?>