<?php
session_start();
$pageTitle = "Dashboard - Lynk URL Shortener";

include 'config.php';
include 'rate_limit.php';


if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
/* GET USER INFO */
$userStmt = $conn->prepare(
    "SELECT username FROM users WHERE id=? LIMIT 1"
);

$userStmt->bind_param("i", $user_id);

$userStmt->execute();

$userResult = $userStmt->get_result();

$userData = $userResult->fetch_assoc();

$username = $userData['username'];

$existing_link = false;

/* CREATE SHORT LINK */
$existing_link = false;
$message = "";
$type = "";
$short_url = "";
$original_url = "";

if (isset($_POST['shorten'])) {

    $url = trim($_POST['url']);
    $custom = trim($_POST['custom_slug'] ?? '');

    if (!filter_var($url, FILTER_VALIDATE_URL)) {

        $error = "Invalid URL format.";

    } else {

        // decide short code
        if (!empty($custom)) {

            // sanitize slug (abc123 only)
            $short = preg_replace('/[^a-zA-Z0-9\-]/', '', $custom);

            if (empty($short)) {
                $error = "Invalid custom slug.";
            } else {

                // check if slug exists
                $check = $conn->prepare("SELECT id FROM links WHERE short_code=? LIMIT 1");
                $check->bind_param("s", $short);
                $check->execute();
                $result = $check->get_result();

                if ($result->num_rows > 0) {
                    $error = "Custom slug already taken.";
                }
            }

        } else {
            // auto generate
            $short = substr(md5(uniqid()), 0, 6);
        }

        // only insert if no error yet
        if (empty($error)) {

            // CHECK EXISTING URL FOR THIS USER
            $check = $conn->prepare(
                "SELECT short_code FROM links 
                 WHERE user_id=? AND original_url=? 
                 LIMIT 1"
            );

            $check->bind_param("is", $user_id, $url);
            $check->execute();
            $result = $check->get_result();

            if ($row = $result->fetch_assoc()) {

                $short = $row['short_code'];
                $message = "⚠️ Destination already exists.";
                $type = "info";

            } else {

                $stmt = $conn->prepare(
                    "INSERT INTO links(user_id, original_url, short_code)
                     VALUES(?,?,?)"
                );

                $stmt->bind_param("iss", $user_id, $url, $short);
                $stmt->execute();

                $message = "🎉 New short link created!";
                $type = "success";

                
            }

            $_SESSION['toast'] = [
                'message' => $message,
                'type' => $type
            ];

            header("Location: dashboard.php");
            exit;
        }
    }
}

/* GET USER LINKS */
$stmt = $conn->prepare(
    "SELECT * FROM links
     WHERE user_id=?
     ORDER BY id DESC"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$links = $stmt->get_result();

/* TOTAL CLICKS */
$stmt = $conn->prepare(
    "SELECT SUM(clicks) AS total_clicks
     FROM links
     WHERE user_id=?"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$clickRow = $result->fetch_assoc();

$totalClicks = $clickRow['total_clicks'] ?? 0;

/* INCLUDE HEADER */
include 'includes/header.php';

?>

<div class="dashboard-container">

    <!-- STATS -->
    <div class="stats">

        <div class="card">

            <h3>Total Links</h3>

            <h1>
                <?php echo $links->num_rows; ?>
            </h1>

        </div>

        <div class="card">

            <h3>Total Clicks</h3>

            <h1>
                <?php echo $totalClicks; ?>
            </h1>

        </div>

        <div class="card">

            <h3>Active Links</h3>

            <h1>
                <?php echo $links->num_rows; ?>
            </h1>

        </div>

    </div>

    <!-- CREATE LINK -->
    <div class="form-box">

    <h3>Create Short Link</h3>

    <form method="POST">

        <!-- LONG URL -->
        <div class="form-group">
            <input
                class="input"
                type="url"
                name="url"
                id="longUrlInput"
                placeholder="Paste long URL..."
                required
            >
        </div>

        <!-- CUSTOM SHORT LINK -->
<div class="form-group">

    <label style="font-size:12px;color:#94a3b8;">
        Custom Short Link (optional)
    </label>

    <div class="url-input-group">

        <!-- FIXED DOMAIN -->
        <div class="url-domain">
            https://lynk.page.gd/
        </div>

        <!-- EDITABLE SLUG -->
        <input
            type="text"
            name="custom_slug"
            placeholder="your-custom-link"
            class="url-slug"
        >

    </div>

    <small style="color:#64748b;font-size:11px;">
        Leave empty to auto-generate
    </small>

</div>

        <button class="btn btn-primary" name="shorten" type="submit">
            🚀 Shorten URL
        </button>

    </form>

</div>
    <?php if (!empty($message)): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    showToast("<?= $message ?>", "<?= $type ?>");
});
</script>
<?php endif; ?>

