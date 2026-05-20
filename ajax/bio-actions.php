<?php
session_start();
include '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

/* -------------------------
   SAVE BIO
------------------------- */
if ($action === 'save_profile') {

    $bio = trim($_POST['bio']);
    $theme = trim($_POST['theme']);

    $stmt = $conn->prepare("UPDATE users SET bio=?, theme=? WHERE id=?");
    $stmt->bind_param("ssi", $bio, $theme, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Profile updated']);
    exit;
}

/* -------------------------
   SAVE THEME ONLY
------------------------- */
if ($action === 'save_theme') {

    $theme = trim($_POST['theme']);

    $stmt = $conn->prepare("UPDATE users SET theme=? WHERE id=?");
    $stmt->bind_param("si", $theme, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

/* -------------------------
   DELETE LINK
------------------------- */
if ($action === 'delete_link') {

    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM bio_links WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'id' => $id]);
    exit;
}

/* -------------------------
   ADD LINK
------------------------- */

if($action === "add_link") {

    $title = trim($_POST['title']);
    $url = trim($_POST['url']);

    if(empty($title) || !filter_var($url, FILTER_VALIDATE_URL)) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid input"
        ]);
        exit;
    }

    $thumbnailPath = "https://www.google.com/s2/favicons?sz=128&domain=" . parse_url($url, PHP_URL_HOST);

    $stmt = $conn->prepare("INSERT INTO bio_links (user_id, title, url, thumbnail) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $url, $thumbnailPath);
    $stmt->execute();

    $id = $stmt->insert_id;

echo json_encode([
    "success" => true,
    "id" => $id,
    "title" => $title,
    "url" => $url,
    "thumbnail" => $thumbnailPath
]);
exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);