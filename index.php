<?php
// index.php — minimal front controller for the kit
error_reporting(E_ALL); ini_set('display_errors', 1);
session_start();

$ROOT = __DIR__;
require $ROOT.'/config.php';

function include_if($f){ if(is_file($f)){ include $f; return true; } return false; }

// ROUTING
$t = $_GET['t'] ?? '';
$admin = ['theme','addpage','addlink','expirations','medical','contact','agents','petitions'];

if (in_array($t, $admin, true)) {
    // ask for PIN
    if (empty($_SESSION['__sitepin_ok'])) {
        if (!empty($_POST['pin']) && isset($_CONFIG['sitepin']) && $_POST['pin'] === $_CONFIG['sitepin']) {
            $_SESSION['__sitepin_ok'] = true;
        } else {
            echo '<!doctype html><meta charset="utf-8"><title>PIN</title>
                  <h2>Administrator access</h2>
                  <form method="post"><input type="password" name="pin" placeholder="PIN">
                  <button>Login</button></form>';
            exit;
        }
    }
    // hand off to cache/ then extensions/
    $candidates = [
        "$ROOT/cache/t_$t.php",
        "$ROOT/extensions/t_$t.php"
    ];
    foreach ($candidates as $f) if (include_if($f)) exit;

    http_response_code(404);
    echo "<h1>$t</h1><p>No handler in <code>cache/</code> or <code>extensions/</code>.</p>";
    exit;
}

// PUBLIC: default page
if ($t === '' || $t === 'home') {
    $kiturl = rtrim($_CONFIG['kiturl'] ?? '/', '/') . '/';
    echo "<!doctype html><meta charset='utf-8'><title>ariguram</title>
          <h1>Kit front page</h1>
          <ul>
            <li><a href='index.php?t=theme'>Theme (admin)</a></li>
            <li><a href='index.php?t=addpage'>Add Page (admin)</a></li>
            <li><a href='index.php?t=addlink'>Add Link (admin)</a></li>
          </ul>";
    exit;
}

// catch-all: try an override by slug (cache/t_{slug}.php)
$slug = preg_replace('~[^a-z0-9_-]+~i','', $t);
if ($slug && include_if("$ROOT/cache/t_$slug.php")) exit;

// 404
http_response_code(404);
echo "<!doctype html><meta charset='utf-8'><title>404</title><h1>Not Found</h1>";
