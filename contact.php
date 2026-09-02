<?php
// contact.php
require_once __DIR__ . '/config.php';
nikoji_start_session();

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
  $name    = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
  $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
  $phone   = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
  $service = htmlspecialchars(strip_tags(trim($_POST['service'] ?? '')));
  $message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));
  $honeypot= trim($_POST['website'] ?? ''); // spam trap

  if (!nikoji_csrf_valid($_POST['csrf_token'] ?? null)) {
    $error = 'Your session expired before submitting. Please try again.';
  } elseif ($honeypot) {
    $error = 'Spam detected.';
  } elseif (!$name || !$email || !$message) {
    $error = 'Please fill in all required fields.';
  } else {
    $entry = [
      'id'      => uniqid(),
      'date'    => date('Y-m-d H:i:s'),
      'name'    => $name,
      'email'   => $email,
      'phone'   => $phone,
      'service' => $service,
      'message' => $message,
      'ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    $wroteJson = nikoji_append_json(__DIR__ . '/data/contacts.json', $entry);
    $wroteCsv  = nikoji_append_csv(
      __DIR__ . '/data/contacts.csv',
      ['ID', 'Date', 'Name', 'Email', 'Phone', 'Service', 'Message', 'IP'],
      array_values($entry)
    );

    if (!$wroteJson && !$wroteCsv) {
      $error = "Sorry, we couldn't save your message right now. Please email us directly at rahul@nikojitechnologies.com or try again shortly.";
    } else {
      $success = true;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Contact Nikoji Technologies — Get in touch for industrial automation, EMS, and engineering consultancy.">
  <title>Contact Us — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/contact.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Contact Us — Nikoji Technologies">
  <meta property="og:description" content="Contact Nikoji Technologies — Get in touch for industrial automation, EMS, and engineering consultancy.">
  <meta property="og:url" content="https://nikojitechnologies.com/contact.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Contact Us — Nikoji Technologies">
  <meta name="twitter:description" content="Contact Nikoji Technologies — Get in touch for industrial automation, EMS, and engineering consultancy.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Contact Nikoji Technologies",
    "url": "https://nikojitechnologies.com/contact.php"
  }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    .contact-grid {
      display: grid; grid-template-columns: 1fr 1.4fr;
      gap: 64px; align-items: start;
    }
    .contact-info { position: sticky; top: calc(var(--nav-h) + 32px); }
    .info-card {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: var(--radius-lg); padding: 32px; margin-bottom: 16px;
    }
    .info-item {
      display: flex; align-items: flex-start; gap: 16px; padding: 16px 0;
      border-bottom: 1px solid var(--border);
    }
    .info-item:last-child { border-bottom: none; }
    .info-icon {
      width: 44px; height: 44px; flex-shrink: 0;
      background: rgba(0,107,79,0.1); border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: var(--green);
    }
    .info-icon svg { width: 20px; height: 20px; }
    .info-text strong { display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-3); text-transform:uppercase; letter-spacing:0.06em; margin-bottom: 4px; }
    .info-text a, .info-text span { font-size: 0.9rem; color: var(--text); transition: color var(--transition); }
    .info-text a:hover { color: var(--green); }
    .form-card {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: var(--radius-lg); padding: 40px;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-full { grid-column: 1/-1; }
    .success-banner {
      background: rgba(0,107,79,0.08); border: 1px solid rgba(0,107,79,0.3);
      border-radius: var(--radius); padding: 20px 24px;
      margin-bottom: 24px; display: flex; align-items: center; gap: 14px;
    }
    .success-banner svg { color: var(--green); flex-shrink: 0; }
    .success-banner strong { display: block; color: var(--green); font-weight: 600; margin-bottom: 2px; }
    .success-banner p { font-size: 0.85rem; color: var(--text-3); }
    .map-container {
      border-radius: var(--radius-lg); overflow: hidden;
      border: 1px solid var(--border); height: 360px;
    }
    .map-container iframe { width: 100%; height: 100%; border: none; display: block; }
    @media(max-width:900px){
      .contact-grid { grid-template-columns: 1fr; }
      .contact-info { position: static; }
      .form-grid { grid-template-columns: 1fr; }
      .form-full { grid-column: 1; }
    }
  </style>
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Contact Us</div>
    <h1>Let's Build Something Together</h1>
    <p>Tell us about your project — our engineers will get back to you within one business day.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="contact-grid">

      <!-- Left: Info -->
      <div class="contact-info">
        <div class="info-card reveal-left">
          <h3 style="font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:var(--text);margin-bottom:8px">Get In Touch</h3>
          <p style="font-size:0.85rem;color:var(--text-3);margin-bottom:20px">We're available Monday–Saturday, 9AM–6PM IST.</p>

          <div class="info-item">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18L6.6 2a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.08 6.08l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.82 17l.1-.08z"/></svg></div>
            <div class="info-text">
              <strong>Phone</strong>
              <a href="tel:+917678334459">+91 7678334459</a>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
            <div class="info-text">
              <strong>Email</strong>
              <a href="mailto:rahul@nikojitechnologies.com">rahul@nikojitechnologies.com</a>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div class="info-text">
              <strong>Office Address</strong>
              <span>H-108 Dharampura, Najafgarh Roshanpura Colony,<br>New Delhi – 110043, India</span>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div class="info-text">
              <strong>Working Hours</strong>
              <span>Mon–Sat: 9:00 AM – 6:00 PM IST</span>
            </div>
          </div>
        </div>

        <!-- Map -->
        <div class="map-container reveal-left" style="transition-delay:0.15s">
          <iframe
            src="https://www.google.com/maps?q=H-108+Dharampura+Najafgarh+Roshanpura+Colony+New+Delhi+110043&output=embed"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            title="Nikoji Technologies Office Location">
          </iframe>
        </div>
      </div>

      <!-- Right: Form -->
      <div class="form-card reveal-right">
        <h3 style="font-family:'Poppins',sans-serif;font-size:1.25rem;font-weight:700;color:var(--text);margin-bottom:6px">Send Us a Message</h3>
        <p style="font-size:0.875rem;color:var(--text-3);margin-bottom:28px">Fill in the details below and we'll respond within 24 hours.</p>

        <?php if ($success): ?>
        <div class="success-banner">
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          <div>
            <strong>Message Received!</strong>
            <p>Thank you for reaching out. Our team will contact you at <?= htmlspecialchars($_POST['email'] ?? '') ?> within one business day.</p>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="form-msg error" style="display:block;margin-bottom:16px"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="contact.php" id="contact-form">
          <?= nikoji_csrf_field() ?>
          <!-- Honeypot anti-spam -->
          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

          <div class="form-grid">
            <div class="form-group">
              <label for="name">Full Name *</label>
              <input type="text" id="name" name="name" placeholder="Rajesh Sharma" required
                value="<?= isset($_POST['name']) && !$success ? htmlspecialchars($_POST['name']) : '' ?>">
            </div>
            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" placeholder="you@company.com" required
                value="<?= isset($_POST['email']) && !$success ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX"
                value="<?= isset($_POST['phone']) && !$success ? htmlspecialchars($_POST['phone']) : '' ?>">
            </div>
            <div class="form-group">
              <label for="service">Service Interested In</label>
              <select id="service" name="service">
                <option value="">Select a service</option>
                <option value="Industrial Automation" <?= (($_POST['service'] ?? '')==='Industrial Automation')?'selected':'' ?>>Industrial Automation</option>
                <option value="PCB / Electronics Design" <?= (($_POST['service'] ?? '')==='PCB / Electronics Design')?'selected':'' ?>>PCB / Electronics Design</option>
                <option value="Testing Services" <?= (($_POST['service'] ?? '')==='Testing Services')?'selected':'' ?>>Testing Services (ICT/FCT/ATE)</option>
                <option value="EMS & Manufacturing" <?= (($_POST['service'] ?? '')==='EMS & Manufacturing')?'selected':'' ?>>EMS & Manufacturing</option>
                <option value="Consultancy" <?= (($_POST['service'] ?? '')==='Consultancy')?'selected':'' ?>>Engineering Consultancy</option>
                <option value="Innovation Lab" <?= (($_POST['service'] ?? '')==='Innovation Lab')?'selected':'' ?>>Innovation Lab</option>
                <option value="PLC Repair & Diagnostics" <?= (($_POST['service'] ?? '')==='PLC Repair & Diagnostics')?'selected':'' ?>>PLC Repair & Diagnostics</option>
                <option value="Other" <?= (($_POST['service'] ?? '')==='Other')?'selected':'' ?>>Other</option>
              </select>
            </div>
            <div class="form-group form-full">
              <label for="message">Your Message *</label>
              <textarea id="message" name="message" placeholder="Describe your project requirements, timeline, and any specific technical needs..." required><?= isset($_POST['message']) && !$success ? htmlspecialchars($_POST['message']) : '' ?></textarea>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">
            Send Message
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
          <p style="font-size:0.75rem;color:var(--text-3);text-align:center;margin-top:12px">
            Your information is kept confidential and never shared with third parties.
          </p>
        </form>
      </div>

    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
