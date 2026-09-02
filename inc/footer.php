<?php // inc/footer.php ?>
<footer>
  <div class="container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">
        <img src="assets/images/logo.jpg" alt="Nikoji Technologies" width="48" height="48">
        <p>Nikoji Technologies delivers precision industrial automation, EMS, and engineering consultancy solutions — bridging the gap between innovation and real-world implementation.</p>
        <div class="footer-socials">
          <a href="https://linkedin.com/company/nikoji-technologies" class="footer-social" aria-label="LinkedIn" target="_blank" rel="noopener">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a href="mailto:rahul@nikojitechnologies.com" class="footer-social" aria-label="Email">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </a>
          <a href="tel:+917678334459" class="footer-social" aria-label="Phone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18L6.6 2a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.08 6.08l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.82 17l.1-.08z"/></svg>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-col">
        <h4>Services</h4>
        <ul>
          <li><a href="automation.php">PLC / SCADA / HMI</a></li>
          <li><a href="design.php">PCB & CAD Design</a></li>
          <li><a href="testing.php">ICT / FCT / ATE</a></li>
          <li><a href="ems.php">EMS & Assembly</a></li>
          <li><a href="consultancy.php">Consultancy</a></li>
          <li><a href="innovation.php">Innovation Lab</a></li>
        </ul>
      </div>

      <!-- Company -->
      <div class="footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="index.php#about">About Us</a></li>
          <li><a href="services.php">All Services</a></li>
          <li><a href="index.php#sectors">Industries</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="index.php#why-us">Why Nikoji</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="footer-col footer-newsletter">
        <h4>Stay Updated</h4>
        <p style="font-size:0.82rem;color:rgba(255,255,255,0.45);margin-bottom:16px;line-height:1.6">
          Get updates on automation trends, new capabilities, and industry insights.
        </p>
        <form id="newsletter-form">
          <?= nikoji_csrf_field() ?>
          <div class="newsletter-input-group" style="margin-bottom:10px">
            <input type="text" name="name" placeholder="Your name" required>
          </div>
          <div class="newsletter-input-group">
            <input type="email" name="email" placeholder="your@email.com" required>
            <button type="submit">Subscribe</button>
          </div>
          <div class="newsletter-msg"></div>
        </form>
        <div style="margin-top:20px;font-size:0.75rem;color:rgba(255,255,255,0.3)">
          📍 H-108 Dharampura, Najafgarh Roshanpura Colony, New Delhi-110043
        </div>
      </div>

    </div><!-- /footer-grid -->

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> Nikoji Technologies. All rights reserved.</p>
      <div style="display:flex;gap:20px;align-items:center">
        <a href="contact.php">Contact</a>
        <a href="services.php">Services</a>
        <a href="mailto:rahul@nikojitechnologies.com">rahul@nikojitechnologies.com</a>
      </div>
    </div>

  </div>
</footer>

<div id="toast" class="toast" role="alert" aria-live="polite"></div>
<script src="assets/js/main.js"></script>
