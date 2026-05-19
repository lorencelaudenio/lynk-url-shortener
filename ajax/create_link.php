<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


header('Content-Type: application/json');

require '../config.php';
require '../rate_limit.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$url = trim($_POST['url'] ?? '');

$current_domain = strtolower($_SERVER['HTTP_HOST']);

$blocked_domains = [
    $current_domain, // auto-detect your site
    'localhost',
    '127.0.0.1'
];

$parsed = parse_url($url);
$host = strtolower($parsed['host'] ?? '');

foreach ($blocked_domains as $blocked) {

    if ($host === $blocked || str_contains($host, $blocked)) {

        echo json_encode([
            'status' => 'error',
            'message' => 'You cannot shorten this domain.'
        ]);
        exit;
    }
}
$custom = trim($_POST['custom_slug'] ?? '');

if (!filter_var($url, FILTER_VALIDATE_URL)) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid URL format.'
    ]);

    exit;
}

/* CUSTOM SLUG */
if (!empty($custom)) {

    $short = preg_replace('/[^a-zA-Z0-9\-]/', '', $custom);

    if (empty($short)) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid custom slug.'
        ]);

        exit;
    }

    $check = $conn->prepare(
        "SELECT id FROM links WHERE short_code=? LIMIT 1"
    );

    $check->bind_param("s", $short);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Custom slug already taken.'
        ]);

        exit;
    }

} else {

    $short = substr(md5(uniqid()), 0, 6);
}

/* CHECK EXISTING URL */
$check = $conn->prepare(
    "SELECT short_code 
     FROM links
     WHERE user_id=? AND original_url=?
     LIMIT 1"
);

$check->bind_param("is", $user_id, $url);
$check->execute();

$result = $check->get_result();

if ($row = $result->fetch_assoc()) {

    echo json_encode([
        'status' => 'info',
        'message' => 'Destination already exists.',
        'short_url' => 'https://lynk.page.gd/' . $row['short_code']
    ]);

    exit;
}

/* INSERT */
$stmt = $conn->prepare(
    "INSERT INTO links(user_id, original_url, short_code)
     VALUES(?,?,?)"
);

$stmt->bind_param("iss", $user_id, $url, $short);
$stmt->execute();

echo json_encode([
    'status' => 'success',
    'message' => 'New short link created!',
    'short_url' => 'https://lynk.page.gd/' . $short,
    'original_url' => $url
]);