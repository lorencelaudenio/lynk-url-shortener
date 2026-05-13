<?php
session_start();

include 'config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$short_url = "";
$is_guest = !isset($_SESSION['user_id']);
$show_cta = false;

if(isset($_POST['shorten'])) {

    $url = trim($_POST['url']);

    if(!filter_var($url, FILTER_VALIDATE_URL)) {
        $error = "Invalid URL format.";
    } else {

        $short = substr(md5(uniqid()), 0, 6);

        $stmt = $conn->prepare(
            "INSERT INTO links(user_id, original_url, short_code)
             VALUES(NULL, ?, ?)"
        );

        $stmt->bind_param("ss", $url, $short);

if($stmt->execute()) {

    $short_url = "https://lynk.page.gd/$short";

    if($is_guest){
        $show_cta = true;
    }

} else {
    $error = "Something went wrong.";
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Lynk - Smart URL Shortener</title>

    <meta name="description"
          content="Shorten URLs instantly with Lynk. Smart, fast, and simple URL shortener.">

    <link rel="stylesheet" href="style.css">

    <style>
        .cta-box{
    margin-top:18px;
    padding:18px;
    border-radius:14px;
    background:#1e293b;
    color:#e2e8f0;
    text-align:center;
}

.cta-box p{
    margin-top:8px;
    color:#94a3b8;
    font-size:14px;
}

.cta-actions{
    margin-top:12px;
    display:flex;
    gap:10px;
    justify-content:center;
    flex-wrap:wrap;
}

.cta-primary{
    background:#3b82f6;
    color:white;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
}

.cta-primary:hover{
    background:#2563eb;
}

.cta-secondary{
    background:transparent;
    border:1px solid #475569;
    color:white;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
}

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

        nav{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:20px 0;
        }

        .logo{
            font-size:28px;
            font-weight:bold;
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

        .hero{
            min-height:85vh;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center;
        }

        .hero h1{
            font-size:58px;
            margin-bottom:15px;
            line-height:1.1;
        }

        .hero p{
            font-size:18px;
            color:#cbd5e1;
            margin-bottom:35px;
            max-width:650px;
        }

        .shortener-box{
            background:white;
            padding:20px;
            border-radius:18px;
            width:100%;
            max-width:720px;
            box-shadow:0 10px 30px rgba(0,0,0,0.25);
        }

        .shortener-form{
            display:flex;
            gap:10px;
        }

        .shortener-form input{
            flex:1;
            padding:16px;
            border:1px solid #dbeafe;
            border-radius:12px;
            font-size:16px;
            outline:none;
        }

        .shortener-form button{
            padding:16px 28px;
            border:none;
            border-radius:12px;
            background:#3b82f6;
            color:white;
            font-size:16px;
            cursor:pointer;
            transition:0.2s;
        }

        .shortener-form button:hover{
            background:#2563eb;
        }

        .result{
            margin-top:20px;
            background:#eff6ff;
            padding:16px;
            border-radius:12px;
            color:#111827;
            word-break:break-all;
        }

        .result a{
            color:#2563eb;
            text-decoration:none;
            font-weight:bold;
        }

        .error{
            margin-top:20px;
            background:#ef4444;
            padding:15px;
            border-radius:10px;
            color:white;
        }

        .features{
            padding:80px 0;
        }

        .section-title{
            text-align:center;
            margin-bottom:40px;
        }

        .section-title h2{
            font-size:36px;
            margin-bottom:10px;
        }

        .section-title p{
            color:#94a3b8;
        }

        .features-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
        }

        .feature-card{
            background:#1e293b;
            padding:28px;
            border-radius:18px;
            transition:0.2s;
        }

        .feature-card:hover{
            transform:translateY(-5px);
        }

        .feature-card h3{
            margin-bottom:12px;
            font-size:20px;
        }

        .feature-card p{
            color:#cbd5e1;
            line-height:1.6;
        }

        footer{
            text-align:center;
            padding:35px 0;
            color:#94a3b8;
            border-top:1px solid rgba(255,255,255,0.08);
            margin-top:40px;
        }

        @media(max-width:768px){

            .hero h1{
                font-size:40px;
            }

            .shortener-form{
                flex-direction:column;
            }

            .shortener-form button{
                width:100%;
            }

        }

    </style>

</head>
<body>

<div class="container">

    <nav>

        <div class="logo">
            Lyn<span>k</span>
        </div>

        <div class="nav-links">
            <a href="about.php">About</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>

    </nav>

    <section class="hero">

        <h1>
            Smart URL Shortener
        </h1>

        <p>
            Shorten long URLs instantly with analytics,
            custom links, and powerful link management.
        </p>

        <div class="shortener-box">

            <form method="POST" class="shortener-form">

                <input
                    type="url"
                    name="url"
                    placeholder="Paste your long URL here..."
                    required
                >

                

                <button name="shorten">
                    Shorten URL
                </button>

            </form>

            <?php if(!empty($short_url)): ?>

                <div class="result">

                    <strong>Your Short URL</strong>

                    <br><br>

                    <a href="<?php echo $short_url; ?>"
                       target="_blank">

                        <?php echo $short_url; ?>

                    </a>

                </div>

                <?php if($show_cta): ?>

    <div class="cta-box">

        🚀 Want to manage, edit, and track your links?

        <p>
            Create a free account to unlock your dashboard and analytics.
        </p>

        <div class="cta-actions">

            <a href="register.php" class="cta-primary">
                Create Free Account
            </a>

            <a href="login.php" class="cta-secondary">
                Login
            </a>

        </div>

    </div>

<?php endif; ?>

            <?php endif; ?>

            <?php if(isset($error)): ?>

                <div class="error">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>

        </div>

    </section>

    <section class="features">

        <div class="section-title">

            <h2>
                Why Use Lynk?
            </h2>

            <p>
                Everything you need to manage and share links smarter.
            </p>

        </div>

        <div class="features-grid">

            <div class="feature-card">

                <h3>⚡ Instant Shortening</h3>

                <p>
                    Create clean and short links within seconds.
                </p>

            </div>

            <div class="feature-card">

                <h3>📊 Link Analytics</h3>

                <p>
                    Track clicks and monitor your traffic performance.
                </p>

            </div>

            <div class="feature-card">

                <h3>🎯 Custom URLs</h3>

                <p>
                    Personalize links using custom aliases.
                </p>

            </div>

            <div class="feature-card">

                <h3>🔒 Secure Redirects</h3>

                <p>
                    Reliable and safe redirect system for all links.
                </p>

            </div>

        </div>

    </section>

    <footer>

        © <?php echo date("Y"); ?> Lynk URL Shortener
        • https://lynk.page.gd

    </footer>

</div>

</body>
</html>