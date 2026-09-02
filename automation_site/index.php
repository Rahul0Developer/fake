<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// index.php — Nikoji Technologies Homepage
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Nikoji Technologies — Industrial Automation, EMS, PCB Design, Testing & Engineering Consultancy based in New Delhi, India.">
  <meta name="keywords" content="industrial automation, PLC, SCADA, HMI, EMS, PCB design, testing, New Delhi">
  <meta name="author" content="Nikoji Technologies">
  <title>Nikoji Technologies — Industrial Automation & EMS Partner</title>
  <link rel="canonical" href="https://nikojitechnologies.com/index.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Nikoji Technologies — Industrial Automation & EMS Partner">
  <meta property="og:description" content="Nikoji Technologies — Industrial Automation, EMS, PCB Design, Testing & Engineering Consultancy based in New Delhi, India.">
  <meta property="og:url" content="https://nikojitechnologies.com/index.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Nikoji Technologies — Industrial Automation & EMS Partner">
  <meta name="twitter:description" content="Nikoji Technologies — Industrial Automation, EMS, PCB Design, Testing & Engineering Consultancy based in New Delhi, India.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Nikoji Technologies",
    "url": "https://nikojitechnologies.com/",
    "logo": "https://nikojitechnologies.com/assets/images/logo.jpg",
    "image": "https://nikojitechnologies.com/assets/images/logo.jpg",
    "telephone": "+91-7678334459",
    "email": "rahul@nikojitechnologies.com",
    "foundingDate": "2026",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "H-108 Dharampura, Najafgarh Roshanpura Colony",
      "addressLocality": "New Delhi",
      "postalCode": "110043",
      "addressCountry": "IN"
    },
    "areaServed": {
      "@type": "Country",
      "name": "India"
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
      "opens": "09:00",
      "closes": "18:00"
    },
    "sameAs": [
      "https://linkedin.com/company/nikoji-technologies"
    ],
    "makesOffer": [
      {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Industrial Automation (PLC/SCADA/HMI)"}},
      {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "PCB & Electronics Design"}},
      {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Testing & Quality Assurance"}},
      {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Electronics Manufacturing Services"}},
      {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Engineering Consultancy"}},
      {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Innovation Lab & R&D"}}
    ]
  }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    /* ── Hero ── */
    .hero {
      min-height: 100vh;
      padding-top: var(--nav-h);
      display: flex; align-items: center;
      background: var(--bg);
      position: relative; overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute; top: -10%; right: -5%;
      width: 55vw; max-width: 700px; height: 100vh;
      background: radial-gradient(ellipse at center, rgba(0,107,79,0.07) 0%, transparent 70%);
      pointer-events: none;
    }
    .hero-inner {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 64px; align-items: center;
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(0,107,79,0.08);
      border: 1px solid rgba(0,107,79,0.2);
      border-radius: 20px; padding: 7px 16px;
      font-size: 0.75rem; font-weight: 700;
      letter-spacing: 0.1em; text-transform: uppercase;
      color: var(--green); margin-bottom: 24px;
    }
    .hero-badge span { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: var(--gold); }
    .hero h1 {
      font-family: 'Poppins', sans-serif;
      font-size: clamp(2.2rem, 5vw, 3.8rem);
      font-weight: 800; line-height: 1.12;
      letter-spacing: -0.03em; color: var(--text);
      margin-bottom: 24px;
    }
    .hero h1 .highlight {
      color: var(--green); position: relative;
      display: inline-block;
    }
    .hero h1 .highlight::after {
      content: '';
      position: absolute; bottom: 2px; left: 0; right: 0;
      height: 4px; background: var(--gold);
      border-radius: 2px; opacity: 0.6;
    }
    .hero-desc {
      font-size: 1.05rem; color: var(--text-3);
      max-width: 480px; line-height: 1.8;
      margin-bottom: 36px;
    }
    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; align-items: center; }
    .hero-trust {
      display: flex; align-items: center; gap: 12px;
      margin-top: 48px; padding-top: 32px;
      border-top: 1px solid var(--border);
    }
    .trust-avatars { display: flex; }
    .trust-avatars div {
      width: 36px; height: 36px; border-radius: 50%;
      border: 2px solid var(--bg);
      background: var(--green);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.75rem; font-weight: 700; color: #fff;
      margin-left: -8px;
    }
    .trust-avatars div:first-child { margin-left: 0; }
    .trust-text { font-size: 0.82rem; color: var(--text-3); }
    .trust-text strong { color: var(--text); }

    /* Hero SVG Animation */
    .hero-visual {
      position: relative; display: flex; align-items: center; justify-content: center;
    }
    .hero-svg-wrap {
      width: 100%; max-width: 520px;
      position: relative;
    }
    .hero-card-float {
      position: absolute;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 16px;
      box-shadow: var(--shadow);
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--text);
      white-space: nowrap;
      animation: float-card 3s ease-in-out infinite;
    }
    .hero-card-float.c1 { top: 10%; left: -8%; animation-delay: 0s; }
    .hero-card-float.c2 { bottom: 18%; right: -8%; animation-delay: 1.5s; }
    .hero-card-float .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    @keyframes float-card {
      0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)}
    }

    /* ── About ── */
    .about-split {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 80px; align-items: center;
    }
    .about-visual-grid {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    .about-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px; text-align: center;
      transition: all var(--transition);
    }
    .about-card:hover { border-color: rgba(0,107,79,0.3); transform: translateY(-3px); }
    .about-card svg { width: 32px; height: 32px; color: var(--green); margin: 0 auto 12px; }
    .about-card h4 { font-size: 0.9rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .about-card p { font-size: 0.78rem; color: var(--text-3); }
    .about-card.accent { background: var(--green); border-color: var(--green); }
    .about-card.accent svg, .about-card.accent h4, .about-card.accent p { color: #fff !important; }

    /* ── Why Us ── */
    .why-grid {
      display: grid; grid-template-columns: repeat(3,1fr); gap: 24px;
    }
    .why-item {
      padding: 32px 28px;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      background: var(--card-bg);
      transition: all var(--transition);
      position: relative; overflow: hidden;
    }
    .why-item::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0;
      height: 3px; background: var(--green);
      transform: scaleX(0); transform-origin: left;
      transition: transform var(--transition);
    }
    .why-item:hover::before { transform: scaleX(1); }
    .why-item:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
    .why-number {
      font-family: 'Poppins', sans-serif;
      font-size: 3rem; font-weight: 800;
      color: rgba(0,107,79,0.1); line-height: 1;
      margin-bottom: 16px;
    }
    .why-item h3 {
      font-family: 'Poppins', sans-serif;
      font-size: 1rem; font-weight: 700;
      color: var(--text); margin-bottom: 10px;
    }
    .why-item p { font-size: 0.875rem; color: var(--text-3); line-height: 1.7; }

    /* ── Sectors ── */
    .sectors-grid {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
    }
    .sector-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 28px 20px; text-align: center;
      transition: all var(--transition); cursor: default;
    }
    .sector-card:hover {
      background: var(--green); border-color: var(--green);
      transform: translateY(-4px); box-shadow: var(--shadow);
    }
    .sector-card svg { width: 36px; height: 36px; margin: 0 auto 14px; color: var(--green); transition: color var(--transition); }
    .sector-card:hover svg { color: var(--gold); }
    .sector-card h4 { font-size: 0.88rem; font-weight: 600; color: var(--text); transition: color var(--transition); }
    .sector-card:hover h4 { color: #fff; }

    /* ── Services Preview ── */
    .services-preview {
      display: grid; grid-template-columns: repeat(3,1fr); gap: 24px;
    }
    .sp-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow: hidden; transition: all var(--transition);
    }
    .sp-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); border-color: rgba(0,107,79,0.25); }
    .sp-card-top {
      padding: 32px 28px 24px;
    }
    .sp-card-icon {
      width: 52px; height: 52px; background: rgba(0,107,79,0.09);
      border-radius: 14px; display: flex; align-items: center; justify-content: center;
      color: var(--green); margin-bottom: 20px;
    }
    .sp-card-icon svg { width: 24px; height: 24px; }
    .sp-card-top h3 {
      font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 700;
      color: var(--text); margin-bottom: 10px;
    }
    .sp-card-top p { font-size: 0.875rem; color: var(--text-3); line-height: 1.7; }
    .sp-card-footer {
      padding: 16px 28px; border-top: 1px solid var(--border);
      background: rgba(0,107,79,0.03);
    }
    .sp-card-footer a {
      font-size: 0.82rem; font-weight: 700; color: var(--green);
      display: flex; align-items: center; gap: 6px; transition: gap var(--transition);
    }
    .sp-card:hover .sp-card-footer a { gap: 10px; }

    @media(max-width:1024px){
      .hero-inner { grid-template-columns:1fr; }
      .hero-visual { display:none; }
      .about-split { grid-template-columns:1fr; }
      .why-grid { grid-template-columns:1fr 1fr; }
      .sectors-grid { grid-template-columns:repeat(2,1fr); }
      .services-preview { grid-template-columns:1fr 1fr; }
    }
    @media(max-width:640px){
      .why-grid,.services-preview,.sectors-grid { grid-template-columns:1fr; }
      .about-visual-grid { grid-template-columns:1fr 1fr; }
    }
  </style>
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<!-- ════════════════════════════
     HERO
