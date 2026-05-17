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
<title>
@<?= htmlspecialchars($user['username']); ?> | Lynk Page
</title>
    <link rel="stylesheet" href="assets/css/bio.css?v=<?= filemtime('assets/css/bio.css') ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="description"
      content="<?= htmlspecialchars($user['bio'] ?? '@' . $user['username'] . ' on Lynk Page'); ?>">

<meta name="keywords"
      content="<?= htmlspecialchars($user['username']); ?>, Lynk Page, Link in Bio">

<meta name="author"
      content="<?= htmlspecialchars($user['username']); ?>">

<meta name="robots" content="index, follow">

<!-- Open Graph -->
<meta property="og:title"
      content="@<?= htmlspecialchars($user['username']); ?> | Lynk Page">

<meta property="og:description"
      content="<?= htmlspecialchars($user['bio'] ?? 'Visit my Lynk Page'); ?>">

<meta property="og:type" content="profile">

<meta property="og:url"
      content="https://lynk.page.gd/@<?= urlencode($user['username']); ?>">

<meta property="og:image"
      content="<?= !empty($user['avatar']) 
          ? htmlspecialchars($user['avatar']) 
          : 'https://lynk.page.gd/assets/images/default-avatar.png'; ?>">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">

<meta name="twitter:title"
      content="@<?= htmlspecialchars($user['username']); ?> | Lynk Page">

<meta name="twitter:description"
      content="<?= htmlspecialchars($user['bio'] ?? 'Visit my Lynk Page'); ?>">

<meta name="twitter:image"
      content="<?= !empty($user['avatar']) 
          ? htmlspecialchars($user['avatar']) 
          : 'https://lynk.page.gd/assets/images/default-avatar.png'; ?>">
    
</head>
<body>

<div class="bio-page">

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
    >

    <!-- text -->
    <div class="bio-link-content">

        <div class="bio-link-title">
            <?= htmlspecialchars($link['title']); ?>
        </div>

        <div class="bio-link-url">
            <?= htmlspecialchars($link['url']); ?>
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