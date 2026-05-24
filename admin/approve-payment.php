<?php
session_start();
include '../config.php';

$token = $_GET['token'] ?? '';

if ($token === '') {
    die("Invalid request");
}

/* FIND TOKEN */
$stmt = $conn->prepare("
    SELECT user_id 
    FROM payment_approvals 
    WHERE token=? AND used=0
    LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    die("Invalid or expired approval link");
}

$user_id = $result['user_id'];

/* Upgrade user */
$update = $conn->prepare("
    UPDATE users 
    SET plan='pro'
    WHERE id=?
");

$update->bind_param("i", $user_id);
$update->execute();

/* mark token as used */
$mark = $conn->prepare("
    UPDATE payment_approvals 
    SET used=1 
    WHERE token=?
");

$mark->bind_param("s", $token);
$mark->execute();

echo "User upgraded to PRO successfully.";
?>