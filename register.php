<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$pageTitle = "Register - CutThis.Link URL Shortener";



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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $terms = isset($_POST['terms']);

    // basic validation
    if (!$terms) {
        $error = "You must agree to the Terms of Service.";
    } elseif (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {

        // check duplicate username OR email
        $check = $conn->prepare("
            SELECT id 
            FROM users 
            WHERE username = ? OR email = ?
        ");

        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $error = "Username or Email already exists.";

        } else {

            // hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            try {

                // insert user
                $stmt = $conn->prepare("
                    INSERT INTO users (username, email, password)
                    VALUES (?, ?, ?)
                ");

                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                $stmt->execute();

                // send emails AFTER successful insert
                sendWelcomeEmail($email, $username);
                sendNewUserAlert($username, $email);

                $success = "Account created successfully! You can now login.";

            } catch (mysqli_sql_exception $e) {

                // fallback safety (prevents 500 error)
                if (str_contains($e->getMessage(), 'Duplicate')) {
                    $error = "Username or Email already exists.";
                } else {
                    $error = "Something went wrong. Please try again.";
                    error_log($e->getMessage());
                }
            }
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

<a href="https://cutthis.link/" class="logo lynk-logo">
  CutThis<span>.Link</span>
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