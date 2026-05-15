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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<style>
.bio-page {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.hero-box {
    width: 100%;
    max-width: 420px;
    text-align: center;
    padding: 20px;
}

.profile-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #1f2937;
}

.bio-name {
    font-size: 20px;
    margin-top: 10px;
}

.bio-text {
    font-size: 14px;
    color: #cbd5e1;
    margin-bottom: 20px;
}

/* LINKS */
.bio-link {
    display: block;
    padding: 12px;
    margin: 10px 0;
    background: #0f172a;
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    border: 1px solid #1f2937;
    transition: 0.2s ease;
}

.bio-link:hover {
    background: #1e293b;
}

/* JOIN BUTTON */
.join-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 16px;
    background: #3b82f6;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 13px;
}

/* RESPONSIVE */
@media (max-width: 480px) {
    .hero-box {
        padding: 15px;
    }

    .bio-name {
        font-size: 18px;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
    }

    .bio-link {
        font-size: 14px;
        padding: 14px;
    }
}
</style>
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
<a href="register.php" class="join-btn">
    Join Lynk Page
</a>    </div>

</div>
</body>
</html>