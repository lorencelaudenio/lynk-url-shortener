</div> <!-- end app-wrapper -->
<div id="resultModal" class="url-modal">
    <div class="url-modal-content">

        <button class="url-modal-close" onclick="closeModal()">×</button>

        <div class="url-modal-icon">🎉</div>

        <div class="url-modal-title">
            Your short link is ready
        </div>

        <div class="url-modal-link">
            <a id="shortUrl" href="#" target="_blank"></a>
        </div>

        <button class="copy-btn" id="copyBtn">
            Copy Link
        </button>

        <div id="ctaSection" style="margin-top:20px; display:none;">
            <div class="cta-title">🚀 Unlock full link analytics</div>

            <p class="cta-text">
                Track clicks, manage all your links, and access your dashboard anytime.
            </p>

            <div class="cta-actions">
                <a href="register.php" class="cta-primary">Signup for FREE</a>
                <a href="login.php" class="cta-secondary">Login</a>
            </div>
        </div>

    </div>
</div>
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