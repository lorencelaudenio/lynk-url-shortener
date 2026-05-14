<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? 'Lynk' ?></title>
<link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime('assets/css/style.css') ?>">
</head>

<body>

<nav class="nav">
    <a href="index.php" class="logo">Lyn<span>k</span></a>

    <div class="nav-links">
        <div class="nav-user">
<?php if (!empty($_SESSION['user_id'])): ?>
    <div class="nav-user">
        <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
    </div>
<?php endif; ?></div>
        

        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="app-wrapper">