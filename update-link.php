<?php

include 'config.php';

if(!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'];

$id = (int)($_POST['id'] ?? 0);

$title = trim($_POST['title'] ?? '');
$url = trim($_POST['url'] ?? '');

if(
    !empty($title) &&
    filter_var($url, FILTER_VALIDATE_URL)
) {

    $stmt = $conn->prepare("
        UPDATE bio_links
        SET title=?, url=?
        WHERE id=? AND user_id=?
    ");

    $stmt->bind_param(
        "ssii",
        $title,
        $url,
        $id,
        $user_id
    );

    $stmt->execute();
}

header("Location: bio-settings.php");
exit;