<?php
session_start();

include 'config.php';

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

    if(strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

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

        <form method="POST">

    <div class="form-group">

        <label>Username</label>

        <input
            class="input"
            type="text"
            name="username"
            placeholder="Enter username"
            required
        >

    </div>

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

    <div class="form-group">

        <label>Password</label>

        <input
            class="input"
            type="password"
            name="password"
            placeholder="Create password"
            required
        >

    </div>

    <button
        type="submit"
        name="register"
        class="btn btn-primary"
    >
        Create Account
    </button>

</form>

        <div class="bottom-text">

            Already have an account?

            <a href="login.php">

                Login

            </a>

        </div>

        <div class="home-link">

            <a href="index.php">

                ← Back to Homepage

            </a>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>