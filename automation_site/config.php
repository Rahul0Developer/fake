<?php
/**
 * Nikoji Technologies — shared security config.
 * Included by admin.php, contact.php, and newsletter.php.
 *
 * No database, no Composer, no external packages — everything here runs
 * on plain PHP 7.4+/8.x so it keeps working unmodified on cPanel shared
 * hosting.
 */

// ---------------------------------------------------------------------
// Secure session bootstrap. Must run before ANY output — call
// nikoji_start_session() before anything else in a page that uses $_SESSION.
// ---------------------------------------------------------------------
function nikoji_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
        || (($_SERVER['SERVER_PORT'] ?? '') == 443);

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    ini_set('session.use_strict_mode', '1');
    session_start();
}

// ---------------------------------------------------------------------
// Admin credentials — env vars first, falling back to the file blocked
// by data/.htaccess. Never hardcode credentials in a .php file that a
// repo or client handoff might ship.
// ---------------------------------------------------------------------
function nikoji_admin_credentials(): array
{
    $envUser = getenv('NIKOJI_ADMIN_USER');
    $envHash = getenv('NIKOJI_ADMIN_PASS_HASH');
    if ($envUser && $envHash) {
        return ['username' => $envUser, 'password_hash' => $envHash];
    }

    $file = __DIR__ . '/data/admin-config.php';
    if (is_file($file)) {
        $creds = require $file;
        if (is_array($creds) && !empty($creds['username']) && !empty($creds['password_hash'])) {
            return $creds;
        }
    }

    // No credentials configured anywhere — fail closed, not open.
    return ['username' => null, 'password_hash' => null];
}

// ---------------------------------------------------------------------
// CSRF tokens — one per session, verified on every state-changing POST.
// ---------------------------------------------------------------------
function nikoji_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function nikoji_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(nikoji_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function nikoji_csrf_valid(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ---------------------------------------------------------------------
// Brute-force throttling for admin login. Tracked in a flat file inside
// data/ (already blocked from web access) so it works across requests
// without a database. Keyed by IP, sliding 15-minute window.
// ---------------------------------------------------------------------
function nikoji_login_throttle_check(string $ip): array
{
    $file = __DIR__ . '/data/login_throttle.json';
    $windowSeconds = 15 * 60;
    $maxAttempts   = 5;

    $all = [];
    if (is_file($file)) {
        $all = json_decode((string) file_get_contents($file), true) ?: [];
    }

    $now = time();
    $attempts = array_filter($all[$ip] ?? [], fn($t) => ($now - $t) < $windowSeconds);

    $locked = count($attempts) >= $maxAttempts;
    $retryAfter = 0;
    if ($locked) {
        $oldest = min($attempts);
        $retryAfter = max(0, $windowSeconds - ($now - $oldest));
    }

    return ['locked' => $locked, 'retry_after' => $retryAfter, 'attempts' => $attempts, 'all' => $all, 'file' => $file];
}

function nikoji_login_throttle_record_failure(string $ip): void
{
    $state = nikoji_login_throttle_check($ip);
    $attempts = $state['attempts'];
    $attempts[] = time();
    $state['all'][$ip] = array_values($attempts);
    @file_put_contents($state['file'], json_encode($state['all']), LOCK_EX);
}

function nikoji_login_throttle_clear(string $ip): void
{
    $file = __DIR__ . '/data/login_throttle.json';
    if (!is_file($file)) {
        return;
    }
    $all = json_decode((string) file_get_contents($file), true) ?: [];
    unset($all[$ip]);
    @file_put_contents($file, json_encode($all), LOCK_EX);
}

// ---------------------------------------------------------------------
// Idle session timeout for the logged-in admin (30 minutes).
// ---------------------------------------------------------------------
function nikoji_enforce_admin_timeout(int $maxIdleSeconds = 1800): void
{
    if (empty($_SESSION['nikoji_admin'])) {
        return;
    }
    if (!empty($_SESSION['admin_time']) && (time() - $_SESSION['admin_time']) > $maxIdleSeconds) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        return;
    }
    $_SESSION['admin_time'] = time(); // sliding window: reset on activity
}

// ---------------------------------------------------------------------
// Safe filesystem writes — used by contact.php / newsletter.php / admin.php
// so a missing/unwritable data/ directory shows a clean message instead of
// a raw PHP warning (there's no database to fall back on).
// ---------------------------------------------------------------------
function nikoji_ensure_writable_dir(string $dir): bool
{
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return is_dir($dir) && is_writable($dir);
}

function nikoji_append_json(string $file, array $entry): bool
{
    $dir = dirname($file);
    if (!nikoji_ensure_writable_dir($dir)) {
        return false;
    }
    $fp = fopen($file, 'c+');
    if (!$fp) {
        return false;
    }
    $ok = true;
    if (flock($fp, LOCK_EX)) {
        $size = filesize($file);
        $existing = $size > 0 ? json_decode(fread($fp, $size), true) : [];
        if (!is_array($existing)) {
            $existing = [];
        }
        $existing[] = $entry;
        ftruncate($fp, 0);
        rewind($fp);
        $ok = fwrite($fp, json_encode($existing, JSON_PRETTY_PRINT)) !== false;
        fflush($fp);
        flock($fp, LOCK_UN);
    } else {
        $ok = false;
    }
    fclose($fp);
    return $ok;
}

function nikoji_append_csv(string $file, array $header, array $row): bool
{
    $dir = dirname($file);
    if (!nikoji_ensure_writable_dir($dir)) {
        return false;
    }
    $isNew = !file_exists($file);
    $fp = fopen($file, 'a');
    if (!$fp) {
        return false;
    }
    $ok = true;
    if (flock($fp, LOCK_EX)) {
        if ($isNew) {
            fputcsv($fp, $header);
        }
        $ok = fputcsv($fp, $row) !== false;
        flock($fp, LOCK_UN);
    } else {
        $ok = false;
    }
    fclose($fp);
    return $ok;
}
