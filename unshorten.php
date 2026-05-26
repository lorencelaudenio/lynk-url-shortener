<?php
$bodyClass = "auth-page";
include 'includes/header.php';
?>

<body class="<?= $bodyClass ?? '' ?>">

<div class="page-center">
    <div class="auth-container">

        <div class="auth-title">
            <a href="https://lynk.page.gd/" class="logo lynk-logo">
                Lyn<span>k</span>
            </a>
        </div>

        <div class="auth-subtitle">
            Unshorten URL
        </div>

        <form id="unshortForm">

            <div class="form-group">
                <label>Paste Short URL</label>
                <input type="url" id="shortUrl" class="input" placeholder="https://bit.ly/abc123" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Unshorten
            </button>

        </form>

        <div id="result" style="margin-top:20px;color:#cbd5e1;"></div>

    </div>
</div>

<script>
document.getElementById("unshortForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const url = document.getElementById("shortUrl").value;

    const formData = new FormData();
    formData.append("url", url);

    const res = await fetch("unshorten_process.php", {
        method: "POST",
        body: formData
    });

    const data = await res.json();

    const resultDiv = document.getElementById("result");

    if (data.success) {
        resultDiv.innerHTML = `
            <p><b>Final URL:</b></p>
            <a href="${data.final}" target="_blank" style="color:#60a5fa;">
                ${data.final}
            </a>
        `;
    } else {
        resultDiv.innerHTML = `<p style="color:red;">${data.message}</p>`;
    }
});
</script>

<?php include 'includes/footer.php'; ?>