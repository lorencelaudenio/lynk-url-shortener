<?php
session_start();
$pageTitle = "Profile - Lynk URL Shortener";

include 'config.php';
require_once __DIR__ . "/includes/mailer.php";;

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* AJAX REQUEST */
if(isset($_POST['ajax']) && $_POST['ajax'] == "1") {

    header('Content-Type: application/json');

    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = trim($_POST['new_password']);

    // GET USER
    $stmt = $conn->prepare("SELECT username, email, password FROM users WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $dbUser = $stmt->get_result()->fetch_assoc();

    // VERIFY PASSWORD
    if(!password_verify($current_password, $dbUser['password'])) {

        echo json_encode([
            "status" => "error",
            "message" => "Current password is incorrect."
        ]);
        exit;
    }

    /*
    ==========================
    EMAIL CHANGE VERIFICATION
    ==========================
    */
    if($email !== $dbUser['email']) {

        // GENERATE TOKEN
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // SAVE PENDING EMAIL
        $stmt = $conn->prepare("
            UPDATE users 
            SET email_verify_token=?, 
                pending_email=?,
                token_expires=?
            WHERE id=?
        ");

        $stmt->bind_param("sssi", $token, $email, $expires, $user_id);
        $stmt->execute();

// SEND VERIFICATION TO NEW EMAIL
sendEmailChangeVerification($email, $dbUser['username'], $token);

// SMALL DELAY
usleep(500000); // 0.5 second

    }

    /*
    ==========================
    PASSWORD UPDATE
    ==========================
    */
    if(!empty($new_password)) {

        if(strlen($new_password) < 6) {

            echo json_encode([
                "status" => "error",
                "message" => "Password must be at least 6 characters."
            ]);
            exit;
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();
    }

    echo json_encode([
        "status" => "success",
        "message" => ($email !== $dbUser['email'])
            ? "Verification link sent to your new email."
            : "Profile updated successfully."
    ]);

    exit;
}

/* GET USER DATA */
$stmt = $conn->prepare("SELECT username, email, plan FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();


$plan = strtolower($user['plan'] ?? 'free');

include 'includes/header.php';
?>

<div class="page-center">

    <div class="auth-container">

        <div class="auth-title">Profile <span>Settings</span></div>
        <div class="auth-subtitle">Manage your account details</div>

        <div style="margin:10px 0; text-align:center;">

    <?php if ($plan === 'free'): ?>

        <span class="plan-badge free">FREE ACCOUNT</span>

        <div style="margin-top:10px;">
            <a href="upgrade.php" class="btn btn-primary">
                ⚡ Upgrade to Pro
            </a>
        </div>

    <?php else: ?>

        <span class="plan-badge pro">PRO ACCOUNT</span>

    <?php endif; ?>

</div>

        <div id="alertBox"></div>

        <form id="profileForm">

            <div class="form-group">
                <label>Username</label>
                <input class="input" type="text"
                    value="<?= htmlspecialchars($user['username']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="input" type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Current Password</label>
                <input class="input"
                    type="password"
                    name="current_password"
                    required>
            </div>

            <div class="form-group">
                <label>New Password</label>

                <input id="profile_password"
                    class="input"
                    type="password"
                    name="new_password">

                <div class="pw-meter">
                    <div class="pw-bar" id="profilePwBar"></div>
                </div>

                <p class="pw-text" id="profilePwText"></p>
            </div>

            <button class="btn btn-primary" id="updateBtn">
                Update Profile
            </button>

        </form>

    </div>

</div>

<script>

const form = document.getElementById("profileForm");
const btn = document.getElementById("updateBtn");
const alertBox = document.getElementById("alertBox");

form.addEventListener("submit", async function(e){

    e.preventDefault();

    btn.disabled = true;
    btn.innerHTML = "Updating...";

    const formData = new FormData(form);
    formData.append("ajax", "1");

    try {

        const response = await fetch("", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        alertBox.innerHTML = `
            <div class="alert alert-${data.status}">
                ${data.message}
            </div>
        `;

    } catch(err) {

        alertBox.innerHTML = `
            <div class="alert alert-error">
                Something went wrong.
            </div>
        `;

    }

    btn.disabled = false;
    btn.innerHTML = "Update Profile";

});

</script>

<?php include 'includes/footer.php'; ?>