<?php
session_start();
$pageTitle = "Lynk - Smart URL Shortener";
$pageDescription = "Free URL shortener with tracking, analytics, and dashboard.";
$pageKeywords = "url shortener, link tracker, analytics, shorten link";
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
<title><?= $pageTitle ?? 'Lynk URL Shortener' ?></title>

<meta name="description" content="<?= $pageDescription ?? 'Free URL shortener with tracking, analytics, and dashboard.' ?>">

<meta name="keywords" content="<?= $pageKeywords ?? 'url shortener, link tracker, analytics, shorten link' ?>">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="page-center">

    <div class="hero-box">
        <div class="hero-image">
    <img src="assets/images/link%20in%20bio.webp" alt="Lynk Hero Image">
</div>

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
<script>
    window.__SHORT_URL__ = "<?= $short_url ?>";
    window.__SHOW_CTA__ = true;
</script>
<?php endif; ?>

    </div>

</div>





<?php include 'includes/footer.php'; ?>