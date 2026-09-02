<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// ems.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Electronics Manufacturing Services — SMT, Through-Hole Assembly, Box Build & Rapid Prototyping by Nikoji Technologies.">
  <title>EMS & Manufacturing — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/ems.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="EMS & Manufacturing — Nikoji Technologies">
  <meta property="og:description" content="Electronics Manufacturing Services — SMT, Through-Hole Assembly, Box Build & Rapid Prototyping by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/ems.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="EMS & Manufacturing — Nikoji Technologies">
  <meta name="twitter:description" content="Electronics Manufacturing Services — SMT, Through-Hole Assembly, Box Build & Rapid Prototyping by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Electronics Manufacturing Services (EMS)",
    "name": "Electronics Manufacturing Services (EMS)",
    "description": "SMT and through-hole assembly, box build, component sourcing and rapid prototyping for electronics production runs.",
    "provider": {
      "@type": "LocalBusiness",
      "name": "Nikoji Technologies",
      "url": "https://nikojitechnologies.com/",
      "telephone": "+91-7678334459",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "H-108 Dharampura, Najafgarh Roshanpura Colony",
        "addressLocality": "New Delhi",
        "postalCode": "110043",
        "addressCountry": "IN"
      }
    },
    "areaServed": {
      "@type": "Country",
      "name": "India"
    }
  }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    .ems-process {
      display: flex; gap: 0; align-items: stretch;
      background: var(--green); border-radius: var(--radius-lg);
      overflow: hidden; flex-wrap: wrap;
    }
    .ems-step {
      flex: 1; min-width: 140px; padding: 28px 20px;
      text-align: center; position: relative;
      border-right: 1px solid rgba(255,255,255,0.1);
    }
    .ems-step:last-child { border-right: none; }
    .ems-step-num {
      font-family: 'Poppins',sans-serif; font-size: 2rem; font-weight: 800;
      color: rgba(255,255,255,0.1); line-height: 1; margin-bottom: 8px;
    }
    .ems-step h4 { font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .ems-step p  { font-size: 0.72rem; color: rgba(255,255,255,0.55); line-height: 1.5; }
    .capability-row {
      display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;
    }
    .cap-card {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: var(--radius-lg); padding: 32px;
      transition: all 0.25s;
    }
    .cap-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); border-color: rgba(0,107,79,0.25); }
    .cap-icon { font-size: 2rem; margin-bottom: 14px; }
    .cap-card h3 { font-family:'Poppins',sans-serif; font-size:1.05rem; font-weight:700; color:var(--text); margin-bottom:10px; }
    .cap-card p  { font-size:0.875rem; color:var(--text-3); line-height:1.75; margin-bottom:16px; }
    .cap-specs { display:flex; flex-direction:column; gap:8px; }
    .cap-spec  { display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; padding:6px 0; border-bottom:1px solid var(--border); }
    .cap-spec:last-child { border:none; }
    .cap-spec span:first-child { color:var(--text-3); }
    .cap-spec span:last-child  { font-weight:600; color:var(--text); }
    @media(max-width:768px){ .capability-row{grid-template-columns:1fr;} .ems-process{flex-direction:column;} .ems-step{border-right:none;border-bottom:1px solid rgba(255,255,255,0.1);} }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Electronics Manufacturing</div>
    <h1>End-to-End Electronics<br>Manufacturing Services</h1>
    <p>From a single prototype to full production runs — SMT, through-hole, box build, and everything in between, delivered on schedule.</p>
  </div>
</div>

<!-- SMT SVG Animation -->
<section class="section-sm">
  <div class="container">
    <div style="background:#0a1a12;border-radius:var(--radius-lg);padding:36px;overflow:hidden" class="reveal">
      <p style="text-align:center;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:20px">SMT Pick & Place — Production Line</p>
      <svg viewBox="0 0 800 160" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%">
        <!-- Conveyor -->
        <rect x="20" y="100" width="760" height="40" rx="4" fill="#1a2a22"/>
        <rect x="20" y="100" width="760" height="12" rx="4" fill="#0d2018"/>
        <!-- Conveyor rollers -->
        <?php for($i=0;$i<20;$i++): ?>
        <circle cx="<?=40+$i*38?>" cy="106" r="5" fill="#2a3a28" stroke="rgba(0,107,79,0.3)" stroke-width="1"/>
        <?php endfor; ?>
        <!-- Moving PCB boards -->
        <rect x="60" y="112" width="120" height="24" rx="4" fill="#0d2a1e" stroke="rgba(0,107,79,0.5)" stroke-width="1">
          <animateTransform attributeName="transform" type="translate" values="0,0;640,0" dur="6s" repeatCount="indefinite"/>
        </rect>
        <rect x="220" y="112" width="120" height="24" rx="4" fill="#0d2a1e" stroke="rgba(0,107,79,0.5)" stroke-width="1">
          <animateTransform attributeName="transform" type="translate" values="-160,0;480,0" dur="6s" repeatCount="indefinite"/>
        </rect>
        <!-- Pick & Place nozzle -->
        <line x1="400" y1="20" x2="400" y2="95" stroke="rgba(255,215,0,0.6)" stroke-width="2">
          <animate attributeName="y2" values="95;110;95" dur="1.5s" repeatCount="indefinite"/>
          <animate attributeName="x1" values="400;360;400;440;400" dur="4s" repeatCount="indefinite"/>
          <animate attributeName="x2" values="400;360;400;440;400" dur="4s" repeatCount="indefinite"/>
        </line>
        <circle cx="400" cy="95" r="8" fill="var(--gold)" opacity="0.9">
          <animate attributeName="cy" values="95;110;95" dur="1.5s" repeatCount="indefinite"/>
          <animate attributeName="cx" values="400;360;400;440;400" dur="4s" repeatCount="indefinite"/>
        </circle>
        <!-- Gantry frame -->
        <rect x="340" y="10" width="120" height="10" rx="3" fill="rgba(255,255,255,0.08)"/>
        <rect x="340" y="10" width="6" height="90" rx="2" fill="rgba(255,255,255,0.06)"/>
        <rect x="454" y="10" width="6" height="90" rx="2" fill="rgba(255,255,255,0.06)"/>
        <!-- Component reels (left) -->
        <?php for($i=0;$i<4;$i++): ?>
        <circle cx="<?=40+$i*32?>" cy="60" r="20" fill="none" stroke="rgba(0,107,79,0.25)" stroke-width="6"/>
        <circle cx="<?=40+$i*32?>" cy="60" r="4" fill="rgba(0,107,79,0.4)"/>
        <?php endfor; ?>
        <!-- Labels -->
        <text x="240" y="155" fill="rgba(255,255,255,0.25)" font-size="9" font-family="monospace" text-anchor="middle">PICK &amp; PLACE MACHINE</text>
        <text x="620" y="155" fill="rgba(255,255,255,0.25)" font-size="9" font-family="monospace" text-anchor="middle">REFLOW OVEN →</text>
        <rect x="560" y="95" width="160" height="45" rx="4" fill="rgba(255,100,50,0.08)" stroke="rgba(255,100,50,0.2)" stroke-width="1"/>
        <text x="640" y="122" fill="rgba(255,100,50,0.5)" font-size="9" font-family="monospace" text-anchor="middle">250°C</text>
      </svg>
    </div>
  </div>
