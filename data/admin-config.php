<?php
/**
 * Nikoji Technologies — admin login credentials.
 *
 * This file lives inside data/, which data/.htaccess blocks from direct
 * web access (Apache "Require all denied"). It is only ever loaded
 * server-side via PHP's require, never requested over HTTP.
 *
 * IMPORTANT: The password below is a bcrypt HASH of the site's original
 * password (nikojitech@2025), kept only so the admin panel keeps working
 * without interruption. That original password was previously printed in
 * plain text in README.md, so treat it as already compromised — change it
 * the first time you deploy this update. To set a new one:
 *
 *   1. Run this once (PHP CLI, or a throwaway local script):
 *        <?php echo password_hash('your-new-password', PASSWORD_DEFAULT);
 *   2. Paste the output below as the new 'password_hash' value.
 *   3. Delete this comment's reference to the old password once changed.
 *
 * Environment variables NIKOJI_ADMIN_USER / NIKOJI_ADMIN_PASS_HASH, if set
 * on the server, override the values below (see config.php) — useful if
 * your hosting lets you set env vars outside the webroot entirely.
 */
return [
    'username'      => 'nikojitech_admin',
    'password_hash' => '$2b$10$CKH7U72sWtBZYP/ssB7DVOjBgokUmxedcAYmIvvrTDcV.WX8bP2bu',
];
