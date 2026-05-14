<?php
include 'includes/header.php';

$url = $_GET['url'] ?? '';
?>

<div class="container">
  <h2>🚨 Report Abuse</h2>

  <?php if (!empty($url)) { ?>
    <p>Reporting: <?php echo htmlspecialchars($url); ?></p>
  <?php } ?>

  <form id="reportForm">
  <input type="hidden" name="url" value="<?php echo htmlspecialchars($url); ?>">

  <select name="reason" class="input" required>
    <option value="">Select reason</option>
    <option value="spam">Spam</option>
    <option value="scam">Scam</option>
    <option value="hate">Hate Speech</option>
  </select>

  <textarea name="details" class="input"></textarea>

  <button type="submit" class="btn btn-danger">Submit Report</button>
</form>
</div>

<script>
document.getElementById("reportForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const res = await fetch("send_report.php", {
        method: "POST",
        body: formData
    });

    const text = await res.text();

    if (text === "success") {
        alert("Report submitted successfully!");
        this.reset();
    } else {
        alert("Failed to submit report!");
    }
});
</script>

<?php include 'includes/footer.php'; ?>