</section>

<!-- Manufacturing Process -->
<section class="section-sm">
  <div class="container">
    <div class="ems-process reveal">
      <?php $steps=[['01','Gerber Review','DFM check before production'],['02','Stencil & Paste','Solder paste printing'],['03','Pick & Place','Automated component placement'],['04','Reflow Soldering','Controlled temperature profile'],['05','AOI Inspection','Optical quality check'],['06','Testing','ICT / FCT validation'],['07','Box Build','System integration'],['08','Shipping','Packed & documented']]; ?>
      <?php foreach($steps as [$n,$t,$d]): ?>
      <div class="ems-step">
        <div class="ems-step-num"><?=$n?></div>
        <h4><?=$t?></h4>
        <p><?=$d?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Capabilities -->
<section class="section">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:48px">
      <div class="section-label">Manufacturing Capabilities</div>
      <h2 class="section-title">Built for <em>Precision</em> & Volume</h2>
    </div>

    <div class="capability-row" data-stagger>
      <div class="cap-card">
        <div class="cap-icon">🏭</div>
        <h3>SMT Assembly</h3>
        <p>Surface mount technology assembly with automated pick & place, solder paste printing, and lead-free reflow — supporting 0201 to large connectors.</p>
        <div class="cap-specs">
          <div class="cap-spec"><span>Min Component Size</span><span>0201 (0.6×0.3mm)</span></div>
          <div class="cap-spec"><span>Board Size</span><span>Up to 510×460mm</span></div>
          <div class="cap-spec"><span>Placement Speed</span><span>5,000 CPH</span></div>
          <div class="cap-spec"><span>Soldering Profile</span><span>Lead-free (SAC305)</span></div>
        </div>
      </div>

      <div class="cap-card">
        <div class="cap-icon">🔌</div>
        <h3>Through-Hole Assembly</h3>
        <p>Manual and selective through-hole soldering for connectors, transformers, electrolytic caps, and mixed-technology boards requiring both SMT and PTH.</p>
        <div class="cap-specs">
          <div class="cap-spec"><span>Method</span><span>Wave / Selective / Manual</span></div>
          <div class="cap-spec"><span>Lead-Free</span><span>Yes (RoHS Compliant)</span></div>
          <div class="cap-spec"><span>Mixed Technology</span><span>Supported</span></div>
          <div class="cap-spec"><span>Conformal Coating</span><span>Available</span></div>
        </div>
      </div>

      <div class="cap-card">
        <div class="cap-icon">📦</div>
        <h3>Box Build & System Integration</h3>
        <p>Complete system assembly — PCB integration into enclosures, wiring harness assembly, cable management, and full system-level functional test before shipment.</p>
        <div class="cap-specs">
          <div class="cap-spec"><span>Enclosure Types</span><span>Metal, Plastic, DIN-rail</span></div>
          <div class="cap-spec"><span>Wiring Harness</span><span>Custom cable assembly</span></div>
          <div class="cap-spec"><span>System Test</span><span>Included</span></div>
          <div class="cap-spec"><span>Labelling</span><span>CE / RoHS marking</span></div>
        </div>
      </div>

      <div class="cap-card">
        <div class="cap-icon">⚡</div>
        <h3>Rapid Prototyping</h3>
        <p>Fast-turn prototype assembly — from bare boards and components to tested hardware within days. Ideal for design validation, NPI, and investor demos.</p>
        <div class="cap-specs">
          <div class="cap-spec"><span>Min Order Qty</span><span>1 piece</span></div>
          <div class="cap-spec"><span>Standard Turnaround</span><span>3–5 working days</span></div>
          <div class="cap-spec"><span>Express Turnaround</span><span>24–48 hours</span></div>
          <div class="cap-spec"><span>BOM Sourcing</span><span>Supported</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Ready to Start Production?</h2>
      <p>Send us your Gerber files, BOM, and quantity — we'll get back with a quote within 24 hours.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Request EMS Quote</a>
        <a href="testing.php" class="btn btn-white">Testing Services →</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
