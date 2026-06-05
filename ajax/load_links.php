<?php

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
$shortUrl = "https://cutthis.link/" . $row['short_code'];
?>

<div class="link-card">

    <!-- QR -->
    <div class="link-qr">
        <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=<?= $shortUrl ?>"
            alt="QR Code"
            onclick="openQrModal('<?= $shortUrl ?>')"
            style="cursor:pointer;"
        >
    </div>

    <!-- DETAILS -->
    <div class="link-info">

        <div class="short-link">
            <a href="<?= $shortUrl ?>" target="_blank">
                <?= $shortUrl ?>
            </a>
        </div>

        <div class="original-link">
            <?= htmlspecialchars($row['original_url']); ?>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="link-side">

        <div class="clicks">
            👆 <?= $row['clicks']; ?> clicks
        </div>

        <div class="link-actions">

            <button
                class="action-btn action-edit"
                onclick="openEditModal(
                    <?= $row['id']; ?>,
                    '<?= addslashes($row['original_url']); ?>'
                )"
                title="Edit">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>

            <button
                class="action-btn action-copy"
                onclick="copyLink('<?= $shortUrl ?>', this)"
                title="Copy">
                <i class="fa-solid fa-copy"></i>
            </button>

            <button
                class="action-btn action-delete"
                onclick="deleteLink(<?= $row['id']; ?>, this)"
                title="Delete">
                <i class="fa-solid fa-trash"></i>
            </button>

        </div>

    </div>

</div>

<!-- QR Modal -->
<div id="qrModal" class="qr-modal" onclick="closeQrModal()">
    <div class="qr-modal-content" onclick="event.stopPropagation()">
        <span class="qr-close" onclick="closeQrModal()">&times;</span>

        <img id="qrModalImg" src="" alt="QR Code">
    </div>
</div>

<!-- QR Modal Script Beg-->
<script>
function openQrModal(shortUrl) {

    const img = document.getElementById("qrModalImg");

    img.src = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=" + encodeURIComponent(shortUrl);

    document.getElementById("qrModal").style.display = "flex";
}

function closeQrModal() {
    document.getElementById("qrModal").style.display = "none";
}
</script>
<!-- QR Modal Script End-->

<?php endwhile; ?>