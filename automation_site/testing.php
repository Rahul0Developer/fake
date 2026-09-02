<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// testing.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PCB & Electronics Testing Services — ICT, FCT, ATE, Flying Probe by Nikoji Technologies.">
  <title>Testing & Quality — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/testing.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Testing & Quality — Nikoji Technologies">
  <meta property="og:description" content="PCB & Electronics Testing Services — ICT, FCT, ATE, Flying Probe by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/testing.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Testing & Quality — Nikoji Technologies">
  <meta name="twitter:description" content="PCB & Electronics Testing Services — ICT, FCT, ATE, Flying Probe by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Testing & Quality Assurance",
    "name": "Testing & Quality Assurance",
    "description": "In-circuit testing (ICT), functional circuit testing (FCT), automated test equipment (ATE) and flying probe testing for boards and assemblies.",
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
    .testing-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:24px; }
    .test-card {
      background:var(--card-bg); border:1px solid var(--border); border-radius:var(--radius-lg);
      overflow:hidden; transition:all 0.25s;
    }
    .test-card:hover { box-shadow:var(--shadow-lg); transform:translateY(-4px); border-color:rgba(0,107,79,0.25); }
    .test-card-header { padding:28px 28px 0; display:flex; gap:16px; align-items:flex-start; }
    .test-badge {
      font-family:'Poppins',sans-serif; font-size:1.2rem; font-weight:800;
      color:var(--green); background:rgba(0,107,79,0.08);
      width:56px; height:56px; border-radius:14px;
      display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .test-card-body { padding:16px 28px 28px; }
    .test-card-body h3 { font-family:'Poppins',sans-serif; font-size:1.05rem; font-weight:700; color:var(--text); margin-bottom:8px; }
    .test-card-body p  { font-size:0.875rem; color:var(--text-3); line-height:1.75; margin-bottom:14px; }
    .test-list { display:flex; flex-direction:column; gap:7px; }
    .test-list li { font-size:0.82rem; color:var(--text-2); display:flex; align-items:center; gap:8px; }
    .test-list li::before { content:'→'; color:var(--green); font-weight:700; }
    .checklist { display:flex; flex-direction:column; gap:12px; }
    .check-item {
      display:flex; align-items:center; gap:12px; padding:14px 18px;
      background:var(--card-bg); border:1px solid var(--border); border-radius:10px;
      font-size:0.875rem; color:var(--text);
    }
    .check-icon { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.8rem; }
    .check-icon.pass { background:rgba(0,107,79,0.12); color:var(--green); }
    .check-icon.warn { background:rgba(255,215,0,0.15); color:#9a7e00; }
    @media(max-width:768px){ .testing-grid{grid-template-columns:1fr;} }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Testing & Quality</div>
    <h1>Electronics Testing,<br>Before It Ships</h1>
    <p>ICT, FCT, ATE, and flying probe testing that catches defects on the bench — not after the product is in the field.</p>
  </div>
</div>

<!-- Testing Types -->
<section class="section">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:48px">
      <div class="section-label">Testing Methods</div>
      <h2 class="section-title">Proven <em>Testing</em> Approaches</h2>
      <p class="section-sub">We select the right test strategy for your volume, complexity, and budget — from rapid prototypes to mass production.</p>
    </div>

    <div class="testing-grid" data-stagger>

      <div class="test-card">
        <div class="test-card-header">
          <div class="test-badge">ICT</div>
          <div>
            <span class="tag" style="margin-bottom:6px;display:inline-block">In-Circuit Test</span>
          </div>
        </div>
        <div class="test-card-body">
          <h3>In-Circuit Testing (ICT)</h3>
          <p>Bed-of-nails fixture-based testing that verifies component placement, value, orientation, and basic functionality of individual circuits — ideal for medium to high volume production.</p>
          <ul class="test-list">
            <li>Component value & orientation verification</li>
            <li>Short & open circuit detection</li>
            <li>Power rail validation</li>
            <li>Programmable test sequences</li>
            <li>High coverage at speed</li>
          </ul>
        </div>
      </div>

      <div class="test-card">
        <div class="test-card-header">
          <div class="test-badge">FCT</div>
          <div><span class="tag">Functional Test</span></div>
        </div>
        <div class="test-card-body">
          <h3>Functional Circuit Testing (FCT)</h3>
          <p>End-to-end functional verification that tests the assembled board as it would operate in the final application — simulating real-world inputs and validating outputs against specification.</p>
          <ul class="test-list">
            <li>Full functional operation testing</li>
            <li>Custom test fixture design</li>
            <li>Communication protocol validation</li>
            <li>I/O response & timing checks</li>
            <li>Production parametric logging</li>
          </ul>
        </div>
      </div>

      <div class="test-card">
        <div class="test-card-header">
          <div class="test-badge">ATE</div>
          <div><span class="tag">Automated Test</span></div>
        </div>
        <div class="test-card-body">
          <h3>Automated Test Equipment (ATE)</h3>
          <p>Software-driven automated test systems that deliver high throughput, consistent results, and detailed traceability logs — reducing human error and test cycle time significantly.</p>
          <ul class="test-list">
            <li>PC-controlled test automation</li>
            <li>NI TestStand / LabVIEW integration</li>
            <li>Statistical process control data</li>
            <li>Traceability reports per board</li>
            <li>Pass/fail logging with timestamps</li>
          </ul>
        </div>
      </div>

      <div class="test-card">
        <div class="test-card-header">
          <div class="test-badge" style="font-size:0.85rem">FPT</div>
          <div><span class="tag">Flying Probe</span></div>
        </div>
        <div class="test-card-body">
          <h3>Flying Probe Testing (FPT)</h3>
          <p>Fixtureless electrical testing using robotic probe heads — perfect for prototype quantities, low volume, and boards where ICT fixture costs aren't justified.</p>
          <ul class="test-list">
            <li>No fixture cost — fast setup</li>
            <li>Suitable for prototypes & NPI</li>
            <li>Access to fine-pitch components</li>
            <li>Both-side probing capability</li>
            <li>Short turnaround from Gerber data</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- QA Process -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:start">
      <div class="reveal-left">
        <div class="section-label">Quality Assurance</div>
        <h2 class="section-title">Our <em>QA</em> Checklist</h2>
        <p style="font-size:0.9rem;color:var(--text-3);margin-bottom:28px;line-height:1.75">Every board goes through a structured quality gate before leaving our facility.</p>
        <div class="checklist">
          <?php $checks=[['pass','Visual Inspection (AOI)','Automated optical inspection for solder defects, missing parts'],['pass','X-Ray Inspection','Hidden joint verification for BGA and QFN packages'],['pass','ICT / FCT','Electrical and functional validation'],['pass','Burn-In Test','Stress testing at elevated temperature for reliability'],['warn','Environmental Stress','Vibration and thermal cycle testing (project-specific)'],['pass','Final QC Sign-off','Documented inspection with serialized traceability report']]; ?>
          <?php foreach($checks as [$type,$title,$desc]): ?>
          <div class="check-item">
            <div class="check-icon <?=$type?>"><?=$type==='pass'?'✓':'!'?></div>
            <div>
              <strong style="display:block;font-size:0.875rem"><?=$title?></strong>
              <span style="font-size:0.78rem;color:var(--text-3)"><?=$desc?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- SVG: Test Machine Visual -->
      <div class="reveal-right" aria-hidden="true">
        <svg viewBox="0 0 360 380" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:360px;margin:0 auto;display:block">
          <!-- Machine body -->
          <rect x="40" y="60" width="280" height="240" rx="16" fill="var(--green)" opacity="0.08" stroke="rgba(0,107,79,0.2)" stroke-width="1.5"/>
          <!-- Screen -->
          <rect x="60" y="80" width="240" height="140" rx="8" fill="#0a1f16"/>
          <!-- Waveform on screen -->
          <polyline points="70,170 90,140 110,190 130,130 150,175 170,120 190,165 210,135 230,170 250,140 270,165 290,145" stroke="#00d4aa" stroke-width="2" fill="none">
            <animate attributeName="points" values="70,170 90,140 110,190 130,130 150,175 170,120 190,165 210,135 230,170 250,140 270,165 290,145;70,155 90,175 110,145 130,180 150,155 170,175 190,150 210,170 230,155 250,175 270,150 290,165;70,170 90,140 110,190 130,130 150,175 170,120 190,165 210,135 230,170 250,140 270,165 290,145" dur="2.5s" repeatCount="indefinite"/>
          </polyline>
          <line x1="70" y1="155" x2="290" y2="155" stroke="rgba(255,215,0,0.2)" stroke-width="1" stroke-dasharray="4,4"/>
          <!-- Status text -->
          <text x="76" y="100" fill="rgba(255,255,255,0.4)" font-size="9" font-family="monospace">NIKOJI TEST SYSTEM v2.1</text>
          <circle cx="282" cy="96" r="5" fill="#00d4aa"><animate attributeName="opacity" values="1;0.2;1" dur="1s" repeatCount="indefinite"/></circle>
          <!-- Probe arms -->
          <line x1="120" y1="230" x2="120" y2="280" stroke="var(--green)" stroke-width="3" stroke-linecap="round">
            <animate attributeName="y2" values="280;260;280" dur="2s" repeatCount="indefinite"/>
          </line>
          <circle cx="120" cy="282" r="6" fill="var(--gold)">
            <animate attributeName="cy" values="282;262;282" dur="2s" repeatCount="indefinite"/>
          </circle>
          <line x1="240" y1="230" x2="240" y2="280" stroke="var(--green)" stroke-width="3" stroke-linecap="round">
            <animate attributeName="y2" values="280;265;280" dur="2s" begin="0.5s" repeatCount="indefinite"/>
          </line>
          <circle cx="240" cy="282" r="6" fill="var(--gold)">
            <animate attributeName="cy" values="282;267;282" dur="2s" begin="0.5s" repeatCount="indefinite"/>
          </circle>
          <!-- PCB under test -->
          <rect x="80" y="295" width="200" height="50" rx="6" fill="#0d2a1e" stroke="rgba(0,107,79,0.4)" stroke-width="1.5"/>
          <?php for($i=0;$i<5;$i++): ?>
          <rect x="<?=98+$i*36?>" y="307" width="18" height="12" rx="3" fill="rgba(0,107,79,0.4)"/>
          <?php endfor; ?>
          <!-- Control buttons -->
          <rect x="60" y="320" width="14" height="14" rx="3" fill="var(--green)" opacity="0.8"/>
          <rect x="307" y="320" width="14" height="14" rx="3" fill="var(--gold)" opacity="0.8"/>
          <text x="70" y="360" fill="var(--text-3)" font-size="10" text-anchor="middle" font-family="inherit">START</text>
          <text x="314" y="360" fill="var(--text-3)" font-size="10" text-anchor="middle" font-family="inherit">STOP</text>
        </svg>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Need Reliable PCB Testing?</h2>
      <p>Tell us about your boards, volumes, and quality targets — we'll recommend the right test strategy.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Get Testing Quote</a>
        <a href="ems.php" class="btn btn-white">EMS Services →</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
