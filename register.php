<?php
$pageTitle = "Register - Lynk URL Shortener";



include 'config.php';
include 'includes/mailer.php';
include 'rate_limit.php';



if(isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}

$error = "";
$success = "";

/* REGISTER */
if(isset($_POST['register'])) {
    $ip = $_SERVER['REMOTE_ADDR'];

if (!rateLimit("login_$ip", 5, 60)) {
    die("Too many requests. Please wait a moment.");
}

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // RESET ERROR FIRST
    $error = "";

    // 1. TERMS CHECK (STOP IMMEDIATELY)
    if (!isset($_POST['terms'])) {
        $error = "You must accept the Terms of Service and Privacy Policy.";
    }

    // 2. PASSWORD RULES (ONLY IF NO ERROR YET)
    elseif(strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }
    elseif(!preg_match('/[A-Z]/', $password)) {
        $error = "Password must contain at least 1 uppercase letter.";
    }
    elseif(!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least 1 number.";
    }
    elseif(!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Password must contain at least 1 special character.";
    }

    // 3. ONLY RUN DB INSERT IF NO ERROR
    else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users(username,email,password)
             VALUES(?,?,?)"
        );

        $stmt->bind_param("sss", $username, $email, $hashed);

        if($stmt->execute()) {

        // SEND WELCOME EMAIL HERE (RIGHT AFTER SUCCESS)
    sendWelcomeEmail($email, $username);

            // IMPORTANT: DO NOT SET SESSION HERE
            $success = "Account created successfully.";

        } else {

            $error = "Username or email already exists.";
        }
    }
}

/* HEADER */
$bodyClass = "auth-page";
include 'includes/header.php';
?>
<meta name="robots" content="noindex, nofollow">
<body class="<?= $bodyClass ?? '' ?>">
<div class="page-center">

    <div class="auth-container">

        <div class="auth-title">

<a href="https://lynk.page.gd/" class="logo lynk-logo">
  Lyn<span>k</span>
</a>

        </div>

        <div class="auth-subtitle">

            Create your account 🚀

            <br>

            Start shortening and tracking links.

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

        <form method="POST" >

<div class="form-group">
    <label class="sr-only">Username</label>
    <input class="input" type="text" name="username" placeholder="Username" required>
</div>

<div class="form-group">
    <label class="sr-only">Email</label>
    <input class="input" type="email" name="email" placeholder="Email address" required>
</div>


<div class="form-group">
    <label class="sr-only">Password</label>
    <input id="register_password" class="input" type="password" name="password" placeholder="Password" required>
</div>
<div class="pw-meter">
    <div class="pw-bar" id="registerPwBar"></div>
</div>
<div class="form-group">

    <label style="display:flex; gap:10px; align-items:flex-start; font-size:13px; color:#94a3b8;">

        <input type="checkbox" name="terms" required>

        <span>
            I agree to the 
            <a href="terms-of-service.php" target="_blank" style="color:#60a5fa;">
                Terms of Service
            </a>
            and
            <a href="privacy-policy.php" target="_blank" style="color:#60a5fa;">
                Privacy Policy
            </a>.
        </span>

    </label>

</div>
<p class="pw-text" id="registerPwText"></p>
    <button
        type="submit"
        name="register"
        class="btn btn-primary"
    >
        Create Account
    </button>

</form>

        <div class="auth-footer">

    <p class="auth-footer-text">
        Already have an account?
        <a href="login.php">Login</a>
    </p>

    <a href="index.php" class="auth-home-link">
        ← Back to Homepage
    </a>

</div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>