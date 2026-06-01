<?php
include 'config.php';

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if(isset($_POST['update'])) {

    $id = $_POST['id'];
    $url = $_POST['url'];
    $user_id = $_SESSION['user_id'];

    if(filter_var($url, FILTER_VALIDATE_URL)) {

        $stmt = $conn->prepare(
            "UPDATE links 
             SET original_url=? 
             WHERE id=? AND user_id=?"
        );

        $stmt->bind_param("sii", $url, $id, $user_id);

        $stmt->execute();
    }
}

header("Location: dashboard.php");
exit;
?>