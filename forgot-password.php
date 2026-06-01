<?php

if(isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}

$pageTitle = "Forgot Password - Lynk";

include 'config.php';
include 'includes/mailer.php';

$success = "";
$error = "";

if(isset($_POST['reset'])) {

    $email = trim($_POST['email']);

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email=? LIMIT 1"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()) {

        $token = bin2hex(random_bytes(32));

        $expires = date(
            "Y-m-d H:i:s",
            strtotime("+1 hour")
        );

        $update = $conn->prepare(
            "UPDATE users
             SET reset_token=?, reset_expires=?
             WHERE id=?"
        );

        $update->bind_param(
            "ssi",
            $token,
            $expires,
            $user['id']
        );

        $update->execute();

        sendResetEmail(
            $user['email'],
            $user['username'],
            $token
        );
    }

    $success = "If the account exists, a reset link has been sent.";
}

include 'includes/header.php';
?>
<meta name="robots" content="noindex, nofollow">
<div class="page-center">

    <div class="auth-container">

        <div class="auth-title">
            Lyn<span>k</span>
        </div>

        <div class="auth-subtitle">

            Forgot your password?

            <br><br>

            Enter your email address and we'll send
            you a password reset link.

        </div>

        <?php if(!empty($error)): ?>

            <div class="alert alert-error">
                <?= $error; ?>
            </div>

        <?php endif; ?>

        <?php if(!empty($success)): ?>

            <div class="alert alert-success">
                <?= $success; ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Email Address</label>

                <input
                    class="input"
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>

            <button
                class="btn btn-primary"
                type="submit"
                name="reset"
            >
                Send Reset Link
            </button>

        </form>

        <div class="auth-footer">

            <p class="auth-footer-text">

                Remember your password?

                <a href="login.php">
                    Login
                </a>

            </p>

            <a href="index.php" class="auth-home-link">
                ← Back to Homepage
            </a>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>