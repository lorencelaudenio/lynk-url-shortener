<?php include 'includes/header.php'; ?>
<div style="text-align:center;margin-bottom:25px;">

    <img
        src="assets/images/cutthislink-link-not-found.webp"
        alt="Link Not Found"
        style="
            width:100%;
            max-width:280px;
            opacity:0.95;
        "
    >

</div>

<div class="container" style="text-align:center;padding:60px 20px;">

    <h1 style="font-size:48px;margin-bottom:10px;">😕 Link Not Found</h1>

    <p style="color:#94a3b8;font-size:16px;max-width:500px;margin:0 auto 30px;">
        The short link you tried to access doesn’t exist or may have been removed.
    </p>

    <div style="background:#0f172a;padding:20px;border-radius:12px;max-width:500px;margin:0 auto 30px;text-align:left;color:#cbd5e1;">

        <p style="margin:0 0 10px;"><strong>Possible reasons:</strong></p>

        <ul style="margin:0;padding-left:18px;color:#94a3b8;">
            <li>The link was typed incorrectly</li>
            <li>The link has expired or been deleted</li>
            <li>The owner removed it</li>
        </ul>

    </div>

    <div class="action-bar">

    <a href="index.php" class="btn btn-primary btn-inline">
        🏠 Go to Homepage
    </a>

    <a href="dashboard.php" class="btn btn-dark btn-inline">
        📊 Go to Dashboard
    </a>

    <a href="report.php" class="btn btn-danger btn-inline">
        🚨 Report Issue
    </a>

</div>

</div>

<?php include 'includes/footer.php'; ?>