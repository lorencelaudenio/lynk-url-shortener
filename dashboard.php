<?php
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Create short link */
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

/* Get links */
$stmt = $conn->prepare(
    "SELECT * FROM links WHERE user_id=? ORDER BY id DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();
$links = $stmt->get_result();

$totalClicks = 0;

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
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard - Lynk</title>

<style>
    .logo{
    font-size:28px;
    font-weight:bold;
    color:white;
    text-decoration:none;
}

.logo:hover{
    opacity:0.8;
}
    
.copy-btn{
    width:auto;   /* IMPORTANT */
    display:inline-block;
    background:#22c55e;
    color:white;
    border:none;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
}

.copy-btn:hover{
    background:#16a34a;
}
    .modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.7);
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:#1e293b;
    padding:25px;
    border-radius:16px;
    width:90%;
    max-width:400px;
}

.modal-content input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:none;
}

.modal-content button{
    width:100%;
    margin-top:10px;
}

.close-btn{
    background:#ef4444;
    margin-top:10px;
}
    .link-card a{
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
}

.link-card a:hover{
    opacity:0.7;
}

body{
    margin:0;
    font-family:Arial;
    background:#0f172a;
    color:white;
}

.container{
    max-width:1100px;
    margin:auto;
    padding:20px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.logo{
    font-size:28px;
    font-weight:bold;
}

.logo span{ color:#3b82f6; }

.logout a{
    color:white;
    text-decoration:none;
    background:#ef4444;
    padding:10px 15px;
    border-radius:10px;
}

/* STATS */
.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
    margin-bottom:25px;
}

.card{
    background:#1e293b;
    padding:20px;
    border-radius:16px;
}

.card h3{
    margin:0;
    color:#94a3b8;
    font-size:14px;
}

.card h1{
    margin:10px 0 0;
}

/* FORM */
.form-box{
    background:#1e293b;
    padding:20px;
    border-radius:16px;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:15px;
    border-radius:10px;
    border:none;
    margin-bottom:10px;
}

.form-box button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    background:#3b82f6;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

/* LINKS */
.link-card{
    background:#1e293b;
    padding:15px;
    border-radius:12px;
    margin-bottom:10px;
}

.short{
    color:#3b82f6;
    font-weight:bold;
}

.long{
    color:#94a3b8;
    font-size:13px;
    word-break:break-all;
}

.clicks{
    float:right;
    color:#22c55e;
}

.about-btn{
    background:#334155 !important;
    margin-right:10px;
}

</style>

</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">

<a href="index.php" class="logo">
    Lyn<span>k</span>
</a>

        <div class="logout">
            <a href="about.php" class="about-btn">
    About
</a>
            <a href="logout.php">Logout</a>
        </div>

    </div>

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
    <h1><?php echo $totalClicks; ?></h1>
</div>

        <div class="card">
            <h3>Active Links</h3>
            <h1><?php echo $links->num_rows; ?></h1>
        </div>

    </div>

    <!-- CREATE LINK -->
    <div class="form-box">

        <h3>Create Short Link</h3>

        <form method="POST">

            <input type="url"
                   name="url"
                   placeholder="Paste long URL..."
                   required>

            <button name="shorten">
                Shorten URL
            </button>

        </form>

    </div>

    <?php if(isset($error)): ?>
        <p style="color:red;">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>

    <!-- LINKS LIST -->
    <h3>Your Links</h3>

    <?php while($row = $links->fetch_assoc()): ?>

<div class="link-card">

   <div class="short">

    <a href="https://lynk.page.gd/<?php echo $row['short_code']; ?>" 
       target="_blank"
       style="color:#3b82f6; text-decoration:none; font-weight:bold;">

        https://lynk.page.gd/<?php echo $row['short_code']; ?>

    </a>

</div>

    <div class="long">
        <?php echo $row['original_url']; ?>
    </div>

    <div class="clicks">
        <?php echo $row['clicks']; ?> clicks
    </div>

    <div style="margin-top:10px; display:flex; gap:10px;">

   <a href="#"
   style="color:#facc15;"
   onclick="openModal(
        <?php echo $row['id']; ?>,
        '<?php echo addslashes($row['original_url']); ?>'
   )">
   Edit
</a>

        <a href="delete.php?id=<?php echo $row['id']; ?>"
           style="color:#ef4444;"
           onclick="return confirm('Delete this link?')">
           Delete
        </a>

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

            <input type="hidden" name="id" id="edit_id">

            <input type="url"
                   name="url"
                   id="edit_url"
                   required>

            <button type="submit" name="update">
                Update Link
            </button>

        </form>

        <button onclick="closeModal()" class="close-btn">
            Close
        </button>

    </div>

</div>

<script>

function openModal(id, url) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_url').value = url;
    document.getElementById('editModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    let modal = document.getElementById('editModal');
    if(event.target == modal){
        modal.style.display = "none";
    }
}

</script>

<script>

function copyLink(btn) {

    const url = btn.getAttribute("data-url");

    navigator.clipboard.writeText(url).then(() => {

        btn.innerText = "Copied!";

        setTimeout(() => {
            btn.innerText = "Copy";
        }, 1500);

    }).catch(() => {

        alert("Failed to copy link");

    });
}

</script>

</body>
</html>