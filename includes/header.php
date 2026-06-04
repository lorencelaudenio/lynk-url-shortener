<?php
$base_url = "/"; 
$usage = null;
$plan = 'free';
$used = 0;
$limit = 1000;

if (!empty($_SESSION['user_id'])) {

    include_once __DIR__ . '/../config.php';

    $stmt = $conn->prepare("
        SELECT urls_used, url_limit, plan
        FROM users 
        WHERE id=?
        LIMIT 1
    ");

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    $usage = $stmt->get_result()->fetch_assoc();

    $used  = (int)($usage['urls_used'] ?? 0);
    $limit = (int)($usage['url_limit'] ?? 1000);
    $plan  = strtolower(trim($usage['plan'] ?? 'free'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? 'CutThis.link' ?></title>
<meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'CutThis.link') ?>">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/style.css') ?>">
<link rel="icon" href="/assets/images/cutthislink-logo.png?v=2" type="image/png">

<meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Create and share your links with Lynk Page') ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XVKZ3HCS93"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XVKZ3HCS93');
</script>
</head>

<body>


<nav class="nav">
<div class="logo-wrapper">
<a href="<?= $base_url ?>" class="logo">CutThis<span>.Link</span></a>

    <?php if (!empty($_SESSION['user_id'])): ?>
        <?php if ($plan === 'free'): ?>
            <span class="plan-badge free">FREE</span>
        <?php else: ?>
            <span class="plan-badge pro">PRO</span>
        <?php endif; ?>
    <?php endif; ?>
</div>

    <div class="nav-links">

<?php if (!empty($_SESSION['user_id'])): ?>

    <a href="<?= $base_url ?>dashboard">Links</a>
    <a href="<?= $base_url ?>bio-settings">Bio</a>







    <div class="nav-user-dropdown nav-link">
        <span class="nav-user-trigger">
            <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?> ▾
        </span>

        <div class="nav-user-menu">
            <a href="<?= $base_url ?>profile">Profile Settings</a>
            <a href="<?= $base_url ?>logout">Logout</a>
        </div>
    </div>

<?php else: ?>

            <a href="<?= $base_url ?>login">Login</a>
<a href="<?= $base_url ?>register" class="btn-signup">Signup for FREE</a>
        <?php endif; ?>

    </div>
</nav>

<div class="app-wrapper">