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

<div class="home-viral">

    <!-- HERO -->
    <section class="viral-hero">

        <div class="viral-hero-inner">

            <div class="viral-badge">⚡ Free • Link in Bio + URL Shortener</div>

            <h1 class="viral-title">
                One Link.<br>
                Endless Control.
            </h1>

            <p class="viral-subtitle">
                Shorten URLs, track clicks, and build your own link-in-bio page — all in one platform.
            </p>

            <form method="POST" class="viral-form">

                <input
                    type="url"
                    name="url"
                    placeholder="Paste your long URL here..."
                    required
                    class="viral-input"
                >

                <button type="submit" name="shorten" class="viral-btn">
                    ✨ Generate Short Link
                </button>

            </form>

            <?php if (!empty($short_url)): ?>
                <div class="viral-result">
                    <span>🎉 Your short link is ready:</span>
                    <a href="<?= $short_url ?>" target="_blank">
                        <?= $short_url ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>

        <div class="viral-glow"></div>

    </section>

    <!-- SOCIAL PROOF -->
    <section class="viral-proof">

        <div class="proof-item">
            <strong>50K+</strong>
            <span>Links Generated</span>
        </div>

        <div class="proof-item">
            <strong>Realtime</strong>
            <span>Click Tracking</span>
        </div>

        <div class="proof-item">
            <strong>99.9%</strong>
            <span>Uptime Reliability</span>
        </div>

    </section>

    <!-- FEATURES -->
    <section class="viral-features">

        <h2>Everything creators need in one tool</h2>

        <div class="viral-grid">

            <div class="viral-card">
                ⚡
                <h3>Instant Short Links</h3>
                <p>Create short URLs in seconds — no setup needed.</p>
            </div>

            <div class="viral-card">
                📊
                <h3>Live Analytics</h3>
                <p>Track clicks, traffic sources, and performance.</p>
            </div>

            <div class="viral-card">
                🔗
                <h3>Link-in-Bio Pages</h3>
                <p>Turn your links into a personal landing page.</p>
            </div>

            <div class="viral-card">
                🔒
                <h3>Secure Redirects</h3>
                <p>Fast, safe, and reliable link system.</p>
            </div>

        </div>

    </section>

    <!-- DEMO STRIP -->
    <section class="viral-demo">

        <div class="demo-card">
            <span>Before</span>
            <code>https://yourstore.com/product/item?id=928391&ref=campaign&utm_source=fb</code>
        </div>

        <div class="demo-arrow">→</div>

        <div class="demo-card highlight">
            <span>After</span>
            <code>lynk.page.gd/x7k2p9</code>
        </div>

    </section>

    <!-- CTA -->
    <section class="viral-cta">

        <h2>Start sharing smarter links today</h2>
        <p>No credit card. No setup. Just paste and share.</p>

        <a href="register.php" class="viral-cta-btn">
            Get Started Free
        </a>

    </section>

</div>





<?php include 'includes/footer.php'; ?>