═════════════════════════════ -->
<section class="hero" id="home" aria-label="Hero">
  <div class="container">
    <div class="hero-inner">

      <div class="hero-content">
        <div class="hero-badge">
          <span></span> Industrial Technology Partner
        </div>
        <h1>
          Your Partner in<br>
          <span class="highlight">Industrial Automation</span><br>
          &amp; EMS
        </h1>
        <p class="hero-desc">
          We design, build, and deploy precision-grade automation systems, embedded electronics, and engineering solutions — from concept to commissioning.
        </p>
        <div class="hero-actions">
          <a href="contact.php" class="btn btn-primary btn-lg">
            Let's Collaborate
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="services.php" class="btn btn-outline">Explore Services</a>
        </div>
        <div class="hero-trust">
          <div class="trust-text">
            <strong>Founded 2026</strong> · Based in New Delhi · Direct engineer access on every project, no call centre in between
          </div>
        </div>
      </div>

      <div class="hero-visual" aria-hidden="true">
        <div class="hero-svg-wrap">
          <!-- Industrial SVG Animation -->
          <svg viewBox="0 0 500 460" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%">
            <!-- Background grid -->
            <defs>
              <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(0,107,79,0.06)" stroke-width="1"/>
              </pattern>
              <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#006B4F;stop-opacity:1"/>
                <stop offset="100%" style="stop-color:#004d38;stop-opacity:1"/>
              </linearGradient>
            </defs>
            <rect width="500" height="460" fill="url(#grid)"/>

            <!-- Central machine body -->
            <rect x="140" y="120" width="220" height="200" rx="16" fill="url(#grad1)"/>
            <rect x="155" y="135" width="190" height="170" rx="10" fill="rgba(255,255,255,0.05)"/>

            <!-- Display screen -->
            <rect x="170" y="150" width="160" height="100" rx="6" fill="#0a1f16"/>
            <!-- Screen content - PCB traces -->
            <line x1="185" y1="165" x2="315" y2="165" stroke="#FFD700" stroke-width="1.5" opacity="0.6">
              <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" repeatCount="indefinite"/>
            </line>
            <line x1="185" y1="180" x2="280" y2="180" stroke="#00d4aa" stroke-width="1" opacity="0.5">
              <animate attributeName="opacity" values="0.5;1;0.5" dur="1.8s" begin="0.3s" repeatCount="indefinite"/>
            </line>
            <line x1="185" y1="195" x2="295" y2="195" stroke="#FFD700" stroke-width="1" opacity="0.4">
              <animate attributeName="opacity" values="0.2;0.7;0.2" dur="2.4s" begin="0.6s" repeatCount="indefinite"/>
            </line>
            <circle cx="220" cy="215" r="12" fill="none" stroke="#00d4aa" stroke-width="2">
              <animate attributeName="r" values="10;14;10" dur="2s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values="1;0.4;1" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="220" cy="215" r="5" fill="#00d4aa" opacity="0.8"/>
            <text x="240" y="220" fill="rgba(255,255,255,0.7)" font-size="10" font-family="monospace">ONLINE</text>

            <!-- Buttons row -->
            <rect x="170" y="260" width="28" height="28" rx="4" fill="#FFD700"/>
            <rect x="206" y="260" width="28" height="28" rx="4" fill="rgba(255,255,255,0.15)"/>
            <rect x="242" y="260" width="28" height="28" rx="4" fill="rgba(255,255,255,0.15)"/>
            <rect x="278" y="260" width="52" height="28" rx="4" fill="rgba(0,212,170,0.3)"/>

            <!-- Robotic Arm -->
            <g>
              <animateTransform attributeName="transform" type="translate" values="0,0;0,-8;0,0" dur="3s" repeatCount="indefinite"/>
              <!-- Arm base -->
              <rect x="330" y="200" width="20" height="60" rx="6" fill="#FFD700" opacity="0.9"/>
              <!-- Arm segment 1 -->
              <line x1="340" y1="200" x2="380" y2="160" stroke="#006B4F" stroke-width="8" stroke-linecap="round"/>
              <!-- Arm segment 2 -->
              <line x1="380" y1="160" x2="400" y2="130" stroke="#004d38" stroke-width="6" stroke-linecap="round"/>
              <!-- End effector -->
              <circle cx="400" cy="125" r="10" fill="#FFD700"/>
              <line x1="395" y1="115" x2="390" y2="108" stroke="#333" stroke-width="3" stroke-linecap="round"/>
              <line x1="405" y1="115" x2="410" y2="108" stroke="#333" stroke-width="3" stroke-linecap="round"/>
            </g>

            <!-- Conveyor base -->
            <rect x="80" y="340" width="340" height="40" rx="8" fill="#1a1a2e"/>
            <rect x="80" y="340" width="340" height="12" rx="6" fill="#2a2a4e"/>
            <!-- Conveyor dots -->
            <circle cx="120" cy="346" r="5" fill="#444">
              <animateTransform attributeName="transform" type="translate" values="0,0;60,0;0,0" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="200" cy="346" r="5" fill="#444">
              <animateTransform attributeName="transform" type="translate" values="0,0;60,0;0,0" dur="2s" begin="0.5s" repeatCount="indefinite"/>
            </circle>

            <!-- Floating particles -->
            <circle cx="80" cy="180" r="4" fill="var(--gold)" opacity="0.5">
              <animate attributeName="cy" values="180;160;180" dur="3s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values="0.5;0.9;0.5" dur="3s" repeatCount="indefinite"/>
            </circle>
            <circle cx="440" cy="240" r="3" fill="#00d4aa" opacity="0.6">
              <animate attributeName="cy" values="240;220;240" dur="2.5s" repeatCount="indefinite"/>
            </circle>
            <circle cx="100" cy="280" r="5" fill="rgba(0,107,79,0.4)">
              <animate attributeName="cy" values="280;260;280" dur="3.5s" repeatCount="indefinite"/>
            </circle>

            <!-- PCB traces bottom -->
            <path d="M 80 400 L 120 400 L 120 420 L 200 420" stroke="rgba(0,107,79,0.4)" stroke-width="2" fill="none"/>
            <path d="M 420 400 L 380 400 L 380 420 L 300 420" stroke="rgba(255,215,0,0.3)" stroke-width="2" fill="none"/>
          </svg>

          <!-- Floating stat cards -->
          <div class="hero-card-float c1">
            <span class="dot" style="background:#00d4aa"></span> PLC · SCADA · HMI
          </div>
          <div class="hero-card-float c2">
            <span class="dot" style="background:#FFD700"></span> New Delhi · Est. 2026
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════
     STATS
