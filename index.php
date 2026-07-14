<?php
/*
🌑 Sfond i zi me gradient neon.
💚 Panel "glass" me efekt blur.
💚 Border dhe glow lime neon.
🟢 Status "ONLINE" me LED pulsues.
🎛️ Butona në stilin TRC4 Broadcast UI Neon.
📡 Pamje që ngjan me një broadcast/control panel
*/
// ==========================
// BASIC ENV DETECTION
// ==========================
$is_local_host = in_array($_SERVER["REMOTE_ADDR"], ["127.0.0.1", "::1", "localhost"], true);

// ==========================
// QUERY HANDLING (SAFE)
// ==========================
if (isset($_GET["q"])) {
    $query = $_GET["q"];

    // Allow-list approach
    if ($query === "info") {

        // phpinfo allowed ONLY on localhost
        if ($is_local_host) {phpinfo();
            exit;
        }

        http_response_code(403);
        exit("Forbidden! Allowed ONLY on localhost");
    }

    // Unknown query
    http_response_code(404);
    exit("Invalid Parameter");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Broadcast Server</title>
<link rel="shortcut icon" href="https://kodi.al/favicon.ico"/>
<link href="https://fonts.googleapis.com/css?family=Karla:400" rel="stylesheet">
<style>
/* TRC4 Broadcast UI Neon */
html,
body{
    margin:0;
    height:100%;
    overflow:hidden;
    background:#030303;
    font-family:Arial,sans-serif;
    color:#66ff00;
}

.trc4-bg{
    position:fixed;
    inset:0;
    background:
    radial-gradient(circle at top,#163600 0,#030303 65%);
}

.container{
    position:relative;
    width:100%;
    height:100%;
    display:flex;
    justify-content:center;
    align-items:center;
}

.panel{
    width:760px;
    max-width:90%;
    background:rgba(0,0,0,.75);
    border:1px solid #66ff00;
    border-radius:18px;
    padding:45px;
    backdrop-filter:blur(12px);
    box-shadow:0 0 20px rgba(102,255,0,.30),inset 0 0 20px rgba(102,255,0,.05);
}

.logo{
    font-size:14px;
    letter-spacing:8px;
    text-align:center;
    opacity:.8;
    margin-bottom:15px;
}

h1{
    margin:0;
    font-size:42px;
    text-align:center;
    text-shadow:0 0 20px #66ff00;
}

.status{
    width:max-content;
    margin:25px auto;
    padding:10px 20px;
    border:1px solid #66ff00;
    border-radius:50px;
    box-shadow:0 0 15px rgba(102,255,0,.4);
}

.dot{
    width:10px;
    height:10px;
    background:#66ff00;
    display:inline-block;
    border-radius:50%;
    margin-right:10px;
    box-shadow:0 0 12px #66ff00;
    animation:pulse 1s infinite;
}

@keyframes pulse{
    0%{opacity:1;}
    50%{opacity:.35;}
    100%{opacity:1;}
}

.info{
    margin-top:35px;
}

.row{
    display:flex;
    justify-content:space-between;
    padding:14px 0;
    border-bottom:1px solid rgba(102,255,0,.15);
}

.row span{
    opacity:.7;
}

.row strong{
    color:#9cff4d;
}

.buttons{
    margin-top:35px;
    text-align:center;
}

.btn{
    display:inline-block;
    padding:12px 26px;
    text-decoration:none;
    color:#66ff00;
    border:1px solid #66ff00;
    border-radius:10px;
    transition:.25s;
    box-shadow:0 0 15px rgba(102,255,0,.25);
}

.btn:hover{
    background:#66ff00;
    color:#000;
    box-shadow:0 0 25px #66ff00;
}
</style>
</head>

<body>
<div class="trc4-bg"></div>
<div class="container">
<div class="panel">
<div class="logo">TRC4</div>
<h1>Broadcast Server</h1>
<div class="status">
<span class="dot"></span>ONLINE
</div>
<div class="info">
<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
$protocol = 'http://';
} else {
$protocol = 'https://';
}
if ($is_local_host): ?>

<div class="row">
<span>Server</span>
<strong><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE']) ?></strong>
</div>

<div class="row">
<span>IP</span>
<strong><?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?></strong>
</div>

<div class="row">
<span>HOST</span>
<strong><?= htmlspecialchars($_SERVER['SERVER_NAME']) ?></strong>
</div>

<div class="row">
<span>ROOT URL</span>
<strong><?= htmlspecialchars($protocol . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/") ?></strong>
</div>

<div class="row">
<span>PHP Version</span>
<strong><?= PHP_VERSION ?></strong>
</div>

<div class="row">
<span>Document Root</span>
<strong><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?></strong>
</div>

<div class="buttons">
<a class="btn" href="?q=info">PHP INFO</a>
</div>

<?php else: ?>
<div class="row">
<span>Status</span>
<strong>ONLINE</strong>
</div>

<div class="row">
<span>PHP</span>
<strong>READY</strong>
</div>
<?php endif; ?>
<div class="buttons">
<p>
<a class="btn" href="https://kodi.al/" target="_blank" rel="noopener">Kodi.AL</a>
</p>
</div>
</div>
</div>
</body>
</html>