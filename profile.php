<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* GET USER DATA */
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$message = "";
$error = "";

/* UPDATE PROFILE */
if(isset($_POST['update_profile'])) {

    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $dbUser = $stmt->get_result()->fetch_assoc();

    if(!password_verify($current_password, $dbUser['password'])) {
        $error = "Current password is incorrect.";
    } else {

        // update email
        $stmt = $conn->prepare("UPDATE users SET email=? WHERE id=?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();

        // update password if filled
        if(!empty($new_password)) {

            if(strlen($new_password) < 6) {
                $error = "Password must be at least 6 characters.";
            } else {

                $hashed = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param("si", $hashed, $user_id);
                $stmt->execute();

                $message = "Profile updated successfully.";
            }

        } else {
            $message = "Email updated successfully.";
        }
    }
}

include 'includes/header.php';
?>

<div class="page-center">

    <div class="auth-container">

        <div class="auth-title">Profile <span>Settings</span></div>

        <div class="auth-subtitle">Manage your account details</div>

        <?php if(!empty($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if(!empty($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Username</label>
                <input class="input" type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="input" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Current Password</label>
                <input class="input" type="password" name="current_password" required>
            </div>

<input id="profile_password" class="input" type="password" name="new_password">

<div class="pw-meter">
    <div class="pw-bar" id="profilePwBar"></div>
</div>

<p class="pw-text" id="profilePwText"></p>
            <button class="btn btn-primary" name="update_profile">
                Update Profile
            </button>

        </form>

    </div>

</div>

<?php include 'includes/footer.php'; ?>