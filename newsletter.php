<?php
// newsletter.php
require_once __DIR__ . '/config.php';
nikoji_start_session();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success'=>false,'message'=>'Invalid request.']);
  exit;
}

if (!nikoji_csrf_valid($_POST['csrf_token'] ?? null)) {
  echo json_encode(['success'=>false,'message'=>'Your session expired. Please refresh the page and try again.']);
  exit;
}

// Simple per-session rate limit: one subscribe attempt per 5 seconds,
// to slow down scripted abuse without needing a database.
if (!empty($_SESSION['nl_last_attempt']) && (microtime(true) - $_SESSION['nl_last_attempt']) < 5) {
  echo json_encode(['success'=>false,'message'=>'Please wait a moment before trying again.']);
  exit;
}
$_SESSION['nl_last_attempt'] = microtime(true);

$name  = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
  echo json_encode(['success'=>false,'message'=>'Please enter a valid email address.']);
  exit;
}
if (!$name) {
  echo json_encode(['success'=>false,'message'=>'Please enter your name.']);
  exit;
}

$file = __DIR__ . '/data/newsletter.json';
if (!nikoji_ensure_writable_dir(dirname($file))) {
  echo json_encode(['success'=>false,'message'=>"Sorry, we couldn't save your subscription right now. Please try again shortly."]);
  exit;
}

$subscribers = [];
if (is_file($file)) {
  $decoded = json_decode((string) file_get_contents($file), true);
  $subscribers = is_array($decoded) ? $decoded : [];
}

// Duplicate check
foreach ($subscribers as $s) {
  if (strtolower($s['email'] ?? '') === strtolower($email)) {
    echo json_encode(['success'=>false,'message'=>'You are already subscribed. Thank you!']);
    exit;
  }
}

$entry = [
  'id'    => uniqid(),
  'date'  => date('Y-m-d H:i:s'),
  'name'  => $name,
  'email' => $email,
  'ip'    => $_SERVER['REMOTE_ADDR'] ?? '',
];

$wroteJson = nikoji_append_json($file, $entry);
$wroteCsv  = nikoji_append_csv(
  __DIR__ . '/data/newsletter.csv',
  ['ID', 'Date', 'Name', 'Email', 'IP'],
  array_values($entry)
);

if (!$wroteJson && !$wroteCsv) {
  echo json_encode(['success'=>false,'message'=>"Sorry, we couldn't save your subscription right now. Please try again shortly."]);
  exit;
}

echo json_encode(['success'=>true,'message'=>'Subscribed successfully! Welcome aboard.']);
