<?php
include 'config.php';
include 'rate_limit.php';

$ip = $_SERVER['REMOTE_ADDR'];

if (!rateLimit("login_$ip", 5, 60)) {
    die("Too many requests. Please wait a moment.");
}

$code = $_GET['code'];

$stmt = $conn->prepare(
    "SELECT * FROM links WHERE short_code=?"
);

$stmt->bind_param("s", $code);

$stmt->execute();

$result = $stmt->get_result();

if($row = $result->fetch_assoc()) {

    $update = $conn->prepare(
        "UPDATE links
         SET clicks = clicks + 1
         WHERE id=?"
    );

    $update->bind_param("i", $row['id']);

    $update->execute();

    header("Location: " . $row['original_url']);
    exit;
}

http_response_code(404);
include "404.php";
exit;
?>