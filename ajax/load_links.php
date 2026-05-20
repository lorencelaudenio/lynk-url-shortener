<?php
session_start();

include "../config.php";

if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 10;
$stmt = $conn->prepare(
    "SELECT * FROM links
     WHERE user_id=?
     ORDER BY id DESC
     LIMIT ? OFFSET ?"
);

$stmt->bind_param("iii", $user_id, $limit, $offset);

$stmt->execute();

$result = $stmt->get_result();

while($row = $result->fetch_assoc()):
?>

<div class="link-card">

    <div class="short">
        <a href="https://lynk.page.gd/<?php echo $row['short_code']; ?>" target="_blank">
            https://lynk.page.gd/<?php echo $row['short_code']; ?>
        </a>
    </div>

    <div class="long">
        <?php echo htmlspecialchars($row['original_url']); ?>
    </div>

    <div class="clicks">
        <?php echo $row['clicks']; ?> clicks
    </div>

    <div class="link-actions">

        <button
            class="action-btn action-edit"
            onclick="openEditModal(
                <?php echo $row['id']; ?>,
                '<?php echo addslashes($row['original_url']); ?>'
            )">
            ✏️
        </button>

        <button
            class="action-btn action-copy"
            onclick="copyLink('https://lynk.page.gd/<?php echo $row['short_code']; ?>', this)">
            📋
        </button>

        <button
            class="action-btn action-delete"
            onclick="deleteLink(<?php echo $row['id']; ?>, this)">
            🗑️
        </button>

    </div>

</div>

<?php endwhile; ?>