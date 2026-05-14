<?php
session_start();
$pageTitle = "Terms of Service - Lynk URL Shortener";

include 'config.php';
include 'includes/header.php';
?>

<div class="page-center">

    <div class="auth-container" style="max-width:800px; text-align:left;">

        <div class="auth-title">
            Terms <span>of Service</span>
        </div>

        <div class="auth-subtitle">
            Please read these terms carefully before using Lynk.
        </div>

        <div style="margin-top:25px; color:#cbd5e1; line-height:1.8; font-size:14px;">

            <h3>1. Acceptance of Terms</h3>
            <p>
                By using Lynk URL Shortener, you agree to be bound by these Terms of Service.
                If you do not agree, please do not use the platform.
            </p>

            <h3>2. Description of Service</h3>
            <p>
                Lynk provides a URL shortening service that allows users to create
                short links and track basic click analytics.
            </p>

            <h3>3. User Responsibilities</h3>
            <p>
                You agree not to use this service for illegal, harmful, spam,
                phishing, or abusive activities. You are responsible for the links you create.
            </p>

            <h3>4. Prohibited Content</h3>
            <p>
                You may not shorten URLs that contain malware, phishing content,
                illegal material, or violate any applicable laws.
            </p>

            <h3>5. Account Security</h3>
            <p>
                You are responsible for maintaining the confidentiality of your account
                credentials. Any activity under your account is your responsibility.
            </p>

            <h3>6. Service Availability</h3>
            <p>
                We do not guarantee uninterrupted service. We may update, suspend,
                or discontinue the service at any time without notice.
            </p>

            <h3>7. Limitation of Liability</h3>
            <p>
                Lynk is provided "as is" without warranties. We are not responsible
                for any damages, losses, or issues caused by the use of shortened links.
            </p>

            <h3>8. Changes to Terms</h3>
            <p>
                We may update these Terms of Service at any time. Continued use of the
                service means you accept the updated terms.
            </p>

            <h3>9. Contact</h3>
            <p>
                For questions about these Terms, please contact the site administrator.
            </p>

        </div>

        <div style="margin-top:30px; text-align:center;">

            <a href="index.php" class="cta-secondary">
                ← Back to Homepage
            </a>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>