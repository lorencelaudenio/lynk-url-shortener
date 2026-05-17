<?php
include 'config.php';

$link_id = (int)($_GET['id'] ?? 0);

if($link_id > 0) {

    $stmt = $conn->prepare("
        UPDATE bio_links
        SET clicks = clicks + 1
        WHERE id = ?
    ");

    $stmt->bind_param("i", $link_id);
    $stmt->execute();

    // get actual URL
    $stmt = $conn->prepare("
        SELECT url FROM bio_links WHERE id=?
    ");

    $stmt->bind_param("i", $link_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    if($result) {
        header("Location: " . $result['url']);
        exit;
    }
}

header("Location: index.php");
exit;