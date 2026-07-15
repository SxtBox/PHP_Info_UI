<?php
/*
========================================================
TRC4 BROADCAST DIAGNOSTICS CENTER COMPLETE CODE
========================================================

✅ TRC4 Broadcast UI Neon Style
✅ PHP Server Dashboard
✅ Security Scanner
✅ File Monitor
✅ Network Monitor
✅ Debug Console
✅ System Health Engine
✅ Final Command Center
*/

/*
========================================================
 TRC4 Broadcast Diagnostics Center
 PHP CORE ENGINE
========================================================
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
========================================================
 ENVIRONMENT
========================================================
*/
$isLocal = in_array(
    $_SERVER['REMOTE_ADDR'] ?? '',
    [
        '127.0.0.1',
        '::1'
    ],
    true
);

/*
========================================================
 TIMEZONE
========================================================
*/
define('TRC4_TIMEZONE','+8');

/*
========================================================
 SAFE SERVER GET
========================================================
*/
function trc4_server($name)
{
    return htmlspecialchars($_SERVER[$name] ?? '[undefined]',ENT_QUOTES,'UTF-8');
}

/*
========================================================
 QUERY HANDLER
========================================================
*/
$action = $_GET['act'] ?? null;

/*
 PHP INFO
 ONLY LOCALHOST
*/

if($action === 'phpinfo')
{

    if($isLocal)
    {
		phpinfo();
        exit;
    }

    http_response_code(403);
    exit('TRC4 SECURITY: phpinfo Allowed Only on Localhost'
    );
}

/*
========================================================
 EXTERNAL IP
========================================================
*/

if($action === 'getip')
{

$data = trc4_server('SERVER_NAME') .'|'. trc4_server('REMOTE_ADDR') .'|'. trc4_server('SERVER_SOFTWARE') .'|'. trc4_server('DOCUMENT_ROOT');

    /*
       External IP service
       can be replaced later
    */

    $ip = @file_get_contents('https://api.ipify.org');

    if(
        preg_match('/^\d+\.\d+\.\d+\.\d+$/',$ip)
    )
    {
        echo $ip;
    }
    else
    {
        echo 'false';
    }
    exit;
}

/*
========================================================
 PHP INFORMATION
========================================================
*/

$trc4Info = [
    'php_ini' => function_exists('php_ini_loaded_file') ? php_ini_loaded_file() : '[undefined]',
    'php_version' => PHP_VERSION,
    'php_sapi' => PHP_SAPI,
];

/*
========================================================
 HELPERS
========================================================
*/

function trc4_status($value)
{
    if($value)
    {
        return'<span class="badge ok">ENABLED</span>';
    }

    return'<span class="badge error">DISABLED</span>';
}

/*
========================================================
 TRC4 COMPONENT DETECTOR
========================================================
*/
function trc4_extension($ext)
{
    return extension_loaded($ext);
}

function trc4_badge($state)
{
    if($state)
    {
        return '<span class="badge ok">ENABLED</span>';
    }

    return '<span class="badge error">DISABLED</span>';
}
?>
<?php
/*
========================================================
 TRC4 SECURITY ENGINE
✅ PHP Security Scanner
✅ Dangerous Functions Check
✅ Disabled Functions Monitor
✅ Display Errors Status
✅ Open Basedir Check
✅ Upload Security
✅ Debug Status
✅ Security Score Meter
✅ TRC4 Neon Warning Badges
========================================================
*/
function trc4_check($value)
{

    if(
        empty($value) ||
        $value === false ||
        $value === "0"
    )
    {
        return false;
    }
    return true;
}

function trc4_percent($ok,$total)
{

    if($total <= 0)
    {
        return 0;
    }

    return round(
        ($ok/$total)*100
    );
}

$securityChecks = [];

$securityChecks['display_errors'] = trc4_check(ini_get('display_errors'));
$securityChecks['open_basedir'] = trc4_check(ini_get('open_basedir'));
$securityChecks['file_uploads'] = trc4_check(ini_get('file_uploads'));
$securityChecks['allow_url_fopen'] = trc4_check(ini_get('allow_url_fopen'));
$disabledFunctions = ini_get('disable_functions');

$dangerFunctions = [
"exec",
"shell_exec",
"system",
"passthru",
"proc_open",
"popen"
];

$dangerFound=[];

foreach($dangerFunctions as $fn)
{

    if(
    function_exists($fn) &&
    !str_contains($disabledFunctions,$fn)
    )
    {
        $dangerFound[]=$fn;
    }
}

$securityOk = 0;

foreach($securityChecks as $item)
{
    if($item)
    {
        $securityOk++;
    }
}

$securityScore = trc4_percent($securityOk,count($securityChecks)+1);
?>
<?php
/*
========================================================
TRC4 Broadcast Diagnostics Center
FILE SYSTEM MONITOR
TRC4 NEON STORAGE ENGINE
========================================================
========================================================
 TRC4 FILE SYSTEM ENGINE
 Disk Space Monitor
✅ Free / Used Space
✅ Folder Permission Scanner
✅ Writable Check
✅ Cache Folder Status
✅ Log Folder Status
✅ Upload Folder Status
✅ TRC4 Neon Storage Cards
========================================================
*/
function trc4_folder_status($path)
{

    if(!file_exists($path))
    {

        return [
            "exists" => false,
            "write" => false
        ];
    }

    return [
        "exists"=>true,
        "write"=>is_writable($path)
    ];
}

function trc4_size($bytes)
{

    if($bytes<=0)
    {
        return "0 B";
    }

    $units=[
        "B",
        "KB",
        "MB",
        "GB",
        "TB"
    ];

    $i = floor(log($bytes,1024));
    return round($bytes / pow(1024,$i), 2) ." ". $units[$i];
}

$diskTotal = disk_total_space(__DIR__);
$diskFree = disk_free_space(__DIR__);

$diskUsed = $diskTotal-$diskFree;
$diskPercent = round(($diskUsed/$diskTotal)*100);

