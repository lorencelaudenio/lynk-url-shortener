<?php

// -------------------- SESSION --------------------
if (session_status() === PHP_SESSION_NONE) {

    $sessionPath = __DIR__ . '/sessions';

    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0755, true);
    }

    session_save_path($sessionPath);
    session_start();
}

// -------------------- LOAD .ENV --------------------
$envPath = __DIR__ . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        [$key, $value] = array_pad(explode('=', $line, 2), 2, null);

        if ($key && $value !== null) {
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// -------------------- DATABASE --------------------
$host     = $_ENV['DB_HOST'] ?? '';
$dbname   = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';
$port     = $_ENV['DB_PORT'] ?? 3306;

// -------------------- CONNECTION --------------------
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// -------------------- SMTP --------------------
define('SMTP_EMAIL', $_ENV['SMTP_EMAIL'] ?? '');
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD'] ?? '');
define('SMTP_NAME', $_ENV['SMTP_NAME'] ?? 'Support');
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? '');

// -------------------- USER HELPER --------------------
function currentUser($conn) {
    if (empty($_SESSION['user_id'])) return null;

    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

?>