</div> <!-- end app-wrapper -->

<footer class="footer">
    <div class="footer-inner">

        <div class="footer-left">
            © <?= date("Y"); ?> Lynk URL Shortener
        </div>

        <div class="footer-right">
            <a href="privacy-policy.php">Privacy Policy</a>
            <a href="terms-of-service.php">Terms of Service</a>
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