═════════════════════════════ -->
<section class="section-sm" id="stats">
  <div class="container">
    <div class="stats-bar reveal">
      <div class="stat-item">
        <div class="stat-number" data-count="2026" data-decimals="0">0</div>
        <div class="stat-label">Founded In</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="6" data-suffix="">0</div>
        <div class="stat-label">Engineering Disciplines In-House</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="24" data-suffix="hrs">0</div>
        <div class="stat-label">Typical Enquiry Response</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="100" data-suffix="%">0%</div>
        <div class="stat-label">Direct Engineer Access</div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════
     ABOUT
═════════════════════════════ -->
<section class="section" id="about" style="background:var(--bg)">
  <div class="container">
    <div class="about-split">

      <div class="about-visual-grid reveal-left">
        <div class="about-card">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          <h4>Multi-Domain</h4>
          <p>Automation, EMS, Design & Testing</p>
        </div>
        <div class="about-card accent">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          <h4>Real-Time Systems</h4>
          <p>SCADA, HMI & Control Panels</p>
        </div>
        <div class="about-card accent">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
          <h4>Embedded Systems</h4>
          <p>PCB design to production</p>
        </div>
        <div class="about-card">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <h4>Expert Team</h4>
          <p>Engineers, developers, designers</p>
        </div>
      </div>

      <div class="reveal-right">
        <div class="section-label">About Nikoji Technologies</div>
        <h2 class="section-title">Engineering Trust.<br>Delivering <em>Results</em>.</h2>
        <p class="section-sub" style="margin-bottom:24px">
          Nikoji Technologies is a New Delhi-based industrial engineering company specializing in automation, electronics manufacturing services, and precision engineering — serving sectors from manufacturing to energy.
        </p>
        <p style="font-size:0.9rem;color:var(--text-3);line-height:1.8;margin-bottom:32px">
          Founded on the principle that great industrial systems must be both technically sound and operationally reliable, we work closely with our clients from initial feasibility to full deployment — ensuring every system performs exactly as designed, day after day.
        </p>
        <div style="display:flex;gap:14px;flex-wrap:wrap">
          <a href="services.php" class="btn btn-primary">Our Services</a>
          <a href="contact.php" class="btn btn-outline">Get in Touch</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════
     SERVICES PREVIEW
