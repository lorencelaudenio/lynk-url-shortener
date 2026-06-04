<?php

$bodyClass = "auth-page";
include 'includes/header.php';

?>
<body class="<?= $bodyClass ?? '' ?>">
<div class="page-center">
    <div class="auth-container">
        <div class="auth-title">

<a href="https://cutthis.link/" class="logo lynk-logo">
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
            https://cutthis.link/
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
    <div class="form-group">
    <label>Email Address</label>
    <input
        type="email"
        name="email"
        class="input"
        placeholder="you@example.com"
        required
    >
</div>
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

    const validSlug = /^[a-zA-Z0-9\-]+$/.test(value);

    submitBtn.disabled = !(validSlug && value.length > 0);
    submitBtn.style.opacity = submitBtn.disabled ? "0.5" : "1";
}

slugInput.addEventListener("input", checkInput);
checkInput();

/* submit */
document.getElementById("reportForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const fullUrl = "https://cutthis.link/" + slugInput.value.trim();
    formData.set("url", fullUrl);

    try {
        const res = await fetch("send_report.php", {
    method: "POST",
    body: formData
});

const text = await res.text();

console.log("RAW RESPONSE:", text);
alert("RESPONSE: " + text);

        if (text === "success") {
            alert("Report submitted successfully!");
            slugInput.value = "";
            checkInput();
        }
        else if (text === "invalid_domain") {
            alert("Invalid short link.");
        }
        else if (text === "mail_failed") {
            alert("Email failed to send.");
        }
        else {
            alert("Failed to submit report!");
        }

    } catch (error) {
        console.error(error);
        alert("Network error!");
    }
});
</script>

<!-- FAQ ACCORDION -->
<div style="
    margin-top:30px;
    background:#0f172a;
    border:1px solid #1f2937;
    border-radius:12px;
    padding:22px;
">

    <p style="color:#cbd5e1;line-height:1.7;margin-bottom:22px;">
        Report phishing, malware, spam, copyright infringement, or other harmful use of a Lynk short link.
        Signed-in users can submit reports directly, while guest reports may require email confirmation before review.
    </p>

    <h3 style="margin-bottom:18px;">FAQ</h3>

    <div class="faq-item">
        <button class="faq-question">
            What can I report here?
            <span>+</span>
        </button>
        <div class="faq-answer">
            You can report phishing pages, malware, scams, spam, impersonation,
            copyright violations, and other harmful or abusive use of a Lynk short link.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            Why do you ask for my email?
            <span>+</span>
        </button>
        <div class="faq-answer">
            Some reports may require email verification before review.
            We may also contact you if additional information is needed regarding your report.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            Do I need to provide details?
            <span>+</span>
        </button>
        <div class="faq-answer">
            Details are optional for most report types, but adding more context
            helps us review the report faster and more accurately.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            What happens after I submit a report?
            <span>+</span>
        </button>
        <div class="faq-answer">
            Reports are reviewed by our team. Links violating our policies
            may be disabled, blocked, or permanently removed.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">
            I still have questions — where can I get help?
            <span>+</span>
        </button>
        <div class="faq-answer">
            Contact us anytime at
            <a href="mailto:cutthislink@gmail.com"
               style="color:#60a5fa;text-decoration:none;">
               cutthislink@gmail.com
            </a>
        </div>
    </div>

</div>

<style>

</style>

<script>
document.querySelectorAll(".faq-question").forEach(btn => {
    btn.addEventListener("click", () => {
        const item = btn.parentElement;
        item.classList.toggle("active");
    });
});
</script>

<?php include 'includes/footer.php'; ?>