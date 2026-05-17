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
    Report abusive or harmful short links.
</div>
<h2 style="display:none;">Report Abuse</h2>



    <form id="reportForm">

        <div class="form-group">
        <label>Short Link</label>

        <div style="display:flex;align-items:stretch;">

        <span style="
            background:#0f172a;
            padding:12px 10px;
            border:1px solid #1f2937;
            border-right:none;
            color:#94a3b8;
            border-radius:8px 0 0 8px;
            font-size:13px;
            display:flex;
            align-items:center;
            white-space:nowrap;
        ">
            https://lynk.page.gd/
        </span>

        <input
            type="text"
            id="reportSlug"
            class="input"
            placeholder="abc123"
            style="
                border-radius:0 8px 8px 0;
                margin:0;
                height:44px;
                box-sizing:border-box;
            "
            required
        >

    </div>
    </div>

<div class="form-group">
    <label>Reason</label>
    <select name="reason" class="input" required>
        <option value="">Select reason</option>

        <option value="spam">Spam / Unwanted Ads</option>
        <option value="scam">Scam / Fraudulent Link</option>
        <option value="phishing">Phishing (Stealing Info)</option>
        <option value="malware">Malware / Virus</option>
        <option value="hate">Hate Speech / Abuse</option>
        <option value="adult">Adult / Explicit Content</option>
        <option value="violence">Violence / Harmful Content</option>
        <option value="impersonation">Impersonation / Fake Identity</option>
        <option value="copyright">Copyright Violation</option>
        <option value="other">Other</option>
    </select>
</div>

<div class="form-group">
    <label>Details (optional)</label>
    <textarea name="details" class="input"
        placeholder="Add more information (e.g. what happened, why this link is harmful)..."></textarea>

    <small style="color:#94a3b8;">
        Help us understand the issue clearly.
    </small>
</div>

    <button type="submit" class="btn btn-danger" id="submitBtn" disabled>
        Submit Report
    </button>

    </form>
    </div>
</div>


<script>
const slugInput = document.getElementById("reportSlug");
const submitBtn = document.getElementById("submitBtn");

function checkInput() {
    const value = slugInput.value.trim();

    // only allow letters, numbers, dash
    const validSlug = /^[a-zA-Z0-9\-]+$/.test(value);

    if (validSlug && value.length > 0) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = "1";
    } else {
        submitBtn.disabled = true;
        submitBtn.style.opacity = "0.5";
    }
}

slugInput.addEventListener("input", checkInput);
checkInput();

/* submit */
document.getElementById("reportForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // rebuild full URL
    const fullUrl = "https://lynk.page.gd/" + slugInput.value;
    formData.set("url", fullUrl);

    const res = await fetch("send_report.php", {
        method: "POST",
        body: formData
    });

    const text = await res.text();

    if (text === "success") {
        alert("Report submitted successfully!");
        slugInput.value = "";
        checkInput();
    }
    else if (text === "invalid_domain") {
        alert("Invalid short link.");
    }
    else {
        alert("Failed to submit report!");
    }
});
</script>

<?php include 'includes/footer.php'; ?>