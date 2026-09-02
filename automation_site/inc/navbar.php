<?php
// inc/navbar.php
$current = basename($_SERVER['PHP_SELF'], '.php');
$current_full = basename($_SERVER['PHP_SELF']);
?>
<div id="page-loader">
  <div class="loader-inner">
    <div class="loader-logo-frame">
      <img src="assets/images/logo.jpg" alt="Nikoji Technologies" class="loader-logo">
    </div>
    <div class="loader-bar"><div class="loader-bar-fill"></div></div>
  </div>
</div>

<nav class="navbar" role="navigation" aria-label="Main navigation">
  <div class="nav-inner">
    <a href="index.php" class="nav-logo" aria-label="Nikoji Technologies Home">
      <img src="assets/images/logo.jpg" alt="Nikoji Technologies Logo" width="42" height="42">
      <div class="nav-logo-text">
        <strong>Nikoji Technologies</strong>
        <span>Industrial Automation</span>
      </div>
    </a>

    <div class="nav-links">
      <a href="index.php" <?= $current==='index'?'class="active"':'' ?>>Home</a>
      <div class="has-dropdown">
        <a href="services.php" <?= $current==='services'?'class="active"':'' ?>>Services ▾</a>
        <div class="nav-dropdown">
          <a href="automation.php">⚙️ Automation</a>
          <a href="design.php">🔧 Design</a>
          <a href="testing.php">🔬 Testing</a>
          <a href="ems.php">📦 EMS</a>
          <a href="consultancy.php">🤝 Consultancy</a>
          <a href="innovation.php">🚀 Innovation</a>
        </div>
      </div>
      <a href="automation.php" <?= $current==='automation'?'class="active"':'' ?>>Automation</a>
      <a href="ems.php" <?= $current==='ems'?'class="active"':'' ?>>EMS</a>
      <a href="innovation.php" <?= $current==='innovation'?'class="active"':'' ?>>Innovation</a>
      <a href="contact.php" class="nav-cta" <?= $current==='contact'?'style="opacity:0.85"':'' ?>>Let's Talk</a>
    </div>

    <div class="nav-right">
      <button class="theme-toggle" id="theme-toggle" aria-label="Toggle dark mode">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>
      <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

<div class="mobile-menu" id="mobile-menu">
  <a href="index.php">Home</a>
  <a href="automation.php">Automation</a>
  <a href="design.php">Design</a>
  <a href="testing.php">Testing</a>
  <a href="ems.php">EMS</a>
  <a href="innovation.php">Innovation</a>
  <a href="consultancy.php">Consultancy</a>
  <a href="services.php">All Services</a>
  <a href="contact.php" class="mobile-cta">Let's Talk →</a>
</div>
