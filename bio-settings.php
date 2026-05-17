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

    if(
        !empty($title) &&
        filter_var($url, FILTER_VALIDATE_URL)
    ) {

        $stmt = $conn->prepare(
            "INSERT INTO bio_links
            (user_id,title,url)
            VALUES(?,?,?)"
        );

        $stmt->bind_param(
            "iss",
            $user_id,
            $title,
            $url
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

                    <option value="default">
                        Default
                    </option>

                    <option value="dark">
                        Dark
                    </option>

                    <option value="light">
                        Light
                    </option>

                    <option value="purple">
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

        <form method="POST">

            <h3 style="margin-bottom:15px;">
                Add New Link
            </h3>

            <input
                type="text"
                name="title"
                class="input"
                placeholder="Button Title"
                required
            >

            <input
                type="url"
                name="url"
                class="input"
                placeholder="https://example.com"
                required
            >

            <button
                class="btn btn-primary"
                name="add_link"
            >
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

            <div class="link-card">

                <strong>
                    <?= htmlspecialchars($link['title']); ?>
                </strong>

                <div class="long">
                    <?= htmlspecialchars($link['url']); ?>
                </div>

                <div style="margin-top:10px;">

<form method="POST" style="display:inline;">
    <input type="hidden" name="delete_id" value="<?= $link['id']; ?>">

    <button
        class="btn btn-danger btn-inline"
        onclick="return confirm('Delete this link?')"
    >
        Delete
    </button>
</form>                </div>

            </div>

        <?php endwhile; ?>

    </div>

</div>
</div>

<?php include 'includes/footer.php'; ?>