═════════════════════════════ -->
<section class="section" id="services" style="background:var(--card-bg)">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:56px">
      <div class="section-label">What We Do</div>
      <h2 class="section-title">Comprehensive Engineering <em>Services</em></h2>
      <p class="section-sub">From PLC programming to full EMS production runs — we cover every stage of the industrial engineering lifecycle.</p>
    </div>

    <div class="services-preview" data-stagger>

      <div class="sp-card">
        <div class="sp-card-top">
          <div class="sp-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          </div>
          <h3>Industrial Automation</h3>
          <p>PLC programming, SCADA systems, HMI development, VFD integration, servo drives, and custom motor control panels for complete factory automation.</p>
        </div>
        <div class="sp-card-footer">
          <a href="automation.php">Learn more →</a>
        </div>
      </div>

      <div class="sp-card">
        <div class="sp-card-top">
          <div class="sp-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
          </div>
          <h3>Electronics Design</h3>
          <p>PCB schematic and layout design, embedded systems firmware, CAD mechanical modeling, and UI/UX for industrial interfaces.</p>
        </div>
        <div class="sp-card-footer">
          <a href="design.php">Learn more →</a>
        </div>
      </div>

      <div class="sp-card">
        <div class="sp-card-top">
          <div class="sp-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v11m0 0h10m-10 0a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2m-10 0V9m10 5V9m0 0H9"/></svg>
          </div>
          <h3>Testing & Quality</h3>
          <p>ICT, FCT, ATE, and flying probe testing solutions ensuring your boards and assemblies meet the highest quality standards before deployment.</p>
        </div>
        <div class="sp-card-footer">
          <a href="testing.php">Learn more →</a>
        </div>
      </div>

      <div class="sp-card">
        <div class="sp-card-top">
          <div class="sp-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/></svg>
          </div>
          <h3>EMS & Manufacturing</h3>
          <p>SMT, through-hole assembly, box build, and rapid prototyping — complete electronics manufacturing services under one roof.</p>
        </div>
        <div class="sp-card-footer">
          <a href="ems.php">Learn more →</a>
        </div>
      </div>

      <div class="sp-card">
        <div class="sp-card-top">
          <div class="sp-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          </div>
          <h3>Consultancy</h3>
          <p>Strategic industrial assessment, technology roadmapping, process optimization, and full deployment planning for your operations.</p>
        </div>
        <div class="sp-card-footer">
          <a href="consultancy.php">Learn more →</a>
        </div>
      </div>

      <div class="sp-card">
        <div class="sp-card-top">
          <div class="sp-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <h3>Innovation Lab</h3>
          <p>From discovery to prototype — our structured innovation process turns your industrial challenges into working, deployable solutions.</p>
        </div>
        <div class="sp-card-footer">
          <a href="innovation.php">Learn more →</a>
        </div>
      </div>

    </div>

    <div style="text-align:center;margin-top:48px">
      <a href="services.php" class="btn btn-outline">View All Services</a>
    </div>
  </div>
