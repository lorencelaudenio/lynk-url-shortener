<?php

include 'config.php';

$slug = $_GET['slug'] ?? '';

/*
|--------------------------------------------------------------------------
| RESERVED SYSTEM PAGES
|--------------------------------------------------------------------------
*/

$reserved = [
    'login',
    'register',
    'dashboard',
    'logout',
    'about',
    'report',
    'forgot-password',
    'reset-password',
    'index',
    'bio-settings'
];

if (in_array($slug, $reserved)) {
    header("Location: $slug");
    exit;
}

/*
|--------------------------------------------------------------------------
| CHECK USERNAME
|--------------------------------------------------------------------------
*/

$userStmt = $conn->prepare(
    "SELECT id FROM users WHERE username=? LIMIT 1"
);

$userStmt->bind_param("s", $slug);
$userStmt->execute();

$user = $userStmt->get_result()->fetch_assoc();

if ($user) {

    header("Location: bio.php?username=$slug");
    exit;
}

/*
|--------------------------------------------------------------------------
| CHECK SHORT LINK
|--------------------------------------------------------------------------
*/

$linkStmt = $conn->prepare(
    "SELECT * FROM links WHERE short_code=? LIMIT 1"
);

$linkStmt->bind_param("s", $slug);
$linkStmt->execute();

$link = $linkStmt->get_result()->fetch_assoc();

if ($link) {

    $update = $conn->prepare(
        "UPDATE links
         SET clicks = clicks + 1
         WHERE id=?"
    );

    $update->bind_param("i", $link['id']);
    $update->execute();

    header("Location: " . $link['original_url']);
    exit;
}

/*
|--------------------------------------------------------------------------
| NOT FOUND
|--------------------------------------------------------------------------
*/

include '404.php';