$trc4Folders = [
"uploads" => trc4_folder_status(__DIR__."/uploads"),
"cache" => trc4_folder_status(__DIR__."/cache"),
"logs" => trc4_folder_status(__DIR__."/logs"),
"tmp" => trc4_folder_status(sys_get_temp_dir()
)
];
?>
<?php
/*
========================================================
 TRC4 NETWORK ENGINE
PHP NETWORK ENGINE
✅ Local Server IP
✅ Remote Client IP
✅ Hostname
✅ DNS Resolver Check
✅ HTTP Response Test
✅ Public IP Detection
✅ Network Status Card
✅ TRC4 Neon Network Monitor
========================================================
========================================================
TRC4 Broadcast Diagnostics Center
NETWORK MONITOR
TRC4 NEON NETWORK ENGINE
========================================================
*/
function trc4_get_public_ip()
{

    $ip = @file_get_contents("https://api.ipify.org");

    if($ip)
    {
        return trim($ip);
    }
    return "Unavailable";
}

function trc4_http_test($url)
{

    $start = microtime(true);
    $context = stream_context_create([
        "http"=>[
            "timeout"=>3
        ]
    ]);

    $result = @file_get_contents($url,false,$context);

    $time = round((microtime(true)-$start)*1000);

    return [
        "online" => ($result!==false),
        "time" => $time
    ];
}

$networkInfo=[
"server_ip" => $_SERVER['SERVER_ADDR'] ?? "Unknown",
"client_ip" => $_SERVER['REMOTE_ADDR'] ?? "Unknown",
"hostname" => gethostname(),
"public_ip" => trc4_get_public_ip(),
"http_test" => trc4_http_test( "https://www.google.com"
)
];
?>
<?php
/*
========================================================
TRC4 LOG & DEBUG CENTER

PHP Error Log
Last Errors
Debug Status
Log File Viewer
Clear Log Button
Neon Console Style

========================================================
TRC4 Broadcast Diagnostics Center
LOG & DEBUG CENTER
TRC4 NEON DEBUG CONSOLE
========================================================

✅ PHP Error Log Monitor
✅ Last Errors Viewer
✅ Debug Mode Status
✅ Log File Detection
✅ Log Size Monitor
✅ Clear Log Button
✅ TRC4 Neon Console View
*/

/*
========================================================
 TRC4 LOG DEBUG ENGINE
========================================================
*/
$logFile = ini_get('error_log');

if(!$logFile || !file_exists($logFile))
{
    $logFile = __DIR__."/error.log";
}

$logExists = file_exists($logFile);
$logSize = $logExists ? filesize($logFile):0;

function trc4_format_size($size)
{

    if($size < 1024)
    {
        return $size." B";
    }

    if($size < 1048576)
    {
        return round($size/1024,2)." KB";
    }

    return round($size/1048576,2)." MB";
}

$lastLogs=[];

if($logExists)
{

$lines = file($logFile,FILE_IGNORE_NEW_LINES);

$lastLogs = array_slice($lines,-20);
}

/*
 CLEAR LOG
*/

if(isset($_POST['trc4_clear_log']))
{

if($logExists)
{

file_put_contents($logFile,'');
}

header("Location: ".$_SERVER['PHP_SELF']);
exit;
}

$debugMode = ini_get('display_errors');
?>
<?php
/*
========================================================
TRC4 FINAL SYSTEM ENGINE
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
========================================================
*/
$modules = [
    "PHP CORE" => true,
    "DATABASE" => function_exists('mysqli_connect'),
    "SECURITY" => ($securityScore >= 70),
    "STORAGE" => ($diskPercent < 90),
    "NETWORK" => ($networkInfo['http_test']['online'])
];

$totalModules = count($modules);
$onlineModules = 0;

foreach($modules as $status)
{
    if($status)
    {
        $onlineModules++;
    }
}

$systemHealth = round(($onlineModules/$totalModules)*100);

