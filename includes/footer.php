</div> <!-- end app-wrapper -->

<footer class="footer">
    <div class="footer-inner">

        <div class="footer-left">
            © <?= date("Y"); ?> Lynk URL Shortener
        </div>

        <div class="footer-right">
            <a href="about.php">About</a>
            <a href="privacy-policy.php">Privacy Policy</a>
            <a href="terms-of-service.php">Terms of Service</a>
        </div>
<div style="text-align:center; padding:20px;">
  <a href="report.php" style="color:red;text-decoration:none;">
    Report Abuse
  </a>
</div>

    </div>
</footer>

<div id="toast" class="toast"></div>

<div id="resultModal" class="url-modal">
    <div class="url-modal-content">

        <!-- Close button -->
        <button class="url-modal-close" onclick="closeModal()">×</button>

        <!-- Icon -->
        <div class="url-modal-icon">🎉</div>

        <!-- Title -->
        <div class="url-modal-title">
            Your short link is ready
        </div>

        <!-- Link box -->
<div class="url-form-group">

    <label class="url-label">Your Short Link</label>

    <div class="url-input-group">

        <div class="url-input-box">
            <a id="shortUrl" href="#" target="_blank"></a>
        </div>

        <button class="url-copy-btn" id="copyBtn" title="Copy link">
            📋
        </button>

    </div>

</div>        <!-- CTA section -->
<div id="ctaSection" class="url-cta-exclusive">

    <div class="url-cta-title">🚀 Unlock full link analytics</div>

    <p class="url-cta-text">
        Track clicks, manage all your links, and access your dashboard anytime.
    </p>

    <div class="url-cta-actions">
        <a href="register.php" class="url-cta-primary">Signup for FREE</a>
        <a href="login.php" class="url-cta-secondary">Login</a>
    </div>

</div>

<!-- GLOBAL JS (ONLY ONCE) -->
<script src="assets/js/app.js?v=<?= filemtime(__DIR__ . '/../assets/js/app.js') ?>"></script>

<?php if (!empty($_SESSION['toast'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    showToast(
        "<?= addslashes($_SESSION['toast']['message']) ?>",
        "<?= $_SESSION['toast']['type'] ?>"
    );
});
</script>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

</body>
</html>