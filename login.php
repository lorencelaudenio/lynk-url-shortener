<?php
session_start();
include 'config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

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
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - Lynk</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#0f172a;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    padding:20px;
}

.auth-container{
    width:100%;
    max-width:430px;
    background:#111827;
    border:1px solid rgba(255,255,255,0.08);
    border-radius:24px;
    padding:35px;
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
}

.logo{
    text-align:center;
    font-size:34px;
    font-weight:bold;
    margin-bottom:10px;
}

.logo span{
    color:#3b82f6;
}

.subtitle{
    text-align:center;
    color:#94a3b8;
    margin-bottom:35px;
    line-height:1.6;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    color:#cbd5e1;
}

.form-group input{
    width:100%;
    padding:15px;
    border:none;
    outline:none;
    border-radius:14px;
    background:#1e293b;
    color:white;
    font-size:15px;
}

.form-group input:focus{
    border:1px solid #3b82f6;
}

.login-btn{
    width:100%;
    padding:15px;
    border:none;
    border-radius:14px;
    background:#3b82f6;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.2s;
}

.login-btn:hover{
    background:#2563eb;
}

.error{
    background:#ef4444;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    text-align:center;
}

.bottom-text{
    text-align:center;
    margin-top:25px;
    color:#94a3b8;
}

.bottom-text a{
    color:#3b82f6;
    text-decoration:none;
    font-weight:bold;
}

.home-link{
    text-align:center;
    margin-top:18px;
}

.home-link a{
    color:#94a3b8;
    text-decoration:none;
    font-size:14px;
}

</style>

</head>
<body>

<div class="auth-container">

    <div class="logo">
        Lyn<span>k</span>
    </div>

    <div class="subtitle">
        Welcome back 👋 <br>
        Login to manage your shortened links.
    </div>

    <?php if(!empty($error)): ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

        </div>

        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

        </div>

        <button
            type="submit"
            name="login"
            class="login-btn"
        >
            Login
        </button>

    </form>

    <div class="bottom-text">

        Don't have an account?
        <a href="register.php">
            Register
        </a>

    </div>

    <div class="home-link">

        <a href="index.php">
            ← Back to Homepage
        </a>

    </div>

</div>

</body>
</html>