<?php
session_start();

include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* CREATE SHORT LINK */
if(isset($_POST['shorten'])) {

    $url = trim($_POST['url']);

    if(!filter_var($url, FILTER_VALIDATE_URL)) {

        $error = "Invalid URL";

    } else {

        $short = substr(md5(uniqid()), 0, 6);

        $stmt = $conn->prepare(
            "INSERT INTO links(user_id, original_url, short_code)
             VALUES(?,?,?)"
        );

        $stmt->bind_param("iss", $user_id, $url, $short);

        $stmt->execute();
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

            <input
                type="url"
                name="url"
                placeholder="Paste long URL..."
                required
            >

            <button name="shorten">
                Shorten URL
            </button>

        </form>

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
                <a href="#"
                   class="edit-link"
                   onclick="openModal(
                        <?php echo $row['id']; ?>,
                        '<?php echo addslashes($row['original_url']); ?>'
                   )">

                    Edit

                </a>

                <!-- DELETE -->
                <a href="delete.php?id=<?php echo $row['id']; ?>"
                   class="delete-link"
                   onclick="return confirm('Delete this link?')">

                    Delete

                </a>

                <!-- COPY -->
                <button class="copy-btn"
                        data-url="https://lynk.page.gd/<?php echo $row['short_code']; ?>"
                        onclick="copyLink(this)">

                    Copy

                </button>

            </div>

        </div>

    <?php endwhile; ?>

</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">

    <div class="modal-content">

        <h3>Edit Link</h3>

        <form method="POST" action="edit.php">

            <input
                type="hidden"
                name="id"
                id="edit_id"
            >

            <input
                type="url"
                name="url"
                id="edit_url"
                required
            >

            <button type="submit" name="update">

                Update Link

            </button>

        </form>

        <button
            onclick="closeModal()"
            class="close-btn">

            Close

        </button>

    </div>

</div>

<?php include 'includes/footer.php'; ?>