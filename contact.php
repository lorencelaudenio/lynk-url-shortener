<?php
$bodyClass = "auth-page";
include 'includes/header.php';
?>

<body class="<?= $bodyClass ?? '' ?>">

<div class="page-center">
    <div class="auth-container">

        <div class="auth-title">
            <a href="https://cutthis.link/" class="logo lynk-logo">
                CutThis<span>.Link</span>
            </a>
        </div>

        <div class="auth-subtitle">
            Contact Us
        </div>

        <p style="color:#cbd5e1;line-height:1.7;margin-top:15px;">
            Need help with a short link, your account, or an issue on the platform?
            You can email us at
            <a href="mailto:cutthislink@gmail.com" style="color:#60a5fa;">
                cutthislink@gmail.com
            </a>.
        </p>

        <p style="color:#94a3b8;margin-top:10px;">
            For abusive, phishing, or harmful links, please use the
            <a href="report.php" style="color:#ef4444;">Report Abuse page</a>
            so we can properly track and review the case.
        </p>

        <p style="color:#94a3b8;margin-top:10px;">
            Before contacting us, please check the FAQ below.
        </p>

        <h3 style="margin-top:25px;">FAQ</h3>

        <div class="faq-item">
            <button class="faq-question">
                Can I use CutThis.Link without signing up?
                <span>+</span>
            </button>
            <div class="faq-answer">
                Yes. You can create short links without an account.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                Do I need an account to use CutThis.Link?
                <span>+</span>
            </button>
            <div class="faq-answer">
                No. But signing up allows you to manage and track your links.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                What can I do with my dashboard?
                <span>+</span>
            </button>
            <div class="faq-answer">
                You can create, edit, and delete your short links and view basic analytics.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                Can I customize my short links?
                <span>+</span>
            </button>
            <div class="faq-answer">
                Yes. You can set custom slugs for your links if they are available.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                How do I report a harmful link?
                <span>+</span>
            </button>
            <div class="faq-answer">
                Use the Report Abuse page. Reports are reviewed after email confirmation.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                I still have questions — where can I get help?
                <span>+</span>
            </button>
            <div class="faq-answer">
                You can contact us anytime at
                <a href="mailto:cutthislink@gmail.com" style="color:#60a5fa;">
                    cutthislink@gmail.com
                </a>.
            </div>
        </div>

    </div>
</div>

<script>
document.querySelectorAll(".faq-question").forEach(btn => {
    btn.addEventListener("click", () => {
        btn.parentElement.classList.toggle("active");
    });
});
</script>

<?php include 'includes/footer.php'; ?>