<?php

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

$success = "";
$error = "";

/*
|--------------------------------------------------------------------------
| SAVE BIO
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_profile'])) {

    $bio = trim($_POST['bio']);
    $theme = trim($_POST['theme']);

    $stmt = $conn->prepare(
        "UPDATE users
         SET bio=?, theme=?
         WHERE id=?"
    );

    $stmt->bind_param(
        "ssi",
        $bio,
        $theme,
        $user_id
    );

    $stmt->execute();

    $success = "Profile updated successfully.";
}

/*
|--------------------------------------------------------------------------
| AVATAR UPLOAD
|--------------------------------------------------------------------------
*/

if(isset($_POST['upload_avatar'])) {

    if(!empty($_FILES['avatar']['name'])) {

        $file = $_FILES['avatar'];

        $ext = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if(in_array($ext, $allowed)) {

            if(!is_dir("uploads/avatars")) {
                mkdir("uploads/avatars", 0777, true);
            }

            $filename = time() . "_" . rand(1000,9999) . "." . $ext;

            $path = "uploads/avatars/" . $filename;

            move_uploaded_file(
                $file['tmp_name'],
                $path
            );

            $stmt = $conn->prepare(
                "UPDATE users
                 SET avatar=?
                 WHERE id=?"
            );

            $stmt->bind_param(
                "si",
                $path,
                $user_id
            );

            $stmt->execute();

            $success = "Avatar uploaded successfully.";

        } else {

            $error = "Invalid image format.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| ADD LINK
|--------------------------------------------------------------------------
*/

if(isset($_POST['add_link'])) {

    $title = trim($_POST['title']);
    $url = trim($_POST['url']);

    $thumbnailPath = null;

    if(
        !empty($title) &&
        filter_var($url, FILTER_VALIDATE_URL)
    ) {

        /*
        |--------------------------------------------------------------------------
        | 1. USER UPLOADED THUMBNAIL (HIGHEST PRIORITY)
        |--------------------------------------------------------------------------
        */
        if(!empty($_FILES['thumbnail']['name'])) {

            $file = $_FILES['thumbnail'];

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];

            if(in_array($ext, $allowed)) {

                if(!is_dir("uploads/link_thumbnails")) {
                    mkdir("uploads/link_thumbnails", 0777, true);
                }

                $filename = time() . "_" . rand(1000,9999) . "." . $ext;

                $thumbnailPath = "uploads/link_thumbnails/" . $filename;

                move_uploaded_file($file['tmp_name'], $thumbnailPath);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. AUTO FALLBACK (FAVICON IF NO UPLOAD)
        |--------------------------------------------------------------------------
        */
        if(empty($thumbnailPath)) {

            $host = parse_url($url, PHP_URL_HOST);

            if($host) {
                $thumbnailPath = "https://www.google.com/s2/favicons?sz=128&domain=" . $host;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. SAVE TO DB
        |--------------------------------------------------------------------------
        */
        $stmt = $conn->prepare(
            "INSERT INTO bio_links (user_id, title, url, thumbnail)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isss",
            $user_id,
            $title,
            $url,
            $thumbnailPath
        );

        $stmt->execute();

        $success = "Link added successfully.";

    } else {
        $error = "Invalid link.";
    }
}

/*
|--------------------------------------------------------------------------
| DELETE LINK
|--------------------------------------------------------------------------
*/

if(isset($_POST['delete_id'])) {

    $id = (int) $_POST['delete_id'];

    $stmt = $conn->prepare(
        "DELETE FROM bio_links WHERE id=? AND user_id=?"
    );

    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();

    header("Location: bio-settings.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| GET USER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT * FROM users WHERE id=?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$profile_url = "https://lynk.page.gd/@" . $user['username'];

/*
|--------------------------------------------------------------------------
| GET LINKS
|--------------------------------------------------------------------------
*/

$linkStmt = $conn->prepare(
    "SELECT * FROM bio_links
     WHERE user_id=?
     ORDER BY id DESC"
);

$linkStmt->bind_param("i", $user_id);
$linkStmt->execute();

$links = $linkStmt->get_result();
?>

<div class="app-wrapper">
    <div class="container" style="display:flex; justify-content:center;">
        <div class="auth-container" style="max-width:700px; width:100%;">
        <h2 style="margin-bottom:20px;">
            Bio Settings
        </h2>

        <?php if($success): ?>
            <div class="alert alert-success">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-error">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <!-- =========================
             AVATAR
        ========================== -->

<form method="POST" enctype="multipart/form-data" id="avatarForm">

    <div class="avatar-wrapper">

        <img
            src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : 'https://via.placeholder.com/120'; ?>"
            class="profile-avatar"
            id="avatarPreview"
        >

        <!-- hidden file input -->
        <input
            type="file"
            name="avatar"
            id="avatarInput"
            accept="image/*"
            hidden
        >

        <!-- overlay button -->
        <label for="avatarInput" class="avatar-upload-btn">
            +
        </label>

    </div>

    <!-- optional fallback (no button needed anymore) -->
    <input type="hidden" name="upload_avatar" value="1">

</form>
        <hr style="margin:30px 0;border-color:#1e293b;">

        <!-- =========================
             PROFILE
        ========================== -->
        <!-- =========================
     PROFILE LINK
========================== -->

<div class="form-group">

    <label>Your Profile Link</label>

    <div class="profile-link-box">

        <!-- plain text -->
        <div 
            id="profileLinkText"
            class="profile-url-text"
        >
            <?= $profile_url; ?>
        </div>

        <!-- copy button -->
<button 
    type="button"
    class="icon-btn"
    id="copyBtn"
    title="Copy link"
>
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>

    </svg>
</button>

        <!-- open button -->
        <button 
            type="button"
            class="icon-btn"
            id="openBtn"
            title="Open profile"
        >
            ↗
        </button>

    </div>

    <div id="profileData" data-url="<?= htmlspecialchars($profile_url); ?>"></div>

    <small id="copyStatus" style="color:#94a3b8;"></small>

</div>

        <form method="POST">

            <div class="form-group">

                <label>Bio</label>

                <textarea
                    name="bio"
                    class="input"
                    placeholder="Tell people about yourself..."
                ><?= htmlspecialchars($user['bio'] ?? ''); ?></textarea>

            </div>

            <div class="form-group">

                <label>Theme</label>

                <select name="theme" class="input">

    <option value="default" <?= $user['theme']=='default'?'selected':'' ?>>
        Default
    </option>

    <option value="dark" <?= $user['theme']=='dark'?'selected':'' ?>>
        Dark
    </option>

    <option value="light" <?= $user['theme']=='light'?'selected':'' ?>>
        Light
    </option>

    <option value="purple" <?= $user['theme']=='purple'?'selected':'' ?>>
        Purple
    </option>

</select>

            </div>

            <button
                class="btn btn-success"
                name="save_profile"
            >
                Save Profile
            </button>

        </form>

        <hr style="margin:30px 0;border-color:#1e293b;">

        <!-- =========================
             ADD LINK
        ========================== -->

        <form method="POST" enctype="multipart/form-data">

    <h3 style="margin-bottom:15px;">
        Add New Link
    </h3>

    <input type="text" name="title" class="input" placeholder="Button Title" required>

    <input type="url" name="url" class="input" placeholder="https://example.com" required>

    <!-- NEW: thumbnail upload -->
    <input type="file" name="thumbnail" class="input" accept="image/*">

    <button class="btn btn-primary" name="add_link">
        Add Link
    </button>

</form>

        <hr style="margin:30px 0;border-color:#1e293b;">

        <!-- =========================
             USER LINKS
        ========================== -->

        <h3 style="margin-bottom:15px;">
            Your Links
        </h3>

        <?php while($link = $links->fetch_assoc()): ?>

<div class="ig-card" data-id="<?= $link['id']; ?>">

    <!-- LEFT: THUMBNAIL -->
    <div class="ig-thumb-wrapper">

        <img 
            src="<?= !empty($link['thumbnail']) 
                ? htmlspecialchars($link['thumbnail']) 
                : 'https://via.placeholder.com/80'; ?>"
            class="ig-thumb"
            onclick="triggerUpload(<?= $link['id']; ?>)"
            id="thumb-<?= $link['id']; ?>"
        >

        <input 
            type="file"
            accept="image/*"
            hidden
            id="file-<?= $link['id']; ?>"
            onchange="uploadThumb(event, <?= $link['id']; ?>)"
        >

    </div>

    <!-- RIGHT: CONTENT -->
<div class="ig-content">

    <!-- VIEW MODE -->
    <div id="view-<?= $link['id']; ?>">

        <div class="ig-title">
            <?= htmlspecialchars($link['title']); ?>
        </div>

        <div class="ig-url">
            <?= htmlspecialchars($link['url']); ?>
        </div>

        <!-- CLICK ANALYTICS -->
    <div class="ig-actions-row">
    <span class="click-badge">👁 <?= (int)$link['clicks']; ?></span>
</div>

    </div>

    <!-- EDIT MODE -->
    <form 
        method="POST"
        action="update-link.php"
        id="edit-<?= $link['id']; ?>"
        style="display:none;"
    >

        <input type="hidden" name="id" value="<?= $link['id']; ?>">

        <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($link['title']); ?>"
            class="ig-input"
            required
        >

        <input
            type="url"
            name="url"
            value="<?= htmlspecialchars($link['url']); ?>"
            class="ig-input"
            required
        >

        <div class="ig-actions">

            <button class="ig-save">
                Save
            </button>

            <button
                type="button"
                class="ig-cancel"
                onclick="cancelEdit(<?= $link['id']; ?>)"
            >
                Cancel
            </button>

        </div>

    </form>

    <!-- ACTION BUTTONS -->
    <div class="ig-actions-row">

    <a
        href="<?= htmlspecialchars($link['url']); ?>"
        target="_blank"
        class="ig-icon-btn"
        title="Open"
    >
        ↗
    </a>

    <button
        type="button"
        class="ig-icon-btn"
        onclick="editLink(<?= $link['id']; ?>)"
        title="Edit"
    >
        ✎
    </button>

<form method="POST" class="ig-inline-form">

        <input
            type="hidden"
            name="delete_id"
            value="<?= $link['id']; ?>"
        >

        <button
            class="ig-icon-btn delete"
            onclick="return confirm('Delete this link?')"
            title="Delete"
        >
            ×
        </button>

    </form>

</div>

</div>

</div>
<?php endwhile; ?>
     </div>

    </div>

</div>


<?php include 'includes/footer.php'; ?>