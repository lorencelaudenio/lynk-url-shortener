<?php
session_start();

include 'config.php';

if(isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}

$error = "";

/* LOGIN */
if(isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email=?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()) {

        if(password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];

            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Incorrect password.";

        }

    } else {

        $error = "Account not found.";

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
      Welcome back 👋
    </div>

<?php if(!empty($error)): ?>
  <div class="alert alert-error">
    <?php echo $error; ?>
  </div>
<?php endif; ?>

    <form method="POST">

      <input class="input" type="email" name="email" placeholder="Email" required>

      <input class="input" type="password" name="password" placeholder="Password" required>

<button class="btn btn-primary" type="submit" name="login">
  Login
</button>

    </form>

    <div style="margin-top:15px;text-align:center;">
      <a href="register.php" style="color:#94a3b8;text-decoration:none;">
        No account? Register
      </a>
    </div>

  </div>

</div>

<?php include 'includes/footer.php'; ?>