function trc4_status_badge($state)
{

    if($state)
    {
        return '<span class="badge ok">ONLINE</span>';
    }

    return '<span class="badge error">ERROR</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width,initial-scale=1.0">
<title>TRC4 Broadcast Diagnostics Center</title>
<link rel="icon" href="https://kodi.al/favicon.ico">
<style>
/* ===================================================
   TRC4 BROADCAST UI NEON
====================================================== */
:root {
    --trc4-bg: #020202;
    --trc4-panel:
        rgba(8, 8, 8, .88);
    --trc4-panel-soft:
        rgba(15, 15, 15, .75);
    --trc4-green: #66ff00;
    --trc4-green-soft:
        #9cff4d;
    --trc4-red:
        #ff3030;
    --trc4-yellow:
        #ffd000;
    --trc4-border:
        rgba(102, 255, 0, .45);
    --trc4-radius:
        16px;
    --trc4-glow:
        0 0 20px rgba(102, 255, 0, .35);
    --trc4-transition:
        .25s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html,
body {
    width: 100%;
    min-height: 100%;
    background:
        radial-gradient(circle at top,
            rgba(102, 255, 0, .12),
            transparent 35%),

        linear-gradient(180deg,
            #050505,
            #000);

    color: white;
    font-family: "Segoe UI", Arial, sans-serif;
    overflow-x: hidden;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    pointer-events: none;
    background-image:
        linear-gradient(rgba(102, 255, 0, .03) 1px,
            transparent 1px),

        linear-gradient(90deg,
            rgba(102, 255, 0, .03) 1px,
            transparent 1px);

    background-size:
        40px 40px;
    z-index: 0;
}

/*
======================================================
 MAIN CONTAINER
======================================================
*/
.trc4-container {
    position: relative;
    z-index: 1;
    width: 1200px;
    max-width: 95%;
    margin: 40px auto;
}

/*
======================================================
 HEADER
======================================================
*/
.trc4-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px;
    background:
        var(--trc4-panel);

    border:
        1px solid var(--trc4-border);
    border-radius:
        var(--trc4-radius);
    backdrop-filter:
        blur(12px);
    box-shadow:
        var(--trc4-glow);
}

.trc4-brand {
    display: flex;
    align-items: center;
    gap: 20px;
}

.trc4-logo {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border:
        2px solid var(--trc4-green);
    color:
        var(--trc4-green);
    font-size: 24px;
    font-weight: 900;
    letter-spacing: 2px;
    box-shadow:
        0 0 20px var(--trc4-green);
}

.trc4-header h1 {
    color:
        var(--trc4-green);
    font-size: 28px;
    text-shadow:
        0 0 15px var(--trc4-green);
}

.trc4-header p {
    color: #aaa;
    margin-top: 5px;
}

/*
======================================================
 ONLINE STATUS
======================================================
*/
.trc4-status {
    border:
        1px solid var(--trc4-green);
    padding:
        12px 25px;
    border-radius:
        50px;
    color:
        var(--trc4-green);
    font-weight: bold;
    box-shadow:
        var(--trc4-glow);
}

.led {
    display: inline-block;
    width: 12px;
    height: 12px;
    background:
        var(--trc4-green);
    border-radius: 50%;
    margin-right: 10px;
    box-shadow:
        0 0 15px var(--trc4-green);
    animation:
        trc4Pulse 1.5s infinite;
}

@keyframes trc4Pulse {
    0% {
        opacity: 1;
    }

    50% {
        opacity: .3;
    }

    100% {
        opacity: 1;
    }
}

/*
======================================================
 DASHBOARD CARDS
======================================================
*/
.trc4-card {
    margin-top: 25px;
    background:
        var(--trc4-panel);
    border:
        1px solid var(--trc4-border);
    border-radius:
        var(--trc4-radius);
    padding: 25px;
    backdrop-filter:
        blur(10px);
    box-shadow:
        0 0 15px rgba(102, 255, 0, .15);
    transition:
        var(--trc4-transition);
}

.trc4-card:hover {
    transform:
        translateY(-3px);
    box-shadow:
        0 0 30px rgba(102, 255, 0, .35);
}

.trc4-card-title {
    color:
        var(--trc4-green);
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
    border-bottom:
        1px solid rgba(102, 255, 0, .2);
    padding-bottom: 10px;
}

/*
======================================================
 INFO ROW
======================================================
*/
.trc4-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 0;
    border-bottom:
        1px solid rgba(255, 255, 255, .08);
}

.trc4-row span {
    color: #aaa;
}

.trc4-row strong {
    color:
        var(--trc4-green-soft);
    text-align: right;
}

/*
======================================================
 BADGES
======================================================
*/
.badge {
    display: inline-block;
    padding:
        5px 14px;
    border-radius:
        50px;
    font-size: 12px;
    font-weight: bold;
}

.badge.ok {
    color:
        #000;
    background:
        var(--trc4-green);
    box-shadow:
        0 0 15px var(--trc4-green);
}

.badge.error {
    color: white;
    background:
        var(--trc4-red);
    box-shadow:
        0 0 15px var(--trc4-red);
}

/*
======================================================
 BUTTONS
======================================================
*/
.trc4-btn {
    display: inline-block;
    padding:
        12px 25px;
    border:
        1px solid var(--trc4-green);
    color:
        var(--trc4-green);
    background:
        transparent;
    border-radius:
        10px;
    text-decoration: none;
    cursor: pointer;
    transition:
        .25s;
}

.trc4-btn:hover {
    background:
        var(--trc4-green);
    color: #000;
    box-shadow:
        0 0 25px var(--trc4-green);
}

/*
======================================================
 INPUTS
======================================================
*/
input {
    width: 100%;
    padding: 12px;
    background: #000;
    border:
        1px solid rgba(102, 255, 0, .5);
    color:
        var(--trc4-green);
    border-radius: 8px;
    outline: none;
}

input:focus {
    box-shadow:
        0 0 15px var(--trc4-green);
}

/*
======================================================
 RESPONSIVE
======================================================
*/
@media(max-width:700px) {
    .trc4-header {
        flex-direction: column;
        gap: 25px;
    }

    .trc4-row {
        flex-direction: column;
    }
}

/*
================================================
TRC4 MYSQL MODAL
================================================
*/
.trc4-modal {
    position: fixed;
    inset: 0;
    background:
        rgba(0, 0, 0, .85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.trc4-modal-box {
    width: 400px;
    max-width: 90%;
    padding: 30px;
    background: #050505;
    border:
        1px solid #66ff00;
    border-radius: 18px;
    text-align: center;
    box-shadow:
        0 0 40px #66ff00;
}

.trc4-modal-box h2 {
    margin-bottom: 20px;
}

/*
============================================
TRC4 SECURITY SCORE CENTER DETAILS CARD NEON
============================================
*/
.trc4-security-score {
    text-align: center;
    margin-bottom: 25px;
    font-size: 18px;
}

.trc4-security-score strong {
    display: block;
    font-size: 40px;
    color: #66ff00;
    text-shadow:
        0 0 15px #66ff00;
}

.security-bar {
    height: 12px;
    background: #111;
    border-radius: 20px;
    overflow: hidden;
    margin-top: 15px;
    border: 1px solid #333;
}

.security-bar span {
    display: block;
    height: 100%;
    background: #66ff00;
    box-shadow:
        0 0 15px #66ff00;
}

/*
========================================================
TRC4 NEON STORAGE / Extra Path Information
TRC4 NEON STORAGE ENGINE
TRC4 FILE SYSTEM MONITOR
Disk Space Monitor
✅ Free / Used Space
✅ Folder Permission Scanner
✅ Writable Check
✅ Cache Folder Status
✅ Log Folder Status
✅ Upload Folder Status
✅ TRC4 Neon Storage Cards
========================================================

==========================================
TRC4 STORAGE MONITOR
==========================================
*/
.storage-box {
    text-align: center;
    padding: 20px;
}

.storage-box strong {
    font-size: 42px;
    display: block;
    color: #66ff00;
    text-shadow:
        0 0 15px #66ff00;
}

.storage-box p {
    color: #aaa;
    margin-top: 15px;
}

/*
========================================================
 TRC4 NETWORK MONITOR ENGINE
 NETWORK PANEL HTML
Local Server IP
✅ Remote Client IP
✅ Hostname
✅ DNS Resolver Check
✅ HTTP Response Test
✅ Public IP Detection
✅ Network Status Card
✅ TRC4 Neon Network Monitor
========================================================
========================================================
TRC4 Broadcast Diagnostics Center
NETWORK MONITOR
TRC4 NEON NETWORK ENGINE
========================================================
*/
.network-status {
    text-align: center;
    padding: 20px;
}

.network-status p {
    color: #66ff00;
    font-size: 18px;
    margin-top: 15px;
}

/*
==========================================
TRC4 DEBUG CONSOLE
==========================================
*/

.trc4-console {
    background: #000;
    border:
        1px solid #222;
    padding: 15px;
    height: 250px;
    overflow: auto;
    font-family:
        monospace;
    font-size: 13px;
    color: #66ff00;
    box-shadow:
        inset 0 0 20px #000;
}

.trc4-console div {
    padding: 5px;
    border-bottom:
        1px solid #111;
}

.trc4-btn.danger {
    border-color: #ff3030;
    color: #ff3030;
}

.trc4-btn.danger:hover {
    background: #ff3030;
    color: #000;
}

/*
================================================
TRC4 COMMAND CENTER
========================================================
TRC4 FINAL SYSTEM ENGINE COMMAND FINAL FOOTER
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
================================================
*/
.trc4-header {
    text-align: center;
    padding: 35px;
    border-bottom:
        1px solid #222;
    margin-bottom: 25px;
}

.trc4-logo {
    font-size: 35px;
    font-weight: bold;
    color: #66ff00;
    text-shadow:
        0 0 20px #66ff00;
}

.trc4-subtitle {
    color: #aaa;
    margin-top: 10px;
}

.health-number {
    text-align: center;
    font-size: 70px;
    font-weight: bold;
    color: #66ff00;
    text-shadow:
        0 0 25px #66ff00;
}

.center {
    text-align: center;
    color: #aaa;
}

.trc4-grid {
    display: grid;
    grid-template-columns:

        repeat(auto-fit,
            minmax(180px, 1fr));

    gap: 20px;
    margin-top: 25px;
}

.module-card {
    background: #050505;
    border:
        1px solid #222;
    padding: 25px;
    text-align: center;
    border-radius: 12px;
    transition: .3s;
}

.module-card:hover {
    border-color: #66ff00;
    box-shadow:
        0 0 20px #66ff00;
}

.module-card h3 {
    color: #fff;
}

.quick-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.quick-actions a {
    padding: 12px 25px;
    border:
        1px solid #66ff00;
    color: #66ff00;
    text-decoration: none;
    border-radius: 8px;
}

.quick-actions a:hover {
    background: #66ff00;
    color: #000;
}

.trc4-footer {
    text-align: center;
    padding: 30px;
    margin-top: 40px;
    color: #555;
}

/*======================================================
TRC4 FOOTER
======================================================*/
.trc4-footer{
    margin-top:60px;
    border-top:1px solid #222;
    background:#050505;
    padding:40px 30px;
    border-radius:18px;
    box-shadow:
    0 0 25px rgba(102,255,0,.08),
    inset 0 0 30px rgba(102,255,0,.03);
}

.trc4-footer-logo{
    text-align:center;
    color:#66ff00;
    font-size:28px;
    font-weight:bold;
    text-shadow:
    0 0 10px #66ff00,
    0 0 25px #66ff00;
}

.trc4-footer-line{
    width:220px;
    height:2px;
    margin:18px auto 35px;
    background:#66ff00;
    box-shadow:0 0 12px #66ff00;
}

.trc4-footer-grid{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(180px,1fr));
    gap:20px;
}

.trc4-footer-grid div{
    background:#090909;
    border:1px solid #222;
    border-radius:12px;
    padding:18px;
    text-align:center;
    transition:.25s;
}

.trc4-footer-grid div:hover{
    border-color:#66ff00;
    box-shadow:
    0 0 15px rgba(102,255,0,.35);
}

.trc4-footer-grid .label{
    display:block;
    color:#888;
    font-size:12px;
    text-transform:uppercase;
    margin-bottom:8px;
    letter-spacing:1px;
}

.trc4-footer-grid strong{
    color:#66ff00;
    font-size:17px;
}

.status-online{
    color:#66ff00;
    font-weight:bold;
    text-shadow:0 0 10px #66ff00;
}

.trc4-footer-bottom{
    margin-top:35px;
    border-top:1px solid #1b1b1b;
    padding-top:20px;
    text-align:center;
    color:#777;
    font-size:13px;
}

.trc4-footer-bottom .dot{
    display:inline-block;
    width:6px;
    height:6px;
    margin:0 12px;
    border-radius:50%;
    background:#66ff00;
    box-shadow:0 0 10px #66ff00;
}
</style>
</head>

<body>
<!--
========================================================
 TRC4 MAIN CONTAINER
========================================================
-->
<div class="trc4-container">
<header class="trc4-header">
<div class="trc4-brand">
<div class="trc4-logo">TRC4</div>
<div>
<h1>Broadcast Diagnostics Center</h1>
<p>Server Monitoring System</p>
</div>
</div>

<div class="trc4-status">
<span class="led"></span>ONLINE</div>
</header>

<!--
========================================================
 TRC4 SERVER INFORMATION DASHBOARD CARDS
========================================================
-->
<div class="trc4-card">
<div class="trc4-card-title">
🖥 Server Information
</div>

<div class="trc4-row">

<span>
SERVER NAME
</span>

<strong>
<?=trc4_server('SERVER_NAME')?>
</strong>

</div>

<div class="trc4-row">

<span>
SERVER ADDRESS
</span>

<strong>
<?=trc4_server('SERVER_ADDR')?>:
<?=trc4_server('SERVER_PORT')?>
</strong>
</div>

<div class="trc4-row">

<span>
SERVER SOFTWARE
</span>

<strong>
<?=trc4_server('SERVER_SOFTWARE')?>
</strong>
</div>

<div class="trc4-row">

<span>
DOCUMENT ROOT
</span>

<strong>
<?=trc4_server('DOCUMENT_ROOT')?>
</strong>
</div>

<div class="trc4-row">

<span>
SERVER TIME
</span>

<strong>
<?= gmdate('Y-m-d H:i:s',time()+intval(TRC4_TIMEZONE)*3600)?>
</strong>
</div>
</div>

<!--
========================================================
 TRC4 PHP COMPONENT SUPPORT
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🧩 PHP Component Support
</div>

<div class="trc4-row">

<span>
MySQL / MySQLi
</span>
<strong>
<?= trc4_badge(extension_loaded('mysqli'))?>
</strong>
</div>

<div class="trc4-row">

<span>
PDO Database
</span>

<strong>
<?= trc4_badge(extension_loaded('pdo'))?>
</strong>
</div>

<div class="trc4-row">
<span>
GD Library
</span>
<strong>
<?= trc4_badge(function_exists('gd_info'))?>
</strong>
</div>

<div class="trc4-row">
<span>CURL</span>
<strong>
<?=trc4_badge(extension_loaded('curl'))?>
</strong>
</div>

<div class="trc4-row">
<span>
OpenSSL
</span>
<strong>
<?= trc4_badge(extension_loaded('openssl'))?>
</strong>
</div>

<div class="trc4-row">
<span>
JSON
</span>
<strong>
<?= trc4_badge(extension_loaded('json'))?>
</strong>
</div>

<div class="trc4-row">
<span>
MBSTRING
</span>
<strong>
<?= trc4_badge(extension_loaded('mbstring'))?>
</strong>
</div>

<div class="trc4-row">
<span>
ZIP
</span>
<strong>
<?= trc4_badge(extension_loaded('zip'))?>
</strong>
</div>

<div class="trc4-row">
<span>
FILEINFO
</span>
<strong>
<?= trc4_badge(extension_loaded('fileinfo'))?>
</strong>
</div>

<div class="trc4-row">
<span>
OPcache
</span>
<strong>
<?= trc4_badge(extension_loaded('Zend OPcache'))?>
</strong>
</div>

</div>

<!--
========================================================
 PANEL PËR PHP LIMITS
========================================================
-->
<div class="trc4-card">
<div class="trc4-card-title">
⚙ PHP Runtime Limits
</div>

<div class="trc4-row">
<span>
Memory Limit
</span>
<strong>
<?=ini_get('memory_limit')?>
</strong>
</div>



<div class="trc4-row">
<span>
Upload Max Size
</span>
<strong>
<?=ini_get('upload_max_filesize')?>
</strong>
</div>

<div class="trc4-row">
<span>
Post Max Size
</span>
<strong>
<?=ini_get('post_max_size')?>
</strong>
</div>

<div class="trc4-row">
<span>
Max Execution Time
</span>
<strong>
<?=ini_get('max_execution_time')?> sec
</strong>
</div>

</div>

<!--
========================================================
 PHP CORE INFORMATION
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🐘 PHP Core Information
</div>

<div class="trc4-row">
<span>
PHP VERSION
</span>
<strong>
<?= htmlspecialchars(PHP_VERSION,ENT_QUOTES,'UTF-8')?>
</strong>
</div>

<div class="trc4-row">
<span>
PHP SAPI
</span>
<strong>
<?=htmlspecialchars(PHP_SAPI,ENT_QUOTES,'UTF-8')?>
</strong>
</div>

<div class="trc4-row">
<span>
PHP.INI FILE
</span>
<strong>
<?=htmlspecialchars($trc4Info['php_ini'],ENT_QUOTES,'UTF-8')?>
</strong>
</div>

<div class="trc4-row">
<span>
TIMEZONE
</span>
<strong>
UTC <?=TRC4_TIMEZONE?>
</strong>
</div>

</div>

<!--
========================================================
 TRC4 PHP SECURITY CENTER
PHP Security Scanner
✅ Dangerous Functions Check
✅ Disabled Functions Monitor
✅ Display Errors Status
✅ Open Basedir Check
✅ Upload Security
✅ Debug Status
✅ Security Score Meter
✅ TRC4 Neon Warning Badges
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🛡 PHP Security Center
</div>

<div class="trc4-security-score">
<div>
Security Score
</div>
<strong>
<?=$securityScore?>%
</strong>

<div class="security-bar">
<span style="width:<?=$securityScore?>%"></span>
</div>
</div>

<div class="trc4-row">
<span>
Display Errors
</span>
<strong>
<?= trc4_badge(!$securityChecks['display_errors'])?>
</strong>
</div>

<div class="trc4-row">
<span>
Open Basedir
</span>
<strong>
<?= trc4_badge($securityChecks['open_basedir'])?>
</strong>
</div>

<div class="trc4-row">
<span>
File Uploads
</span>
<strong>
<?= trc4_badge($securityChecks['file_uploads'])?>
</strong>
</div>

<div class="trc4-row">
<span>
URL fopen
</span>
<strong>
<?= trc4_badge($securityChecks['allow_url_fopen'])?>
</strong>
</div>

<div class="trc4-row">
<span>
Dangerous Functions
</span>
<strong>
<?php if(count($dangerFound)==0): ?>
<span class="badge ok">
PROTECTED
</span>
<?php else: ?>
<span class="badge error">
<?=count($dangerFound)?>
 FOUND
</span>
<?php endif; ?>
</strong>
</div>

</div>

<!--
========================================================
 TRC4 PHP SECURITY CENTER SECURITY DETAILS CARD
PHP Security Scanner
✅ Dangerous Functions Check
✅ Disabled Functions Monitor
✅ Display Errors Status
✅ Open Basedir Check
✅ Upload Security
✅ Debug Status
✅ Security Score Meter
✅ TRC4 Neon Warning Badges
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
⚠ Security Details
</div>

<div class="trc4-row">
<span>
Disabled Functions
</span>
<strong>
<?php echo $disabledFunctions ? htmlspecialchars($disabledFunctions) : "NONE"; ?>
</strong>
</div>

<div class="trc4-row">
<span>
PHP User
</span>
<strong>
<?= get_current_user() ?>
</strong>
</div>

<div class="trc4-row">
<span>
Server User Agent
</span>
<strong>
<?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') ?>
</strong>
</div>

</div>

<!--
========================================================
TRC4 Broadcast Diagnostics Center
TRC4 NEON STORAGE ENGINE
TRC4 FILE SYSTEM MONITOR
Disk Space Monitor
✅ Free / Used Space
✅ Folder Permission Scanner
✅ Writable Check
✅ Cache Folder Status
✅ Log Folder Status
✅ Upload Folder Status
✅ TRC4 Neon Storage Cards
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
💾 File System Monitor
</div>

<div class="storage-box">
<div>
Disk Usage
</div>
<strong><?=$diskPercent?>%</strong>
<div class="security-bar">
<span style="width:<?=$diskPercent?>%"></span>
</div>
<p>Used: <?=trc4_size($diskUsed)?> / <?=trc4_size($diskTotal)?></p>
</div>

</div>

<!--
========================================================
TRC4 Broadcast Diagnostics Center / Folder Scanner Card
TRC4 NEON STORAGE ENGINE
TRC4 FILE SYSTEM MONITOR
Disk Space Monitor
✅ Free / Used Space
✅ Folder Permission Scanner
✅ Writable Check
✅ Cache Folder Status
✅ Log Folder Status
✅ Upload Folder Status
✅ TRC4 Neon Storage Cards
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
📁 Folder Permissions
</div>

<?php foreach($trc4Folders as $name=>$folder): ?>
<div class="trc4-row">
<span>
<?=strtoupper($name)?>
</span>
<strong>
<?php if(!$folder['exists']): ?>
<span class="badge error">
NOT FOUND
</span>
<?php elseif($folder['write']): ?>
<span class="badge ok">
WRITABLE
</span>
<?php else: ?>
<span class="badge error">
READ ONLY
</span>
<?php endif; ?>
</strong>
</div>

<?php endforeach; ?>
</div>

<!--
========================================================
TRC4 Broadcast Diagnostics Center / Extra Path Information
TRC4 NEON STORAGE ENGINE
TRC4 FILE SYSTEM MONITOR
Disk Space Monitor
✅ Free / Used Space
✅ Folder Permission Scanner
✅ Writable Check
✅ Cache Folder Status
✅ Log Folder Status
✅ Upload Folder Status
✅ TRC4 Neon Storage Cards
========================================================
-->
<div class="trc4-card">


<div class="trc4-card-title">

📂 System Paths

</div>




<div class="trc4-row">
<span>Current Directory</span>
<strong><?=htmlspecialchars(__DIR__)?></strong>
</div>

<div class="trc4-row">
<span>Temporary Directory</span>
<strong><?=htmlspecialchars(sys_get_temp_dir())?></strong>
</div>

<div class="trc4-row">
<span>PHP Upload Directory</span>
<strong><?=ini_get('upload_tmp_dir') ?: 'DEFAULT'?></strong>
</div>

</div>

<!-- ========================================================
TRC4 Broadcast Diagnostics Center
MYSQL CONNECTION TEST PANEL
TRC4 NEON DATABASE MONITOR
========================================================
-->

<?php
/*
========================================================
 TRC4 MYSQL TEST ENGINE
MySQL Test Card
✅ Neon Input Fields
✅ Secure POST Handling
✅ Connection Status
✅ Server Version
✅ Error Details
✅ TRC4 Modal Result
✅ No page reload
========================================================
*/

$mysqlResult = null;
if(isset($_POST['trc4_mysql_test'])
)

{
$host =$_POST['mysqlHost'] ?? '';
$user =$_POST['mysqlUser'] ?? '';
$pass =$_POST['mysqlPassword'] ?? '';
$db =$_POST['mysqlDb'] ?? '';

$link = @mysqli_connect($host,$user,$pass);

if($link)
{
$serverInfo =mysqli_get_server_info($link);
$dbStatus =mysqli_select_db($link,$db
);

$mysqlResult=[
"status"=>"success",
"message"=>"Connection Successful",
"server"=>$serverInfo,
"database"=>$dbStatus
];
mysqli_close($link);
}
else
{
$mysqlResult=[
"status"=>"error",
"message"=>"Connection Failed",
"error"=>mysqli_connect_error()
];
}
}
?>

<!--
========================================================
 TRC4 MYSQL CONNECTION TEST
========================================================
-->
<div class="trc4-card">
<div class="trc4-card-title">
🗄 MySQL Connection Test
</div>

<form method="post">

<div class="trc4-row">
<span>MYSQL HOST</span>
<strong>
<input name="mysqlHost" value="localhost">
</strong>
</div>

<div class="trc4-row">
<span>DATABASE</span>
<strong>
<input name="mysqlDb" value="trc4_broadcast">
</strong>
</div>

<div class="trc4-row">
<span>USERNAME</span>
<strong>
<input name="mysqlUser" value="root">
</strong>
</div>

<div class="trc4-row">
<span>PASSWORD</span>
<strong>
<input type="text" name="mysqlPassword" value="">
</strong>
</div>

<br>
<button class="trc4-btn" name="trc4_mysql_test" value="1">
⚡ TEST DATABASE
</button>
</form>

</div>

<!--
========================================================
 SQL Rezultat Modal
========================================================
-->

<?php if($mysqlResult): ?>

<div id="mysqlModal" style="display:flex" class="trc4-modal">
<div class="trc4-modal-box">
<h2>
<?php if($mysqlResult['status']=="success"): ?>
<span style="color:#66ff00">
✔ MYSQL ONLINE
</span>
<?php else: ?>
<span style="color:#ff3030">
✖ MYSQL ERROR
</span>
<?php endif; ?>
</h2>

<p><?=$mysqlResult['message']?></p>

<?php if(isset($mysqlResult['server'])): ?>

<p>Server: <?=$mysqlResult['server']?></p>

<?php endif; ?>

<?php if(isset($mysqlResult['error'])): ?>

<p style="color:#ff3030">

<?=$mysqlResult['error']?>
</p>
<?php endif; ?>

<button class="trc4-btn" onclick="closeMysqlModal()">
CLOSE
</button>
</div>
</div>
<?php endif; ?>

<!--
========================================================
 SECURITY STATUS
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🔐 TRC4 Security Status
</div>

<div class="trc4-row">
<span>Environment</span>
<strong>
<?php if($isLocal): ?>
<span class="badge ok">
LOCAL MODE
</span>
<?php else: ?>
<span class="badge error">
REMOTE MODE
</span>
<?php endif; ?>
</strong>
</div>

<div class="trc4-row">
<span>phpinfo Access</span>
<strong>
<?php if($isLocal): ?>
<span class="badge ok">
ALLOWED
</span>
<?php else: ?>
<span class="badge error">
BLOCKED
</span>
<?php endif; ?>
</strong>
</div>

<div class="trc4-row">
<span>Current Client IP</span>
<strong><?=trc4_server('REMOTE_ADDR')?></strong>
</div>

</div>

<!--
========================================================
 TRC4 NETWORK ENGINE
 NETWORK PANEL HTML
Local Server IP
✅ Remote Client IP
✅ Hostname
✅ DNS Resolver Check
✅ HTTP Response Test
✅ Public IP Detection
✅ Network Status Card
✅ TRC4 Neon Network Monitor
========================================================
========================================================
TRC4 Broadcast Diagnostics Center
NETWORK MONITOR
TRC4 NEON NETWORK ENGINE
========================================================
-->
<!--
========================================================
 TRC4 NETWORK MONITOR
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🌐 Network Monitor
</div>

<div class="trc4-row">
<span>SERVER IP</span>
<strong><?=$networkInfo['server_ip']?></strong>
</div>

<div class="trc4-row">
<span>CLIENT IP</span>
<strong><?=$networkInfo['client_ip']?></strong>
</div>

<div class="trc4-row">
<span>HOSTNAME</span>
<strong><?=htmlspecialchars($networkInfo['hostname'])?></strong>
</div>

<div class="trc4-row">
<span>PUBLIC IP</span>
<strong><?=$networkInfo['public_ip']?></strong>
</div>

</div>

<!--
========================================================
 TRC4 NETWORK ENGINE
 NETWORK PANEL HTML
Local Server IP
✅ Remote Client IP
✅ Hostname
✅ DNS Resolver Check
✅ HTTP Response Test
✅ Public IP Detection
✅ Network Status Card
✅ TRC4 Neon Network Monitor
========================================================
========================================================
TRC4 Broadcast Diagnostics Center
NETWORK MONITOR
TRC4 NEON NETWORK ENGINE
========================================================
-->
<!--
========================================================
 HTTP RESPONSE CARD
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
⚡ HTTP Response Test
</div>

<div class="network-status">
<?php if($networkInfo['http_test']['online']): ?>

<div class="badge ok">
ONLINE
</div>
<p>Response: <?=$networkInfo['http_test']['time']?> ms</p>
<?php else: ?>

<div class="badge error">
OFFLINE
</div>
<?php endif; ?>
</div>

</div>

<!--
========================================================
 TRC4 NETWORK ENGINE
 NETWORK PANEL HTML
Local Server IP
✅ Remote Client IP
✅ Hostname
✅ DNS Resolver Check
✅ HTTP Response Test
✅ Public IP Detection
✅ Network Status Card
✅ TRC4 Neon Network Monitor
========================================================
========================================================
TRC4 Broadcast Diagnostics Center
NETWORK MONITOR
TRC4 NEON NETWORK ENGINE
========================================================
-->
<!--
========================================================
 DNS CHECK PANEL
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
📡 DNS Resolver
</div>

<div class="trc4-row">
<span>DNS Lookup</span>

<strong>
<?php $dns = dns_get_record($_SERVER['SERVER_NAME'],DNS_A);
echo $dns ? "ACTIVE" : "FAILED"; ?>
</strong>
</div>

</div>

<!--
========================================================
TRC4 LOG & DEBUG CENTER LOG CENTER CARD

PHP Error Log
Last Errors
Debug Status
Log File Viewer
Clear Log Button
Neon Console Style

========================================================
TRC4 Broadcast Diagnostics Center
LOG & DEBUG CENTER
TRC4 NEON DEBUG CONSOLE
========================================================

✅ PHP Error Log Monitor
✅ Last Errors Viewer
✅ Debug Mode Status
✅ Log File Detection
✅ Log Size Monitor
✅ Clear Log Button
✅ TRC4 Neon Console View
-->

<!--
========================================================
 TRC4 LOG DEBUG ENGINE CENTER
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🖥 Log & Debug Center
</div>

<div class="trc4-row">
<span>DEBUG MODE</span>
<strong>
<?php if($debugMode): ?>
<span class="badge error">
ON
</span>
<?php else: ?>
<span class="badge ok">
OFF
</span>
<?php endif; ?>
</strong>
</div>

<div class="trc4-row">
<span>ERROR LOG</span>
<strong><?= $logExists ? "FOUND" : "NOT FOUND" ?></strong>
</div>

<div class="trc4-row">
<span>LOG SIZE</span>
<strong><?= trc4_format_size($logSize)?></strong>
</div>

</div>

<!--
========================================================
 TRC4 LOG DEBUG CENTER NEON LOG VIEWER
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
📜 Latest Errors
</div>

<div class="trc4-console">
<?php if(count($lastLogs)): ?>
<?php foreach($lastLogs as $log): ?>
<div>
<?=htmlspecialchars($log)?>
</div>
<?php endforeach; ?>
<?php else: ?>
<div>
No Errors Found ✔
</div>
<?php endif; ?>
</div>

</div>

<!--
========================================================
 TRC4 LOG DEBUG CENTER CLEAR LOG BUTTON
========================================================
-->
<div class="trc4-card">
<div class="trc4-card-title">
🧹 Log Actions
</div>

<form method="post">

<button class="trc4-btn danger" name="trc4_clear_log" onclick="return confirm('Clear PHP log?')">CLEAR LOG</button>
</form>
</div>

<!--
========================================================
 TRC4 FINAL SYSTEM ENGINE COMMAND CENTER HEADER
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
-->

<!----/>
<div class="trc4-header">

<!----/>
<div class="trc4-logo">

⚡ TRC4 Broadcast Diagnostics Center

</div>
<!---->

<!----/>
<div class="trc4-subtitle">

Universal PHP Server Monitor
</div>

</div>
<!---->


<!--
========================================================
 TRC4 FINAL SYSTEM ENGINE COMMAND SYSTEM HEALTH CARD
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
🚀 System Health
</div>

<div class="health-number">
<?=$systemHealth?>%
</div>

<div class="security-bar">
<span style="width:<?=$systemHealth?>%"></span>
</div>

<p class="center">
Modules Active: <?=$onlineModules?> / <?=$totalModules?>
</p>
</div>

<!--
========================================================
 TRC4 FINAL SYSTEM ENGINE COMMAND MODULE STATUS GRID
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
-->

<div class="trc4-grid">
<?php foreach($modules as $name=>$state): ?>

<div class="module-card">
<h3><?=$name?></h3>

<?= trc4_status_badge($state)?>
</div>
<?php endforeach; ?>
</div>

<!--
========================================================
 TRC4 FINAL SYSTEM ENGINE COMMAND QUICK ACTIONS
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
-->


<div class="trc4-card">
<div class="trc4-card-title">
⚙ Quick Actions
</div>

<div class="quick-actions">
<a href="?q=info">PHP INFO</a>
<a href="javascript:location.reload()">RELOAD</a>
<a href="javascript:window.print()">PRINT REPORT</a>
</div>

</div>

<!--
========================================================
 SERVER INFORMATION DASHBOARD ACTION BUTTONS
========================================================
-->

<div class="trc4-card">
<div class="trc4-card-title">
⚡ TRC4 Tools
</div>

<a class="trc4-btn" href="?act=phpinfo">PHP INFO</a>
<button class="trc4-btn" onclick="get_ip()">GET PUBLIC IP</button>
<div id="ip_r" style="margin-top:20px;color:#aaa;">Waiting IP Check...</div>
</div>

</div>

<!--
========================================================
TRC4 FINAL SYSTEM ENGINE COMMAND FINAL FOOTER
TRC4 SYSTEM OVERVIEW DASHBOARD
Total Health Score %
All modules status
Neon animated dashboard
Quick actions
TRC4 Broadcast Center Header
Final UI polish.

Ky është paneli final që lidh të gjitha modulet:

✅ Total System Health Score
✅ Server Status
✅ PHP Status
✅ Database Status
✅ Security Status
✅ Storage Status
✅ Network Status
✅ Neon Animated Header
✅ Quick Actions
✅ TRC4 Command Center

========================================================
TRC4 Broadcast Diagnostics Center
SYSTEM OVERVIEW DASHBOARD
TRC4 NEON COMMAND CENTER
========================================================
-->
<!----/>
<div class="trc4-footer">

TRC4 Broadcast UI Neon
<br>
System Diagnostics Engine
<br>

</?=date("Y-m-d H:i:s")?>
</div>
<!---->

<!-- ======================================================
TRC4 FOOTER
====================================================== -->
<footer class="trc4-footer">
    <div class="trc4-footer-logo">
        ⚡ TRC4 Broadcast Diagnostics Center UI Neon
    </div>

    <div class="trc4-footer-line"></div>

    <div class="trc4-footer-grid">

        <div>
            <span class="label">Version</span>
            <strong>v2.0</strong>
        </div>

        <div>
            <span class="label">PHP</span>
            <strong><?= htmlspecialchars(PHP_VERSION) ?></strong>
        </div>

        <div>
            <span class="label">Server Time</span>
            <strong><?= date('d-m-Y H:i:s') ?></strong>
        </div>

        <div>
            <span class="label">Status</span>
            <span class="status-online">
                ● SYSTEM ONLINE
            </span>
        </div>

    </div>

    <div class="trc4-footer-bottom">
    <div class="row" style="background-color: #000; color: #0F0;">
      &copy;  <?php echo date("Y") ?>  <a href="https://github.com/SxtBox" target="_blank"> TRC4</a>
        <span class="dot"></span>
        PHP: <?= htmlspecialchars(PHP_VERSION) ?>
        <span class="dot"></span>
        Universal Diagnostics Engine
    </div>
	 </div>
</footer>
<script>
/*
================================================
TRC4 MYSQL MODAL
================================================
*/
function closeMysqlModal()
{

const m =
document.getElementById(
"mysqlModal"
);

if(m)
{

m.style.display="none";
}

}
</script>

<script>
/*
========================================================
 TRC4 SERVER INFORMATION DASHBOARD
 CORE INFORMATION
 UPDATE SCRIPT AREA
 SECURITY STATUS
✅ Server Information Card
✅ PHP Information Card
✅ Localhost Security Badge
✅ TRC4 Neon rows
✅ Dynamic PHP values
✅ Safe output (htmlspecialchars)
========================================================
*/

function get_ip()
{

const box=document.getElementById("ip_r");

box.innerHTML="Checking...";

fetch("?act=getip")

.then(r=>r.text())

.then(ip=>{

if(ip==="false")
{

box.innerHTML="Failed to Get External IP";
return;
}

box.innerHTML=
`
Public IP: <a class="trc4-btn" target="_blank" href="http://${ip}">${ip}</a>
`;

})

.catch(()=>{

box.innerHTML="Connection Error";
});

}
</script>
</body>
</html>
