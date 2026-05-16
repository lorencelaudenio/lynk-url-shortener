<?php
session_start();
$pageTitle = "Login - Lynk URL Shortener";

include 'config.php';
include 'rate_limit.php';





if(isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}

$error = "";

/* LOGIN */
if(isset($_POST['login'])) {
  $ip = $_SERVER['REMOTE_ADDR'];
  if (!rateLimit("login_$ip", 5, 60)) {
    die("Too many requests. Please wait a moment.");
}

    $login = trim($_POST['email']); // email OR username
    $password = $_POST['password'];

    $error = "";

    // basic password check only (DO NOT force email validation)
    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE email=? OR username=? LIMIT 1"
        );

        $stmt->bind_param("ss", $login, $login);

        $stmt->execute();

        $result = $stmt->get_result();

        if($user = $result->fetch_assoc()) {

            if(password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: dashboard.php");
                exit;

            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "Account not found.";
        }
    }
}


/* HEADER */
include 'includes/header.php';
?>

<div class="page-center">

  <div class="auth-container">

    <div class="auth-title">
<a href="https://lynk.page.gd/" class="logo lynk-logo">
  Lyn<span>k</span>
</a>
    </div>

    <div class="auth-subtitle">
      Welcome back 👋
    </div>

<?php if(!empty($error)): ?>
  <div class="alert alert-error">
    <?php echo $error; ?>
  </div>
<?php endif; ?>

    <form method="POST">

<input class="input" type="text" name="email" placeholder="Email or Username" required>
<div style="position:relative;">
    <input class="input" type="password" name="password" id="password" placeholder="Password" required>

    <button type="button"
        onclick="togglePassword()"
        style="position:absolute; right:10px; top:12px; background:none; border:none; color:#94a3b8; cursor:pointer;">
        👁
    </button>
</div>
<button class="btn btn-primary" type="submit" name="login">
  Login
</button>

    </form>
<div class="forgot-link">
    <a href="forgot-password.php">Forgot password?</a>
</div>

    <div style="margin-top:15px;text-align:center;">
      <a href="register.php" style="color:#94a3b8;text-decoration:none;">
        No account? Register
      </a>
    </div>

  </div>

</div>

<?php include 'includes/footer.php'; ?>