<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/mailer.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* HANDLE AJAX REQUEST */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['payer_name'], $_POST['reference_number'])
) {

    header('Content-Type: application/json');
    ob_clean(); // IMPORTANT: clears any warning output

    try {

        $payer_name = trim($_POST['payer_name']);
        $reference  = trim($_POST['reference_number']);

        if ($payer_name === '' || $reference === '') {
            echo json_encode([
                "status" => "error",
                "message" => "All fields are required."
            ]);
            exit;
        }

        $stmt = $conn->prepare("
            SELECT username, email 
            FROM users 
            WHERE id=? 
            LIMIT 1
        ");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            echo json_encode([
                "status" => "error",
                "message" => "User not found."
            ]);
            exit;
        }

        $ok = sendGCashPaymentAlert(
            $user_id,
            $user['username'],
            $user['email'],
            $payer_name,
            $reference
        );

        if (!$ok) {
            echo json_encode([
                "status" => "error",
                "message" => "Email failed to send."
            ]);
            exit;
        }

        echo json_encode([
            "status" => "success",
            "message" => "Payment details sent for verification."
        ]);
        exit;

    } catch (Exception $e) {

        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
        exit;
    }
}

include __DIR__ . '/../includes/header.php'; ?>

<div class="page-center">

    <div class="auth-container gcash-box">

        <div class="gcash-header">
            <div class="auth-title">Pay via <span>GCash</span></div>
            <div class="auth-subtitle">Scan QR or submit reference number</div>
        </div>

        <div id="alertBox"></div>

        <!-- 2 COLUMN LAYOUT -->
        <div class="gcash-layout">

            <!-- LEFT: QR -->
            <div class="gcash-qr-box">
                <img src="https://lorencelaudeniodigital.com/wp-content/uploads/2025/07/Scan-to-Pay.png"
                     alt="GCash QR">
                <p>Scan to pay</p>
            </div>

            <!-- RIGHT: FORM -->
            <div class="gcash-form-box">

                <form id="paymentForm">

                    <div class="form-group">
                        <label>Your Name</label>
                        <input class="input" type="text" name="payer_name" required>
                    </div>

                    <div class="form-group">
                        <label>Reference Number</label>
                        <input class="input" type="text" name="reference_number" required>
                    </div>

                    <button class="btn btn-primary" id="submitBtn">
                        Confirm Payment
                    </button>

                </form>

                <div class="gcash-note">
                    ⚠️ Make sure reference number is correct before submitting
                </div>

            </div>

        </div>

    </div>

</div>

<script>
const form = document.getElementById("paymentForm");
const btn = document.getElementById("submitBtn");
const alertBox = document.getElementById("alertBox");

form.addEventListener("submit", async function(e){

    e.preventDefault();

    btn.disabled = true;
    btn.innerHTML = "Sending...";

    const formData = new FormData(form);

    try {

        const response = await fetch(window.location.href, {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        alertBox.innerHTML = `
            <div class="alert alert-${data.status}">
                ${data.message}
            </div>
        `;

        if(data.status === "success"){
            form.reset();
        }

    } catch(err) {

        alertBox.innerHTML = `
            <div class="alert alert-error">
                Something went wrong.
            </div>
        `;

    }

    btn.disabled = false;
    btn.innerHTML = "Send Payment Information";

});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>