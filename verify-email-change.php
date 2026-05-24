<?php

include 'config.php';
require_once __DIR__ . "/includes/mailer.php";

if(!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = trim($_GET['token']);

/*
==================================
GET USER BY TOKEN
==================================
*/
$stmt = $conn->prepare("
    SELECT id, username, email, pending_email, token_expires
    FROM users
    WHERE email_verify_token=?
    LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if(!$user) {
    die("Invalid or expired token.");
}

/*
==================================
CHECK EXPIRY
==================================
*/
if(strtotime($user['token_expires']) < time()) {
    die("Verification link expired.");
}

/*
==================================
SET VALUES
==================================
*/
$oldEmail = $user['email'];
$newEmail = $user['pending_email'];

/*
==================================
GENERATE RECOVERY TOKEN
==================================
*/
$recoveryToken = bin2hex(random_bytes(32));
$recoveryExpires = date("Y-m-d H:i:s", strtotime("+1 hour"));

/*
==================================
SAVE UPDATE + RECOVERY DATA
==================================
*/
$stmt = $conn->prepare("
    UPDATE users
    SET email=?,
        pending_email=NULL,
        email_verify_token=NULL,
        token_expires=NULL,
        previous_email=?,
        recovery_token=?,
        recovery_expires=?
    WHERE id=?
");

$stmt->bind_param(
    "ssssi",
    $newEmail,
    $oldEmail,
    $recoveryToken,
    $recoveryExpires,
    $user['id']
);

$stmt->execute();

/*
==================================
SEND OLD EMAIL NOTIFICATION
(with recovery link inside your mailer)
==================================
*/
sendEmailChangeNoticeToOld(
    $oldEmail,
    $user['username'],
    $newEmail,
    $recoveryToken
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Email Updated</title>

<style>
body{
    background:#0f172a;
    color:#e5e7eb;
    font-family:Arial;
    display:flex;
    align-items:center;
    justify-content:center;
    height:100vh;
}
.card{
    background:#111827;
    padding:40px;
    border-radius:12px;
    text-align:center;
    max-width:500px;
}
a{
    display:inline-block;
    margin-top:20px;
    background:#3b82f6;
    color:#fff;
    padding:12px 20px;
    text-decoration:none;
    border-radius:8px;
}
</style>

</head>
<body>

<div class="card">

    <h1>✅ Email Successfully Updated</h1>

    <p>Your email has been verified and updated.</p>

    <p>You can now continue using your account normally.</p>

    <a href="login.php">Go to Login</a>

</div>

</body>
</html>