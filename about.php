<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>About - Lynk</title>

<meta name="description"
      content="Learn more about Lynk URL Shortener and its features.">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#0f172a;
    color:white;
}

.container{
    width:90%;
    max-width:1100px;
    margin:auto;
}

/* NAVBAR */
nav{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 0;
}

.logo{
    font-size:28px;
    font-weight:bold;
    color:white;
    text-decoration:none;
}

.logo span{
    color:#3b82f6;
}

.nav-links a{
    color:white;
    text-decoration:none;
    margin-left:15px;
    font-size:15px;
}

/* HERO */
.hero{
    padding:90px 0 60px;
    text-align:center;
}

.hero h1{
    font-size:54px;
    margin-bottom:20px;
}

.hero p{
    color:#cbd5e1;
    max-width:700px;
    margin:auto;
    line-height:1.8;
    font-size:18px;
}

/* CONTENT */
.about-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
    margin-top:50px;
}

.card{
    background:#1e293b;
    padding:30px;
    border-radius:18px;
    transition:0.2s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    margin-bottom:15px;
    font-size:22px;
}

.card p{
    color:#cbd5e1;
    line-height:1.7;
}

/* CTA */
.cta{
    margin-top:70px;
    background:#1e293b;
    padding:45px;
    border-radius:24px;
    text-align:center;
}

.cta h2{
    font-size:36px;
    margin-bottom:15px;
}

.cta p{
    color:#cbd5e1;
    margin-bottom:25px;
}

.cta a{
    display:inline-block;
    background:#3b82f6;
    color:white;
    padding:14px 22px;
    border-radius:12px;
    text-decoration:none;
    font-weight:bold;
}

/* FOOTER */
footer{
    text-align:center;
    padding:40px 0;
    color:#94a3b8;
    margin-top:60px;
    border-top:1px solid rgba(255,255,255,0.08);
}

@media(max-width:768px){

    .hero h1{
        font-size:40px;
    }

}

</style>

</head>
<body>

<div class="container">

    <!-- NAVBAR -->
    <nav>

        <a href="index.php" class="logo">
            Lyn<span>k</span>
        </a>

        <div class="nav-links">

            <a href="index.php">Home</a>

            <?php if(isset($_SESSION['user_id'])): ?>

                <a href="dashboard.php">Dashboard</a>

            <?php else: ?>

                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

            <?php endif; ?>

        </div>

    </nav>

    <!-- HERO -->
    <section class="hero">

        <h1>About Lynk</h1>

        <p>
            Lynk is a modern URL shortener designed to make
            sharing links faster, cleaner, and smarter.
            Create short links instantly, manage them easily,
            and track clicks with a beautiful dashboard.
        </p>

    </section>

    <!-- FEATURES -->
    <section class="about-grid">

        <div class="card">

            <h3>⚡ Fast Shortening</h3>

            <p>
                Instantly convert long and messy URLs into
                clean and shareable links.
            </p>

        </div>

        <div class="card">

            <h3>🎯 Custom Aliases</h3>

            <p>
                Registered users can personalize links
                using custom aliases for branding and campaigns.
            </p>

        </div>

        <div class="card">

            <h3>📊 Analytics</h3>

            <p>
                Track clicks and monitor how your links
                perform directly from your dashboard.
            </p>

        </div>

        <div class="card">

            <h3>🔒 Secure Redirects</h3>

            <p>
                Lynk provides safe and reliable redirects
                for every shortened URL.
            </p>

        </div>

    </section>

    <!-- CTA -->
    <section class="cta">

        <h2>Start Shortening Smarter 🚀</h2>

        <p>
            Join Lynk today and manage your links with ease.
        </p>

        <a href="register.php">
            Create Free Account
        </a>

    </section>

    <!-- FOOTER -->
    <footer>

        © <?php echo date("Y"); ?> Lynk URL Shortener
        • https://lynk.page.gd

    </footer>

</div>

</body>
</html>