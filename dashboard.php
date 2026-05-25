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



/* GET USER LINKS */
$limit = 10;

$stmt = $conn->prepare(
    "SELECT * FROM links
     WHERE user_id=?
     ORDER BY id DESC
     LIMIT ?"
);

$stmt->bind_param("ii", $user_id, $limit);


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

/* TOTAL LINKS */
$countStmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM links
     WHERE user_id=?"
);

$countStmt->bind_param("i", $user_id);

$countStmt->execute();

$countResult = $countStmt->get_result();

$totalRow = $countResult->fetch_assoc();

$totalLinks = $totalRow['total'];

$limit = 1000;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM links
    WHERE user_id=?
    AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$usage = $stmt->get_result()->fetch_assoc();

$used = $usage['total'];

/* INCLUDE HEADER */
include 'includes/header.php';

?>

<div class="dashboard-container">

    <!-- STATS -->
    <div class="stats">

        <div class="card">

            <h3>Total Links</h3>

                <h1 id="totalLinks"><?php echo $totalLinks; ?></h1>

        </div>

        <div class="card">

            <h3>Total Clicks</h3>

            <h1>
                <?php echo $totalClicks; ?>
            </h1>

        </div>

        <div class="card">

            <h3>Active Links</h3>

                <h1 id="activeLinks"><?php echo $totalLinks; ?></h1>

        </div>

    </div>

    <!-- CREATE LINK -->
    <div class="form-box">

    <h3>Create Short Link</h3>

   <form id="shortenForm" onsubmit="return false;">

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

        <button class="btn btn-primary" id="shortenBtn" type="submit">
            🚀 Shorten URL
        </button>

    </form>

</div>

</div>



    <?php if(isset($error)): ?>

        <div class="error">

            <?php echo $error; ?>

        </div>

    <?php endif; ?>

    <!-- LINKS -->
<h3 style="display:flex;justify-content:space-between;align-items:center;">
    <span>Your Links</span>

<span class="usage-pill">
    <span class="used"><?= $used ?></span>
    <span class="sep">/</span>
    <span class="limit"><?= $limit ?></span>
    <span class="label">used this month</span>
</span>
</h3>
<div id="linksContainer">
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
<div id="skeletonLoader" style="display:none;">
    
    <div class="skeleton-card"></div>
    <div class="skeleton-card"></div>
    <div class="skeleton-card"></div>
    <div class="skeleton-card"></div>

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
<button
    class="action-btn action-delete"
    onclick="deleteLink(<?php echo $row['id']; ?>, this)"
    title="Delete link">

    🗑️

</button>
</div>

        </div>

    <?php endwhile; ?>

</div>
    </div>  <!-- Infinite scroll trigger -->
    <div id="loader" style="text-align:center;padding:20px;display:none;">
    Loading...
</div>

<div id="sentinel"></div>

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

<div id="loader">
   <div class="skeleton-card"></div>
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

<script>
document.getElementById("shortenForm")
.addEventListener("submit", async function(e){

    e.preventDefault();

    const btn = document.getElementById("shortenBtn");

    btn.disabled = true;
    btn.innerHTML = "Creating...";

    const formData = new FormData(this);

    try {

        const response = await fetch("ajax/create_link.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        showToast(data.message, data.status);

        if(data.status === "success" || data.status === "info") {

            // CLEAR INPUTS
            this.reset();

            // OPTIONAL:
            // AUTO RELOAD LINKS
            setTimeout(() => {
                location.reload();
            }, 800);
        }

    } catch(err) {

        showToast("Something went wrong.", "error");

    }

    btn.disabled = false;
    btn.innerHTML = "🚀 Shorten URL";

});
    </script>

<script>
async function deleteLink(id, btn) {

    if (!confirm("Delete this link?")) return;

    btn.disabled = true;

    try {

        const formData = new FormData();
        formData.append("id", id);

        const response = await fetch("ajax/delete_link.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        showToast(data.message, data.status);

if (data.status === "success") {

    const card = btn.closest(".link-card");

    card.style.transition = "0.3s";
    card.style.opacity = "0";

    setTimeout(() => card.remove(), 300);

    // UPDATE STATS FROM SERVER
    document.getElementById("totalLinks").innerText = data.total_links;
    document.getElementById("activeLinks").innerText = data.total_links;
}    } catch (err) {

        console.error(err);
        showToast("Delete failed", "error");
    }

    btn.disabled = false;
}
</script>

<script>
let offset = 10;
let loading = false;
let finished = false;

const skeleton = document.getElementById("skeletonLoader");

function showSkeleton(count = 5) {

    if (!skeleton) return;

    skeleton.innerHTML = "";

    for (let i = 0; i < count; i++) {
        const div = document.createElement("div");
        div.className = "skeleton-card";
        skeleton.appendChild(div);
    }

    skeleton.style.display = "block";
}

async function loadMoreLinks() {

    if (loading || finished) return;

    loading = true;

showSkeleton(4);

    try {

        const response = await fetch(`ajax/load_links.php?offset=${offset}`);
        const html = await response.text();

        if (html.trim() === "") {

    finished = true;
    loading = false;

    skeleton.style.opacity = "0";

    setTimeout(() => {
        skeleton.style.display = "none";
        skeleton.innerHTML = "";
        skeleton.style.opacity = "1"; // reset for safety
    }, 200);

    return;
} else {

            // simulate skeleton before render (smooth UX)
            setTimeout(() => {

                document.getElementById("linksContainer")
                    .insertAdjacentHTML("beforeend", html);

                offset += 10;

                if (skeleton) skeleton.style.display = "none";

            }, 500); // small delay for smooth effect
        }

    } catch (err) {
        console.error(err);
    }

    loading = false;
}

if (sentinel) {

    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            loadMoreLinks();
        }
    });

    observer.observe(sentinel);
}
</script>

<?php include 'includes/footer.php'; ?>