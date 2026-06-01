<?php

$pageTitle = "Reset Password - Lynk";

include 'config.php';

$error = "";
$success = "";

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid reset token.");
}

/* CHECK TOKEN */
$stmt = $conn->prepare(
    "SELECT * FROM users
     WHERE reset_token=?
     AND reset_expires > NOW()
     LIMIT 1"
);

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die("Reset link is invalid or expired.");
}

/* RESET PASSWORD */
if (isset($_POST['reset'])) {

    $password = $_POST['password'];

    // PASSWORD VALIDATION
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

        $update = $conn->prepare(
            "UPDATE users
             SET password=?,
                 reset_token=NULL,
                 reset_expires=NULL
             WHERE id=?"
        );

        $update->bind_param(
            "si",
            $hashed,
            $user['id']
        );

        if($update->execute()) {

            $success = "Password updated successfully.";

        } else {

            $error = "Something went wrong.";
        }
    }
}

include 'includes/header.php';
?>

<div class="page-center">

    <div class="auth-container">

        <div class="auth-title">
            Lyn<span>k</span>
        </div>

        <div class="auth-subtitle">
            Create a new password
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

        <?php if(empty($success)): ?>

        <form method="POST">

            <div class="form-group">

                <label>New Password</label>

                <input
                    id="reset_password"
                    class="input"
                    type="password"
                    name="password"
                    required
                >

                <div class="pw-meter">
                    <div class="pw-bar" id="resetPwBar"></div>
                </div>

                <p class="pw-text" id="resetPwText"></p>

            </div>

            <button
                type="submit"
                name="reset"
                class="btn btn-primary"
            >
                Update Password
            </button>

        </form>

        <?php else: ?>

            <div style="margin-top:20px;text-align:center;">

                <a href="login.php" class="btn btn-primary">
                    Go to Login
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>

<script>
const resetPassword = document.getElementById("reset_password");
const resetBar = document.getElementById("resetPwBar");
const resetText = document.getElementById("resetPwText");

if(resetPassword){

    resetPassword.addEventListener("input", function(){

        const val = this.value;

        let strength = 0;

        if(val.length >= 8) strength++;
        if(/[A-Z]/.test(val)) strength++;
        if(/[0-9]/.test(val)) strength++;
        if(/[^A-Za-z0-9]/.test(val)) strength++;

        resetBar.className = "pw-bar";

        if(strength <= 1){

            resetBar.classList.add("weak");
            resetText.innerText = "Weak password";

        }
        else if(strength <= 3){

            resetBar.classList.add("medium");
            resetText.innerText = "Medium password";

        }
        else {

            resetBar.classList.add("strong");
            resetText.innerText = "Strong password";

        }

    });

}
</script>

<?php include 'includes/footer.php'; ?>