<?php
include 'config.php';

$username = $_GET['username'] ?? '';

$stmt = $conn->prepare(
    "SELECT * FROM users WHERE username=? LIMIT 1"
);

$stmt->bind_param("s", $username);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if(!$user){
    include '404.php';
    exit;
}

$linkStmt = $conn->prepare(
    "SELECT * FROM bio_links
     WHERE user_id=?
     ORDER BY id DESC"
);

$linkStmt->bind_param("i", $user['id']);
$linkStmt->execute();

$links = $linkStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<?php
$usernameClean = htmlspecialchars($user['username'] ?? '');
$bioText = $user['bio'] ?? "@$usernameClean on Lynk Page";
$bioShort = htmlspecialchars(mb_substr(strip_tags($bioText), 0, 160));

$profileUrl = "https://lynk.page.gd/@" . urlencode($user['username'] ?? '');

$ogImage = (!empty($user['avatar']))
    ? htmlspecialchars($user['avatar'])
    : "https://lynk.page.gd/assets/images/default-avatar.png";
?>

<title>@<?= $usernameClean ?> | Lynk Page</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- SEO -->
<meta name="description" content="<?= htmlspecialchars($bioShort) ?>">
<meta name="keywords" content="<?= $usernameClean ?>, lynk page, link in bio, social links">
<meta name="author" content="<?= $usernameClean ?>">
<meta name="robots" content="index, follow">

<link rel="canonical" href="<?= $profileUrl ?>">

<!-- OPEN GRAPH -->
<meta property="og:title" content="@<?= $usernameClean ?> | Lynk Page">
<meta property="og:description" content="<?= $bioShort ?>">
<meta property="og:type" content="profile">
<meta property="og:url" content="<?= $profileUrl ?>">
<meta property="og:image" content="<?= $ogImage ?>">
<meta property="og:site_name" content="Lynk Page">

<!-- TWITTER -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@<?= $usernameClean ?> | Lynk Page">
<meta name="twitter:description" content="<?= $bioShort ?>">
<meta name="twitter:image" content="<?= $ogImage ?>">

<!-- CSS -->
<link rel="stylesheet" href="assets/css/bio.css?v=<?= filemtime('assets/css/bio.css') ?>">
</head>
<body>

<div class="bio-page theme-<?= htmlspecialchars($user['theme'] ?? 'default'); ?>">

    <div class="hero-box">

        <img 
            src="<?= !empty($user['avatar']) 
                ? htmlspecialchars($user['avatar']) 
                : 'https://via.placeholder.com/120'; ?>"
            class="profile-avatar"
        >

        <h1 class="bio-name">
            @<?= htmlspecialchars($user['username']); ?>
        </h1>

        <p class="bio-text">
            <?= htmlspecialchars($user['bio'] ?? 'Welcome to my Lynk page'); ?>
        </p>

        <?php while($link = $links->fetch_assoc()): ?>

<a
    href="track-click.php?id=<?= $link['id']; ?>"
    target="_blank"
    class="bio-link-card"
>

    <!-- thumbnail -->
<img
    src="<?= !empty($link['thumbnail']) 
        ? htmlspecialchars($link['thumbnail']) 
        : 'https://www.google.com/s2/favicons?sz=128&domain=' . parse_url($link['url'], PHP_URL_HOST); ?>"
    class="bio-link-thumb"
/>

    <!-- text -->
    <div class="bio-link-content">

        <div class="bio-link-title">
            <?= htmlspecialchars($link['title']); ?>
        </div>

        

    </div>

</a>

<?php endwhile; ?>
    </div>
<a href="register.php" class="join-float-btn">
    Join Lynk Page
</a>
</div>
</body>
</html>