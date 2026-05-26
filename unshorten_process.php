<?php
header('Content-Type: application/json');

$url = $_POST['url'] ?? '';

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid URL"
    ]);
    exit;
}

/* CURL FOLLOW REDIRECTS */
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, "Lynk Unshortener Bot");

curl_exec($ch);

$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if (!$finalUrl) {
    echo json_encode([
        "success" => false,
        "message" => "Could not resolve URL"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "final" => $finalUrl,
    "status" => $httpCode
]);