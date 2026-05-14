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
    <title>@<?= htmlspecialchars($user['username']); ?></title>

    <link rel="stylesheet" href="assets/css/bio.css">

    
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
                href="<?= htmlspecialchars($link['url']); ?>"
                target="_blank"
                class="bio-link"
            >
                <?= htmlspecialchars($link['title']); ?>
            </a>

        <?php endwhile; ?>

    </div>

</div>

</body>
</html>