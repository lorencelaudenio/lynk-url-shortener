<?php
$pageTitle = "Upgrade to Pro - Lynk";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

/* GET USER */
$stmt = $conn->prepare("SELECT username, email, plan FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$plan = strtolower($user['plan'] ?? 'free');

include 'includes/header.php';
?>

<div class="upgrade-container">

    <h1>⚡ Upgrade to Pro</h1>
    <p>Remove limits and unlock unlimited link creation.</p>

    <?php if ($plan === 'pro'): ?>
        <div class="already-pro">
            ✅ You are already a PRO user.
        </div>
    <?php else: ?>

        <div class="pricing-card">

            <h2>Pro Plan</h2>
            <p class="price">₱99 / month</p>

            <ul>
                <li>🚀 Unlimited URL shortening</li>
                <li>📊 Advanced analytics</li>
                <li>⚡ Priority support</li>
                <li>🚫 No 1000 limit</li>
            </ul>

            <div class="payment-methods">

                <!-- GCASH -->
                <form method="POST" action="payment/gcash.php">
                    <button class="btn gcash">
                        Pay with GCash
                    </button>
                </form>

                <!-- PAYPAL -->
                <form method="POST" action="payment/paypal.php">
                    <button class="btn paypal">
                        Pay with PayPal
                    </button>
                </form>

                <!-- STRIPE -->
                <form method="POST" action="payment/stripe.php">
                    <button class="btn stripe">
                        Pay with Stripe
                    </button>
                </form>

            </div>

        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>