</div>



    <?php if(isset($error)): ?>

        <div class="error">

            <?php echo $error; ?>

        </div>

    <?php endif; ?>

    <!-- LINKS -->
    <h3 style="margin-bottom:15px;">
        Your Links
    </h3>

    <?php while($row = $links->fetch_assoc()): ?>

        <div class="link-card">

            <!-- SHORT LINK -->
            <div class="short">

                <a href="https://lynk.page.gd/<?php echo $row['short_code']; ?>"
                   target="_blank">

                    https://lynk.page.gd/<?php echo $row['short_code']; ?>

                </a>

            </div>

            <!-- ORIGINAL -->
            <div class="long">

                <?php echo $row['original_url']; ?>

            </div>

            <!-- CLICKS -->
            <div class="clicks">

                <?php echo $row['clicks']; ?> clicks

            </div>

            <!-- ACTIONS -->
<div class="link-actions">

    <!-- EDIT -->
    <button
        class="action-btn action-edit"
        onclick="openEditModal(
            <?php echo $row['id']; ?>,
            '<?php echo addslashes($row['original_url']); ?>'
        )"
        title="Edit link">

        ✏️

    </button>

    <!-- COPY -->
    <button
        class="action-btn action-copy"
        onclick="copyLink('https://lynk.page.gd/<?php echo $row['short_code']; ?>', this)"
        title="Copy link">

        📋

    </button>

    <!-- DELETE -->
    <a
        href="delete.php?id=<?php echo $row['id']; ?>"
        class="action-btn action-delete"
        onclick="return confirm('Delete this link?')"
        title="Delete link">

        🗑️

    </a>

</div>

        </div>

    <?php endwhile; ?>

</div>

<!-- EDIT MODAL -->
<div id="editModal" class="dash-modal">

    <div class="dash-modal-content">

        <!-- CLOSE -->
        <button class="dash-modal-close" onclick="closeEditModal()">×</button>

        <!-- TITLE -->
        <div class="dash-modal-title">
            Edit Your Link
        </div>

        <!-- FORM -->
        <form method="POST" action="edit.php">

            <input type="hidden" name="id" id="edit_id">

            <div class="dash-form-group">
                <label class="dash-label">Destination URL</label>

                <input
                    type="url"
                    name="url"
                    id="edit_url"
                    class="dash-input"
                    placeholder="https://example.com"
                    required
                >
            </div>

            <button type="submit" name="update" class="dash-btn-primary">
                Update Link
            </button>

        </form>

        <!-- FOOTER ACTION -->
        <button onclick="closeEditModal()" class="dash-btn-secondary">
            Cancel
        </button>

    </div>

</div>

<script>
window.addEventListener("load", () => {

    const input = document.getElementById("longUrlInput");

    console.log("INPUT:", input);

    if (!input) {
        console.warn("Input not found");
        return;
    }

    // HARD FOCUS ATTEMPT
    input.focus();

    setTimeout(() => {
        input.focus();
    }, 1000);

});
</script>

<?php include 'includes/footer.php'; ?>