<?php
// admin.php — Nikoji Technologies Admin Panel
require_once __DIR__ . '/config.php';
nikoji_start_session();

define('DATA_DIR',       __DIR__ . '/data/');
define('UPLOADS_PDFS',   __DIR__ . '/assets/uploads/pdfs/');
define('UPLOADS_IMAGES', __DIR__ . '/assets/uploads/images/');

// Ensure directories exist (fail gracefully — no fatal warnings if the
// host account can't create them; the page still renders with a message)
$dirsOk = true;
foreach ([DATA_DIR, UPLOADS_PDFS, UPLOADS_IMAGES] as $d) {
  if (!nikoji_ensure_writable_dir($d)) $dirsOk = false;
}

$error   = '';
$success = '';
$clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// ── Handle CSV download early, before any HTML is echoed ──
// (Previously this ran at the bottom of the file, after the page had
// already started printing HTML, so header() silently failed. Moved here.)
if (!empty($_SESSION['nikoji_admin']) && isset($_GET['download'])) {
  nikoji_enforce_admin_timeout();
  if (!empty($_SESSION['nikoji_admin'])) {
    $allowed = ['contacts.csv', 'newsletter.csv'];
    $fname   = basename($_GET['download']);
    $path    = DATA_DIR . $fname;
    if (in_array($fname, $allowed, true) && is_file($path)) {
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $fname . '"');
      header('X-Content-Type-Options: nosniff');
      readfile($path);
      exit;
    }
  }
}

// ── Login ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
  $throttle = nikoji_login_throttle_check($clientIp);
  if ($throttle['locked']) {
    $mins = (int) ceil($throttle['retry_after'] / 60);
    $error = "Too many failed attempts. Try again in about {$mins} minute(s).";
  } elseif (!nikoji_csrf_valid($_POST['csrf_token'] ?? null)) {
    $error = 'Your session expired. Please try logging in again.';
  } else {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    $creds = nikoji_admin_credentials();
    if ($creds['username'] !== null && hash_equals($creds['username'], $u) && password_verify($p, $creds['password_hash'])) {
      session_regenerate_id(true);
      $_SESSION['nikoji_admin'] = true;
      $_SESSION['admin_time']   = time();
      nikoji_login_throttle_clear($clientIp);
      header('Location: admin.php');
      exit;
    }
    nikoji_login_throttle_record_failure($clientIp);
    $error = 'Invalid credentials. Access denied.';
  }
}

// ── Logout ──
if (isset($_GET['logout'])) {
  $_SESSION = [];
  session_destroy();
  header('Location: index.php');
  exit;
}

// ── Auth check (with idle timeout) ──
nikoji_enforce_admin_timeout();
$loggedIn = !empty($_SESSION['nikoji_admin']);

