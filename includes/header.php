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

<title>Lynk</title>

<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>

<body>

<nav class="nav">
    <a href="index.php" class="logo">Lyn<span>k</span></a>

    <div class="nav-links">
        <a href="about.php">About</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="app-wrapper">