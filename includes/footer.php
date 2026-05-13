</div> <!-- end app-wrapper -->

<footer class="footer">
    <p>© <?php echo date("Y"); ?> Lynk URL Shortener</p>
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