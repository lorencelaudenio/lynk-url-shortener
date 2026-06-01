<?php
include 'config.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if(!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

$link_id = $_POST['link_id'] ?? null;

if(!empty($_FILES['thumbnail'])) {

    $file = $_FILES['thumbnail'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];

    if(in_array($ext, $allowed)) {

        if(!is_dir("uploads/link_thumbnails")) {
            mkdir("uploads/link_thumbnails", 0777, true);
        }

        $filename = time() . "_" . rand(1000,9999) . "." . $ext;
        $path = "uploads/link_thumbnails/" . $filename;

        move_uploaded_file($file['tmp_name'], $path);

        $stmt = $conn->prepare("
            UPDATE bio_links
            SET thumbnail=?
            WHERE id=? AND user_id=?
        ");

        $stmt->bind_param("sii", $path, $link_id, $user_id);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'url' => $path
        ]);
        exit;
    }
}

echo json_encode(['success' => false]);