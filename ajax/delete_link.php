<?php
session_start();

header('Content-Type: application/json');

require '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid ID'
    ]);
    exit;
}

/* ensure user owns the link */
$check = $conn->prepare(
    "SELECT id FROM links WHERE id=? AND user_id=?"
);

$check->bind_param("ii", $id, $user_id);
$check->execute();

if ($check->get_result()->num_rows === 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Not found'
    ]);
    exit;
}

/* delete */
$stmt = $conn->prepare(
    "DELETE FROM links WHERE id=? AND user_id=?"
);

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

/* 🔥 GET UPDATED TOTAL LINKS */
$countStmt = $conn->prepare(
    "SELECT COUNT(*) AS total FROM links WHERE user_id=?"
);

$countStmt->bind_param("i", $user_id);
$countStmt->execute();

$countResult = $countStmt->get_result()->fetch_assoc();
$total_links = $countResult['total'] ?? 0;

/* RESPONSE */
echo json_encode([
    'status' => 'success',
    'message' => 'Link deleted successfully',
    'id' => $id,
    'total_links' => $total_links
]);