<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

include 'includes/header.php';

$error = "";
$short_url = "";
$show_cta = false;

if (isset($_POST['shorten'])) {

    $url = trim($_POST['url']);

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $error = "Invalid URL format.";
    } else {

        $short = substr(md5(uniqid()), 0, 6);

        $stmt = $conn->prepare(
            "INSERT INTO links(user_id, original_url, short_code)
             VALUES(NULL, ?, ?)"
        );

        $stmt->bind_param("ss", $url, $short);

        if ($stmt->execute()) {
            $short_url = "https://lynk.page.gd/$short";
            $show_cta = true;
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>

<div class="page-center">

    <div class="auth-container" style="max-width:600px;">

        <div class="auth-title">
            Smart URL Shortener
        </div>

        <div class="auth-subtitle">
            Shorten long URLs instantly with analytics.
        </div>

        <form method="POST">

            <input class="input" type="url" name="url" placeholder="Paste your long URL here..." required>

            <button class="btn btn-primary" type="submit" name="shorten">
                Shorten URL
            </button>

        </form>

        <?php if (!empty($short_url)): ?>
            <div class="alert alert-success">
                <a href="<?= $short_url ?>" target="_blank">
                    <?= $short_url ?>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($show_cta): ?>
            <div class="cta-box">
                🚀 Want analytics & dashboard?
            </div>
        <?php endif; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>