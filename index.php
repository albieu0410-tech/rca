<?php
/**
 * Minimal front controller that mimics the structure of the official kit.
 */

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

$ROOT = __DIR__;

require_once $ROOT . '/config.php';
require_once $ROOT . '/soap_helpers.php';

/**
 * Includes a PHP file if it exists.
 */
function include_if(string $file): bool
{
    if (is_file($file)) {
        include $file;
        return true;
    }

    return false;
}

$t = $_GET['t'] ?? 'home';
$slug = preg_replace('~[^a-z0-9_-]+~i', '', $t);

$adminPages = [
    'theme',
    'addpage',
    'addlink',
    'expirations',
    'medical',
    'contact',
    'agents',
    'petitions',
];

if (in_array($slug, $adminPages, true)) {
    if (empty($_SESSION['__sitepin_ok'])) {
        $pinFromConfig = $_CONFIG['sitepin'] ?? '';
        $submittedPin   = $_POST['pin'] ?? '';

        if ($pinFromConfig === '') {
            $_SESSION['__sitepin_ok'] = true;
        } elseif (hash_equals($pinFromConfig, $submittedPin)) {
            $_SESSION['__sitepin_ok'] = true;
        } else {
            include $ROOT . '/templates/pin-form.php';
            exit;
        }
    }

    foreach (["$ROOT/cache/t_$slug.php", "$ROOT/extensions/t_$slug.php"] as $candidate) {
        if (include_if($candidate)) {
            exit;
        }
    }

    http_response_code(404);
    echo "<h1>Missing admin handler</h1><p>No template for <code>$slug</code>.</p>";
    exit;
}

if ($slug === '' || $slug === 'home') {
    include $ROOT . '/cache/t_home.php';
    exit;
}

// Dedicated public handlers
$specialHandlers = [
    'tarife'       => "$ROOT/cache/t_tarife.php",
    'submitoferta' => "$ROOT/cache/t_submit_oferta.php",
];

if (isset($specialHandlers[$slug]) && include_if($specialHandlers[$slug])) {
    exit;
}

if ($slug !== '' && include_if("$ROOT/cache/t_$slug.php")) {
    exit;
}

if ($slug !== '' && include_if("$ROOT/extensions/t_$slug.php")) {
    exit;
}

http_response_code(404);
include $ROOT . '/templates/404.php';
