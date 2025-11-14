<?php
// auth.php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hard-config
define('REMEMBER_COOKIE_NAME', 'toolslib_rem');
define('REMEMBER_DAYS', 30);
define('REMEMBER_SELECTOR_BYTES', 6);
define('REMEMBER_TOKEN_BYTES', 32);

// Check login
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

// Log in
function login_user($user_id, $username, $regenerate = true) {
    if ($regenerate) session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['last_active'] = time();
}

// Log out
function logout_user() {
    global $mysqli;
    if (!empty($_COOKIE[REMEMBER_COOKIE_NAME])) {
        list($selector, $validator) = explode(':', $_COOKIE[REMEMBER_COOKIE_NAME]);
        $stmt = $mysqli->prepare("DELETE FROM remember_tokens WHERE selector = ?");
        $stmt->bind_param('s', $selector);
        $stmt->execute();
        setcookie(REMEMBER_COOKIE_NAME, '', time()-3600, '/', '', false, true);
    }
    $_SESSION = [];
    session_unset();
    session_destroy();
}

// Session timeout
function enforce_session_timeout($timeout_seconds = 1800) {
    if (!empty($_SESSION['last_active']) && (time() - $_SESSION['last_active']) > $timeout_seconds) {
        logout_user();
        header("Location: login.php?timeout=1");
        exit;
    }
    $_SESSION['last_active'] = time();
}

// Remember-me login
function try_remember_login() {
    global $mysqli;
    if (is_logged_in()) return;
    if (empty($_COOKIE[REMEMBER_COOKIE_NAME])) return;

    $cookie = $_COOKIE[REMEMBER_COOKIE_NAME];
    if (!strpos($cookie, ':')) return;

    list($selector, $validator) = explode(':', $cookie);

    if (!preg_match('/^[0-9a-f]{12}$/', $selector)) return;

    $stmt = $mysqli->prepare("SELECT user_id, token_hash, expires_at FROM remember_tokens WHERE selector = ?");
    $stmt->bind_param('s', $selector);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        setcookie(REMEMBER_COOKIE_NAME, '', time()-3600, '/', '', false, true);
        return;
    }

    $row = $res->fetch_assoc();
    if (new DateTime() > new DateTime($row['expires_at'])) {
        $stmt = $mysqli->prepare("DELETE FROM remember_tokens WHERE selector = ?");
        $stmt->bind_param('s', $selector);
        $stmt->execute();
        setcookie(REMEMBER_COOKIE_NAME, '', time()-3600, '/', '', false, true);
        return;
    }

    $calc_hash = hash('sha256', hex2bin($validator));
    if (hash_equals($row['token_hash'], $calc_hash)) {
        $uid = (int)$row['user_id'];
        $s2 = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
        $s2->bind_param('i', $uid);
        $s2->execute();
        $r2 = $s2->get_result();
        if ($r2->num_rows === 1) {
            $u = $r2->fetch_assoc();
            login_user($uid, $u['username']);
            $stmt = $mysqli->prepare("DELETE FROM remember_tokens WHERE selector = ?");
            $stmt->bind_param('s', $selector);
            $stmt->execute();
            create_remember_token($uid);
        }
    } else {
        $stmt = $mysqli->prepare("DELETE FROM remember_tokens WHERE selector = ?");
        $stmt->bind_param('s', $selector);
        $stmt->execute();
        setcookie(REMEMBER_COOKIE_NAME, '', time()-3600, '/', '', false, true);
    }
}

// Create remember token
function create_remember_token($user_id) {
    global $mysqli;
    $selector = bin2hex(random_bytes(REMEMBER_SELECTOR_BYTES));
    $token = bin2hex(random_bytes(REMEMBER_TOKEN_BYTES));
    $token_hash = hash('sha256', hex2bin($token));
    $expires_at = (new DateTime())->modify('+' . REMEMBER_DAYS . ' days')->format('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("INSERT INTO remember_tokens (user_id, selector, token_hash, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('isss', $user_id, $selector, $token_hash, $expires_at);
    $stmt->execute();

    setcookie(REMEMBER_COOKIE_NAME, $selector . ':' . $token, time() + REMEMBER_DAYS*86400, '/', '', false, true);
}
?>
