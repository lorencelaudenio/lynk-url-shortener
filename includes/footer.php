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

<div id="resultModal" class="lynk-modal">

    <div class="lynk-modal-content">

        <!-- Close -->
        <button class="lynk-modal-close" onclick="closeModal()">×</button>

        <!-- Icon -->
        <div class="lynk-modal-icon">🔗</div>

        <!-- Title -->
        <div class="lynk-modal-title">
            Your Lynk is ready
        </div>

        <!-- Form group -->
        <div class="lynk-form-group">

            <label class="lynk-label">Short Link</label>

            <div class="lynk-input-group">

                <div class="lynk-input-box">
                    <a id="shortUrl" href="#" target="_blank"></a>
                </div>

                <button class="lynk-copy-btn" id="copyBtn" title="Copy">
                    📋
                </button>

            </div>

            <div class="lynk-toast" id="copyToast">Copied!</div>

        </div>

        <!-- CTA -->
        <div id="ctaSection" class="lynk-cta">

            <div class="lynk-cta-title">🚀 Unlock full analytics</div>

            <p class="lynk-cta-text">
                Track clicks, manage links, and grow your reach with Lynk dashboard.
            </p>

            <div class="lynk-cta-actions">
                <a href="register.php" class="lynk-btn-primary">Signup for FREE</a>
                <a href="login.php" class="lynk-btn-secondary">Login</a>
            </div>

        </div>

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