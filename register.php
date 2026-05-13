<?php
session_start();

include 'config.php';
include 'rate_limit.php';

$ip = $_SERVER['REMOTE_ADDR'];

if (!rateLimit("login_$ip", 5, 60)) {
    die("Too many requests. Please wait a moment.");
}

if(isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}

$error = "";
$success = "";

/* REGISTER */
if(isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

if(strlen($password) < 8) {
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
} else {

        $hashed = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "INSERT INTO users(username,email,password)
             VALUES(?,?,?)"
        );

        $stmt->bind_param(
            "sss",
            $username,
            $email,
            $hashed
        );

        if($stmt->execute()) {

            $success = "Account created successfully.";

        } else {

            $error = "Username or email already exists.";

        }
    }
}

/* HEADER */
include 'includes/header.php';
?>

<div class="page-center">

    <div class="auth-container">

        <div class="auth-title">

            Lyn<span>k</span>

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

        <form method="POST" ">

    <div class="form-group">
    <label>Username</label>
    <input class="input" type="text" name="username" id="username" required>
    <small id="usernameMsg" style="color:#94a3b8;"></small>
</div>

<div class="form-group">
    <label>Email</label>
    <input class="input" type="email" name="email" id="email" required>
    <small id="emailMsg" style="color:#94a3b8;"></small>
</div>

    <div class="form-group">

        <label>Password</label>

<input id="register_password" class="input" type="password" name="password" required>

<div class="pw-meter">
    <div class="pw-bar" id="registerPwBar"></div>
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