</section>

<!-- ════════════════════════════
     WHY US
═════════════════════════════ -->
<section class="section" id="why-us">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:56px">
      <div class="section-label">Why Nikoji</div>
      <h2 class="section-title">Built on <em>Engineering</em> Excellence</h2>
    </div>

    <div class="why-grid" data-stagger>
      <div class="why-item">
        <div class="why-number">01</div>
        <h3>End-to-End Capability</h3>
        <p>From concept and design to prototyping, testing, and full production — we handle the complete engineering lifecycle so you work with a single accountable partner.</p>
      </div>
      <div class="why-item">
        <div class="why-number">02</div>
        <h3>Industry-Hardened Systems</h3>
        <p>Every solution we deliver is designed for the real industrial environment — vibration, temperature, EMI, and operational demands that lab conditions can't replicate.</p>
      </div>
      <div class="why-item">
        <div class="why-number">03</div>
        <h3>Rapid Deployment</h3>
        <p>Our structured project methodology means faster time-to-commissioning without compromising on quality. Most projects run on schedule and within scope.</p>
      </div>
      <div class="why-item">
        <div class="why-number">04</div>
        <h3>Transparent Communication</h3>
        <p>Weekly progress reports, milestone check-ins, and a dedicated project lead ensure you always know exactly where your project stands.</p>
      </div>
      <div class="why-item">
        <div class="why-number">05</div>
        <h3>Post-Delivery Support</h3>
        <p>We don't disappear after handover. Our support model includes documentation, training, AMC options, and remote diagnostics for ongoing peace of mind.</p>
      </div>
      <div class="why-item">
        <div class="why-number">06</div>
        <h3>Cost-Effective Solutions</h3>
        <p>Competitive pricing built on lean engineering practices — you get the quality of a large firm at the agility and cost structure of a focused specialist team.</p>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════
     INDUSTRY SECTORS
═════════════════════════════ -->
<section class="section" id="sectors" style="background:var(--card-bg)">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:48px">
      <div class="section-label">Industries We Serve</div>
      <h2 class="section-title">Across Every <em>Industrial Sector</em></h2>
    </div>

    <div class="sectors-grid" data-stagger>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <h4>Manufacturing</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        <h4>Energy & Power</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        <h4>Automotive</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        <h4>Healthcare</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        <h4>Oil & Gas</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        <h4>Food & Beverage</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
        <h4>FMCG & Packaging</h4>
      </div>
      <div class="sector-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
        <h4>Electronics</h4>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════
     CTA BANNER
═════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Ready to Automate Your Operations?</h2>
      <p>Let's discuss your project requirements. Our engineers are ready to help you find the right technical solution.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Start a Project</a>
        <a href="tel:+917678334459" class="btn btn-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18L6.6 2a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.08 6.08l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.82 17l.1-.08z"/></svg>
          +91 7678334459
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
