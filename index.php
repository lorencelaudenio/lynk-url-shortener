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

    <div class="hero-box">

        <div class="auth-title">
            Smart URL Shortener
        </div>

        <div class="auth-subtitle">
            Shorten long URLs instantly with analytics.
        </div>

        <div class="form-box">
    <form method="POST">
        <input class="input" type="url" name="url" placeholder="Paste your long URL here..." required>

        <button class="btn btn-primary" type="submit" name="shorten">
            Shorten URL
        </button>
    </form>
</div>

<?php if (!empty($short_url)): ?>
    <div class="result-box">

        <div class="result-icon">🎉</div>

        <div class="result-title">
            Your short link is ready
        </div>

        <div class="result-link-box">
            <a class="result-link" href="<?= $short_url ?>" target="_blank">
                <?= $short_url ?>
            </a>
        </div>

<button class="copy-btn"
    onclick="copyLink('<?= $short_url ?>', this)">
    Copy Link
</button>

    </div>
<?php endif; ?>

<?php if ($show_cta): ?>
    <div class="cta-box">

        <div class="cta-title">
            🚀 Unlock full link analytics
        </div>

        <p class="cta-text">
            Track clicks, manage all your links, and access your dashboard anytime.
        </p>

        <div class="cta-actions">
            <a href="register.php" class="cta-primary">
                Create Free Account
            </a>

            <a href="login.php" class="cta-secondary">
                Login
            </a>
        </div>

    </div>
<?php endif; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>