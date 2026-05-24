<?php

include 'config.php';

if(!isset($_GET['token'])) {
    die("Invalid token");
}

$token = $_GET['token'];

/*
==================================
FIND USER
==================================
*/
$stmt = $conn->prepare("
    SELECT id, email, previous_email, recovery_expires
    FROM users
    WHERE recovery_token=?
    LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if(!$user) {
    die("Invalid or expired token");
}

/*
==================================
CHECK EXPIRY
==================================
*/
if(strtotime($user['recovery_expires']) < time()) {
    die("Recovery link expired");
}

/*
==================================
CHECK IF WE HAVE PREVIOUS EMAIL
==================================
*/
if(empty($user['previous_email'])) {
    die("No previous email to restore");
}

/*
==================================
RESTORE EMAIL
==================================
*/
$stmt = $conn->prepare("
    UPDATE users
    SET email=?,
        pending_email=NULL,
        email_verify_token=NULL,
        token_expires=NULL,
        recovery_token=NULL,
        recovery_expires=NULL,
        previous_email=NULL
    WHERE id=?
");

$stmt->bind_param("si", $user['previous_email'], $user['id']);
$stmt->execute();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Secured</title>
</head>
<body style="background:#0f172a;color:white;font-family:Arial;text-align:center;padding:50px;">

    <h1>🔐 Account Secured</h1>

    <p>Your email has been restored successfully.</p>

    <p>You can now safely log in again.</p>

    <a href="login.php" style="color:#fff;background:#3b82f6;padding:12px 20px;text-decoration:none;border-radius:8px;">
        Go to Login
    </a>

</body>
</html>