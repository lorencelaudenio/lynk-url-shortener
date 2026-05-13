<?php
include 'config.php';

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

$response = [
    "username_exists" => false,
    "email_exists" => false
];

if (!empty($username)) {

    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response["username_exists"] = true;
    }
}

if (!empty($email)) {

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response["email_exists"] = true;
    }
}

echo json_encode($response);