// ── File Upload ──
if ($loggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if (in_array($action, ['upload_pdf', 'upload_image', 'delete_file'], true) && !nikoji_csrf_valid($_POST['csrf_token'] ?? null)) {
    $error = 'Your session expired. Please refresh the page and try again.';
  } elseif (!$dirsOk && in_array($action, ['upload_pdf', 'upload_image'], true)) {
    $error = 'Upload storage is not writable on the server right now. Please check folder permissions for assets/uploads/.';
  } else {

  if ($action === 'upload_pdf' && isset($_FILES['pdf'])) {
    $file = $_FILES['pdf'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
    $mime  = ($finfo && is_uploaded_file($file['tmp_name'])) ? finfo_file($finfo, $file['tmp_name']) : '';
    if ($finfo) finfo_close($finfo);

    if ($file['error'] !== UPLOAD_ERR_OK) { $error = 'Upload failed. Please try again.'; }
    elseif ($ext !== 'pdf' || $mime !== 'application/pdf') { $error = 'Only genuine PDF files are allowed.'; }
    elseif ($file['size'] > 10 * 1024 * 1024) { $error = 'File too large (max 10MB).'; }
    else {
      $name = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
      $dest = UPLOADS_PDFS . time() . '_' . bin2hex(random_bytes(4)) . '_' . substr($name, 0, 60) . '.pdf';
      if (move_uploaded_file($file['tmp_name'], $dest)) { chmod($dest, 0644); $success = 'PDF uploaded successfully.'; }
      else { $error = 'Upload failed. Check that assets/uploads/pdfs/ is writable.'; }
    }
  }

  if ($action === 'upload_image' && isset($_FILES['image'])) {
    $file     = $_FILES['image'];
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExt  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
    $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
    $mime  = ($finfo && is_uploaded_file($file['tmp_name'])) ? finfo_file($finfo, $file['tmp_name']) : '';
    if ($finfo) finfo_close($finfo);

    if ($file['error'] !== UPLOAD_ERR_OK) { $error = 'Upload failed. Please try again.'; }
    elseif (!isset($allowedExt[$ext]) || $mime !== $allowedExt[$ext]) { $error = 'Only genuine JPG, PNG, or WebP images are allowed.'; }
    elseif ($file['size'] > 8 * 1024 * 1024) { $error = 'File too large (max 8MB).'; }
    else {
      $name = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
      $dest = UPLOADS_IMAGES . time() . '_' . bin2hex(random_bytes(4)) . '_' . substr($name, 0, 60) . '.' . $ext;
      if (move_uploaded_file($file['tmp_name'], $dest)) { chmod($dest, 0644); $success = 'Image uploaded successfully.'; }
      else { $error = 'Upload failed. Check that assets/uploads/images/ is writable.'; }
    }
  }

  if ($action === 'delete_file') {
    $f    = basename($_POST['file'] ?? '');
    $type = $_POST['type'] ?? '';
    $dir  = $type === 'pdf' ? UPLOADS_PDFS : UPLOADS_IMAGES;
    $path = $dir . $f;
    $real = realpath($path);
    $realDir = realpath($dir);
    if ($real && $realDir && strpos($real, $realDir) === 0 && is_file($real)) {
      unlink($real);
      $success = 'File deleted.';
    } else {
      $error = 'File not found.';
    }
  }

  }
}

// ── Load Data ──
$contacts    = [];
$newsletter  = [];
$pdfs        = [];
$images      = [];
$dataLoadError = '';

if ($loggedIn) {
  $cf = DATA_DIR . 'contacts.json';
  $nf = DATA_DIR . 'newsletter.json';
  if (is_file($cf)) {
    $raw = file_get_contents($cf);
    $decoded = $raw !== false ? json_decode($raw, true) : null;
    if ($raw !== false && $raw !== '' && $decoded === null) $dataLoadError = 'Enquiries data file could not be read (it may be corrupted).';
    $contacts = is_array($decoded) ? $decoded : [];
  }
  if (is_file($nf)) {
    $raw = file_get_contents($nf);
    $decoded = $raw !== false ? json_decode($raw, true) : null;
    $newsletter = is_array($decoded) ? $decoded : [];
  }
  $contacts   = array_reverse($contacts);
  $newsletter = array_reverse($newsletter);

  foreach (glob(UPLOADS_PDFS   . '*.pdf') as $f) $pdfs[]   = basename($f);
  foreach (glob(UPLOADS_IMAGES . '*.{jpg,jpeg,png,webp}', GLOB_BRACE) as $f) $images[] = basename($f);
}

$tab = $_GET['tab'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel — Nikoji Technologies</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --green:#006B4F; --green-dark:#004d38; --gold:#FFD700;
      --bg:#f4f7f5; --sidebar:#061410; --card:#fff;
      --text:#1a1a1a; --text-2:#555; --border:#e2ebe7;
      --radius:10px; --shadow:0 2px 12px rgba(0,107,79,0.08);
    }
    [data-theme="dark"] {
      --bg:#0d1f18; --card:#122a1f; --text:#e8f0ec; --text-2:#9ab5a5; --border:#1e3828;
    }
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;}
    /* Sidebar */
    .sidebar{width:240px;flex-shrink:0;background:var(--sidebar);display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;z-index:100;}
    .sidebar-brand{padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,0.06);}
    .sidebar-brand img{height:36px;margin-bottom:12px;}
    .sidebar-brand h2{font-family:'Poppins',sans-serif;font-size:0.85rem;font-weight:700;color:#fff;letter-spacing:0.04em;}
    .sidebar-brand p{font-size:0.7rem;color:rgba(255,255,255,0.3);margin-top:2px;}
    .sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto;}
    .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;font-size:0.85rem;font-weight:500;color:rgba(255,255,255,0.55);transition:all 0.2s;margin-bottom:2px;text-decoration:none;}
    .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(255,255,255,0.08);color:#fff;}
    .sidebar-nav a.active{background:rgba(0,107,79,0.35);color:var(--gold);}
    .sidebar-nav span.badge{margin-left:auto;background:var(--gold);color:#1a1a1a;font-size:0.65rem;font-weight:700;padding:2px 7px;border-radius:10px;}
    .sidebar-footer{padding:16px 12px;border-top:1px solid rgba(255,255,255,0.06);}
    .sidebar-footer a{display:flex;align-items:center;gap:8px;font-size:0.8rem;color:rgba(255,255,255,0.35);text-decoration:none;padding:8px 12px;border-radius:8px;transition:all 0.2s;}
    .sidebar-footer a:hover{color:#ff6666;background:rgba(255,100,100,0.08);}
    /* Main */
    .main{margin-left:240px;flex:1;min-height:100vh;}
    .topbar{background:var(--card);border-bottom:1px solid var(--border);padding:0 32px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;}
    .topbar h1{font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:var(--text);}
    .topbar-right{display:flex;align-items:center;gap:16px;}
    .admin-badge{background:rgba(0,107,79,0.1);color:var(--green);font-size:0.75rem;font-weight:700;padding:5px 12px;border-radius:20px;border:1px solid rgba(0,107,79,0.2);}
    .content{padding:32px;}
    /* Cards */
    .stat-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px;}
    .stat-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;display:flex;align-items:flex-start;gap:16px;}
    .stat-card-icon{width:46px;height:46px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
    .stat-card-icon.green{background:rgba(0,107,79,0.1);color:var(--green);}
    .stat-card-icon.gold{background:rgba(255,215,0,0.15);color:#9a7e00;}
    .stat-card h3{font-size:1.6rem;font-family:'Poppins',sans-serif;font-weight:700;color:var(--text);line-height:1;}
    .stat-card p{font-size:0.78rem;color:var(--text-2);margin-top:4px;}
    /* Table */
    .section-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:24px;}
    .section-card-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
    .section-card-header h3{font-family:'Poppins',sans-serif;font-size:0.95rem;font-weight:700;color:var(--text);}
    table{width:100%;border-collapse:collapse;}
    th{text-align:left;padding:12px 24px;font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-2);background:rgba(0,107,79,0.03);border-bottom:1px solid var(--border);}
    td{padding:14px 24px;font-size:0.85rem;color:var(--text);border-bottom:1px solid var(--border);}
    tr:last-child td{border-bottom:none;}
    tr:hover td{background:rgba(0,107,79,0.02);}
    .tag-green{background:rgba(0,107,79,0.1);color:var(--green);padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;}
    /* Upload */
    .upload-zone{border:2px dashed var(--border);border-radius:var(--radius);padding:32px;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--bg);}
    .upload-zone:hover{border-color:var(--green);background:rgba(0,107,79,0.03);}
    .upload-zone p{font-size:0.85rem;color:var(--text-2);margin-top:8px;}
    /* Alerts */
    .alert{padding:14px 18px;border-radius:var(--radius);margin-bottom:20px;font-size:0.875rem;display:flex;align-items:center;gap:10px;}
    .alert-success{background:rgba(0,107,79,0.08);border:1px solid rgba(0,107,79,0.25);color:var(--green);}
    .alert-error{background:rgba(220,50,50,0.08);border:1px solid rgba(220,50,50,0.2);color:#cc2222;}
    /* Btn */
    .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;font-size:0.82rem;font-weight:600;border:none;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-primary{background:var(--green);color:#fff;}.btn-primary:hover{background:var(--green-dark);}
    .btn-danger{background:rgba(220,50,50,0.1);color:#cc2222;border:1px solid rgba(220,50,50,0.2);}
    .btn-danger:hover{background:#cc2222;color:#fff;}
    .btn-sm{padding:6px 12px;font-size:0.75rem;}
    .files-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;padding:24px;}
    .file-card{background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center;}
    .file-card img{width:100%;height:100px;object-fit:cover;border-radius:6px;margin-bottom:10px;}
    .file-card p{font-size:0.72rem;color:var(--text-2);word-break:break-all;margin-bottom:10px;}
    /* Login */
    .login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);}
    .login-box{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:44px;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(0,107,79,0.1);}
    .login-box img{height:52px;margin:0 auto 20px;}
    .login-box h2{font-family:'Poppins',sans-serif;font-size:1.3rem;font-weight:700;text-align:center;margin-bottom:6px;color:var(--text);}
    .login-box p{text-align:center;font-size:0.85rem;color:var(--text-2);margin-bottom:28px;}
    .form-group{margin-bottom:16px;}
    .form-group label{display:block;font-size:0.8rem;font-weight:700;color:var(--text-2);margin-bottom:8px;}
    .form-group input{width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:0.9rem;color:var(--text);background:var(--bg);outline:none;}
    .form-group input:focus{border-color:var(--green);}
    @media(max-width:900px){
      .sidebar{display:none;} .main{margin-left:0;}
      .stat-cards{grid-template-columns:1fr 1fr;}
    }
    @media(max-width:500px){ .stat-cards{grid-template-columns:1fr;} }
  </style>
</head>
<body data-theme="light">

<?php if (!$loggedIn): ?>
<!-- ── Login Page ── -->
<div class="login-wrap">
  <div class="login-box">
    <img src="assets/images/logo.jpg" alt="Nikoji" style="display:block">
    <h2>Admin Access</h2>
    <p>Secure login for authorized personnel only.</p>
    <?php if ($error): ?>
      <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="action" value="login">
      <?= nikoji_csrf_field() ?>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required autocomplete="username" placeholder="Enter username">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required autocomplete="current-password" placeholder="Enter password">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px">Login to Dashboard →</button>
    </form>
    <p style="text-align:center;margin-top:20px;font-size:0.78rem;color:var(--text-2)"><a href="index.php" style="color:var(--green)">← Back to website</a></p>
  </div>
</div>

<?php else: ?>
<!-- ── Admin Dashboard ── -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <img src="assets/images/logo.jpg" alt="Nikoji">
    <h2>Nikoji Admin</h2>
    <p>Control Panel v1.0</p>
  </div>
  <nav class="sidebar-nav">
    <a href="admin.php?tab=dashboard" class="<?= $tab==='dashboard'?'active':'' ?>">
      📊 Dashboard
    </a>
    <a href="admin.php?tab=contacts" class="<?= $tab==='contacts'?'active':'' ?>">
      📬 Enquiries
      <?php if(count($contacts)): ?><span class="badge"><?= count($contacts) ?></span><?php endif; ?>
    </a>
    <a href="admin.php?tab=newsletter" class="<?= $tab==='newsletter'?'active':'' ?>">
      📧 Newsletter
      <?php if(count($newsletter)): ?><span class="badge"><?= count($newsletter) ?></span><?php endif; ?>
    </a>
    <a href="admin.php?tab=pdfs" class="<?= $tab==='pdfs'?'active':'' ?>">📄 PDFs</a>
    <a href="admin.php?tab=images" class="<?= $tab==='images'?'active':'' ?>">🖼 Images</a>
    <a href="admin.php?tab=downloads" class="<?= $tab==='downloads'?'active':'' ?>">💾 Download CSV</a>
  </nav>
  <div class="sidebar-footer">
    <a href="index.php" target="_blank">🌐 View Website</a>
    <a href="admin.php?logout=1" style="margin-top:4px">🚪 Logout</a>
  </div>
</aside>

<main class="main">
  <div class="topbar">
    <h1>
      <?php
        $titles = ['dashboard'=>'Dashboard','contacts'=>'Enquiries','newsletter'=>'Newsletter Subscribers','pdfs'=>'PDF Manager','images'=>'Image Manager','downloads'=>'Download Reports'];
        echo $titles[$tab] ?? 'Dashboard';
      ?>
    </h1>
    <div class="topbar-right">
      <span class="admin-badge">● Admin</span>
      <a href="admin.php?logout=1" style="font-size:0.82rem;color:var(--text-2);text-decoration:none">Logout</a>
    </div>
  </div>

  <div class="content">
    <?php if ($success): ?>
      <div class="alert alert-success">✓ <?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($dataLoadError): ?>
      <div class="alert alert-error">✕ <?= htmlspecialchars($dataLoadError) ?></div>
    <?php endif; ?>
    <?php if (!$dirsOk): ?>
      <div class="alert alert-error">✕ One or more storage folders (data/ or assets/uploads/) could not be created or are not writable. Uploads and CSV export will not work until permissions are fixed.</div>
    <?php endif; ?>

    <!-- DASHBOARD -->
    <?php if ($tab === 'dashboard'): ?>
    <div class="stat-cards">
      <div class="stat-card">
        <div class="stat-card-icon green">📬</div>
        <div><h3><?= count($contacts) ?></h3><p>Total Enquiries</p></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon gold">📧</div>
        <div><h3><?= count($newsletter) ?></h3><p>Newsletter Subscribers</p></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon green">📄</div>
        <div><h3><?= count($pdfs) ?></h3><p>Uploaded PDFs</p></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon gold">🖼</div>
        <div><h3><?= count($images) ?></h3><p>Uploaded Images</p></div>
      </div>
    </div>

    <!-- Recent contacts -->
    <div class="section-card">
      <div class="section-card-header">
        <h3>Recent Enquiries</h3>
        <a href="admin.php?tab=contacts" style="font-size:0.82rem;color:var(--green)">View all →</a>
      </div>
      <?php if (!$contacts): ?>
        <p style="padding:32px;text-align:center;color:var(--text-2);font-size:0.875rem">No enquiries yet.</p>
      <?php else: ?>
      <table>
        <thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Service</th></tr></thead>
        <tbody>
        <?php foreach(array_slice($contacts,0,5) as $c): ?>
          <tr>
            <td><?= htmlspecialchars(substr($c['date'],0,10)) ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><span class="tag-green"><?= htmlspecialchars($c['service'] ?: 'General') ?></span></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

    <!-- END DASHBOARD -->

    <!-- CONTACTS -->
    <?php elseif ($tab === 'contacts'): ?>
    <div class="section-card">
      <div class="section-card-header">
        <h3>All Enquiries (<?= count($contacts) ?>)</h3>
        <a href="admin.php?tab=downloads" class="btn btn-primary btn-sm">Download CSV</a>
      </div>
      <?php if (!$contacts): ?>
        <p style="padding:32px;text-align:center;color:var(--text-2)">No enquiries received yet.</p>
      <?php else: ?>
      <div style="overflow-x:auto">
      <table>
        <thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Phone</th><th>Service</th><th>Message</th></tr></thead>
        <tbody>
        <?php foreach($contacts as $c): ?>
          <tr>
            <td style="white-space:nowrap"><?= htmlspecialchars($c['date']) ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>" style="color:var(--green)"><?= htmlspecialchars($c['email']) ?></a></td>
            <td><?= htmlspecialchars($c['phone'] ?: '—') ?></td>
            <td><span class="tag-green"><?= htmlspecialchars($c['service'] ?: 'General') ?></span></td>
            <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($c['message']) ?>"><?= htmlspecialchars(substr($c['message'],0,80)) . (strlen($c['message'])>80?'…':'') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <?php endif; ?>
    </div>

    <!-- NEWSLETTER -->
    <?php elseif ($tab === 'newsletter'): ?>
    <div class="section-card">
      <div class="section-card-header">
        <h3>Newsletter Subscribers (<?= count($newsletter) ?>)</h3>
        <a href="admin.php?tab=downloads" class="btn btn-primary btn-sm">Download CSV</a>
      </div>
      <?php if (!$newsletter): ?>
        <p style="padding:32px;text-align:center;color:var(--text-2)">No subscribers yet.</p>
      <?php else: ?>
      <table>
        <thead><tr><th>Date</th><th>Name</th><th>Email</th></tr></thead>
        <tbody>
        <?php foreach($newsletter as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['date']) ?></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($s['email']) ?>" style="color:var(--green)"><?= htmlspecialchars($s['email']) ?></a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

    <!-- PDFs -->
    <?php elseif ($tab === 'pdfs'): ?>
    <div class="section-card" style="margin-bottom:24px">
      <div class="section-card-header"><h3>Upload PDF</h3></div>
      <div style="padding:24px">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="upload_pdf">
          <?= nikoji_csrf_field() ?>
          <input type="file" name="pdf" accept=".pdf,application/pdf" required style="display:block;margin-bottom:16px;font-size:0.875rem">
          <button type="submit" class="btn btn-primary">Upload PDF</button>
        </form>
      </div>
    </div>
    <div class="section-card">
      <div class="section-card-header"><h3>Uploaded PDFs (<?= count($pdfs) ?>)</h3></div>
      <?php if (!$pdfs): ?>
        <p style="padding:32px;text-align:center;color:var(--text-2)">No PDFs uploaded yet.</p>
      <?php else: ?>
      <table>
        <thead><tr><th>Filename</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($pdfs as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f) ?></td>
            <td style="display:flex;gap:8px;align-items:center">
              <a href="assets/uploads/pdfs/<?= urlencode($f) ?>" target="_blank" class="btn btn-primary btn-sm">View</a>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this file?')">
                <input type="hidden" name="action" value="delete_file">
                <?= nikoji_csrf_field() ?>
                <input type="hidden" name="type" value="pdf">
                <input type="hidden" name="file" value="<?= htmlspecialchars($f) ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

    <!-- IMAGES -->
    <?php elseif ($tab === 'images'): ?>
    <div class="section-card" style="margin-bottom:24px">
      <div class="section-card-header"><h3>Upload Image</h3></div>
      <div style="padding:24px">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="upload_image">
          <?= nikoji_csrf_field() ?>
          <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required style="display:block;margin-bottom:16px;font-size:0.875rem">
          <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
      </div>
    </div>
    <div class="section-card">
      <div class="section-card-header"><h3>Uploaded Images (<?= count($images) ?>)</h3></div>
      <?php if (!$images): ?>
        <p style="padding:32px;text-align:center;color:var(--text-2)">No images uploaded yet.</p>
      <?php else: ?>
      <div class="files-grid">
        <?php foreach($images as $f): ?>
        <div class="file-card">
          <img src="assets/uploads/images/<?= htmlspecialchars($f) ?>" alt="<?= htmlspecialchars(pathinfo($f, PATHINFO_FILENAME)) ?>">
          <p><?= htmlspecialchars($f) ?></p>
          <form method="POST" onsubmit="return confirm('Delete this image?')">
            <input type="hidden" name="action" value="delete_file">
            <?= nikoji_csrf_field() ?>
            <input type="hidden" name="type" value="image">
            <input type="hidden" name="file" value="<?= htmlspecialchars($f) ?>">
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- DOWNLOADS -->
    <?php elseif ($tab === 'downloads'): ?>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px">
      <?php
        $csvFiles = [
          'contacts.csv'   => ['Enquiries CSV', 'Download all contact form submissions as a CSV file.'],
          'newsletter.csv' => ['Newsletter Subscribers CSV', 'Download all newsletter subscriber emails as a CSV file.'],
        ];
        foreach($csvFiles as $fname => [$label,$desc]):
          $path = DATA_DIR . $fname;
      ?>
      <div class="section-card">
        <div class="section-card-header"><h3><?= $label ?></h3></div>
        <div style="padding:24px">
          <p style="font-size:0.85rem;color:var(--text-2);margin-bottom:16px"><?= $desc ?></p>
          <?php if(file_exists($path)): ?>
            <a href="admin.php?download=<?= urlencode($fname) ?>" class="btn btn-primary">Download <?= $label ?></a>
            <p style="font-size:0.75rem;color:var(--text-2);margin-top:12px">Last modified: <?= date('d M Y, H:i', filemtime($path)) ?></p>
          <?php else: ?>
            <p style="font-size:0.85rem;color:var(--text-2)">No data available yet.</p>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</main>
<?php endif; ?>

</body>
</html>
