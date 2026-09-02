<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// innovation.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Innovation Lab — Discovery, Ideation, Prototyping & Deployment by Nikoji Technologies.">
  <title>Innovation Lab — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/innovation.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Innovation Lab — Nikoji Technologies">
  <meta property="og:description" content="Innovation Lab — Discovery, Ideation, Prototyping & Deployment by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/innovation.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Innovation Lab — Nikoji Technologies">
  <meta name="twitter:description" content="Innovation Lab — Discovery, Ideation, Prototyping & Deployment by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Innovation Lab & R&D",
    "name": "Innovation Lab & R&D",
    "description": "Custom R&D, prototype development and turnkey engineering for new industrial products and processes.",
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
    .roadmap {
      position: relative;
      padding-left: 48px;
    }
    .roadmap::before {
      content: '';
      position: absolute; left: 16px; top: 0; bottom: 0;
      width: 2px; background: linear-gradient(to bottom, var(--green), var(--gold));
      border-radius: 2px;
    }
    .roadmap-item {
      position: relative; margin-bottom: 48px;
    }
    .roadmap-item:last-child { margin-bottom: 0; }
    .roadmap-dot {
      position: absolute; left: -40px; top: 4px;
      width: 20px; height: 20px; border-radius: 50%;
      background: var(--green); border: 3px solid var(--bg);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.6rem; color: #fff; font-weight: 700;
      box-shadow: 0 0 0 3px rgba(0,107,79,0.2);
    }
    .roadmap-dot.gold { background: var(--gold); }
    .roadmap-card {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: var(--radius-lg); padding: 28px 32px;
      transition: all 0.25s;
    }
    .roadmap-card:hover { box-shadow: var(--shadow-lg); border-color: rgba(0,107,79,0.25); }
    .roadmap-phase {
      font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--green);
      margin-bottom: 8px; display: block;
    }
    .roadmap-card h3 { font-family:'Poppins',sans-serif; font-size:1.1rem; font-weight:700; color:var(--text); margin-bottom:10px; }
    .roadmap-card p  { font-size:0.875rem; color:var(--text-3); line-height:1.75; margin-bottom:14px; }
    .roadmap-tags    { display:flex; flex-wrap:wrap; gap:6px; }
    .innovation-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
    @media(max-width:768px){ .innovation-grid{grid-template-columns:1fr;} }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Innovation Lab</div>
    <h1>From Problem to<br>Production-Ready Solution</h1>
    <p>A structured innovation process that turns your industrial challenges into working, deployable products — guided by engineers who understand both technology and manufacturing.</p>
  </div>
</div>

<!-- Animated diagram -->
<section class="section-sm" style="background:var(--card-bg)">
  <div class="container">
    <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-lg);padding:40px" class="reveal">
      <p style="text-align:center;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--text-3);margin-bottom:28px">Innovation Pipeline</p>
      <svg viewBox="0 0 760 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%">
        <?php
        $phases = [
          [80,  'Discovery',   '🔍'],
          [230, 'Ideation',    '💡'],
          [380, 'Prototype',   '🔧'],
          [530, 'Validation',  '✅'],
          [680, 'Deploy',      '🚀'],
        ];
        foreach($phases as $idx=>[$cx,$label,$icon]):
        ?>
        <circle cx="<?=$cx?>" cy="55" r="36" fill="var(--green)" opacity="<?=0.7+$idx*0.06?>"/>
        <text x="<?=$cx?>" y="48" text-anchor="middle" font-size="16"><?=$icon?></text>
        <text x="<?=$cx?>" y="64" text-anchor="middle" fill="white" font-size="9" font-weight="700" font-family="Poppins,sans-serif"><?=$label?></text>
        <?php if($idx<4): ?>
        <line x1="<?=$cx+38?>" y1="55" x2="<?=$phases[$idx+1][0]-38?>" y2="55" stroke="rgba(0,107,79,0.3)" stroke-width="2" stroke-dasharray="5,3">
          <animate attributeName="stroke-dashoffset" values="16;0" dur="1s" repeatCount="indefinite"/>
        </line>
        <?php endif; ?>
        <?php endforeach; ?>
        <!-- Progress bar -->
        <rect x="44" y="100" width="672" height="6" rx="3" fill="rgba(0,107,79,0.08)"/>
        <rect x="44" y="100" width="672" height="6" rx="3" fill="var(--gold)" opacity="0.7">
          <animate attributeName="width" values="0;672" dur="2.5s" ease="ease-out" fill="freeze"/>
        </rect>
      </svg>
    </div>
  </div>
</section>

<!-- Roadmap -->
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:64px;align-items:start">
      <div class="reveal-left">
        <div class="section-label">Our Process</div>
        <h2 class="section-title">The <em>Innovation</em> Roadmap</h2>
        <p class="section-sub">Four structured phases — each with clear deliverables, timelines, and decision gates so you always know what's happening and what comes next.</p>
      </div>

      <div class="roadmap reveal-right">

        <div class="roadmap-item">
          <div class="roadmap-dot">1</div>
          <div class="roadmap-card">
            <span class="roadmap-phase">Phase 1 — Discovery</span>
            <h3>Understanding the Problem</h3>
            <p>We start by deeply understanding your operational challenge — interviewing stakeholders, observing processes, and documenting constraints. No assumptions, no generic solutions.</p>
            <div class="roadmap-tags">
              <span class="tag">Stakeholder interviews</span>
              <span class="tag">Process audit</span>
              <span class="tag">Feasibility report</span>
              <span class="tag">Deliverable: Problem brief</span>
            </div>
          </div>
        </div>

        <div class="roadmap-item">
          <div class="roadmap-dot gold">2</div>
          <div class="roadmap-card">
            <span class="roadmap-phase">Phase 2 — Ideation</span>
            <h3>Generating & Selecting Solutions</h3>
            <p>Multiple approaches are evaluated — technology options, cost models, risk assessments — and the best-fit solution architecture is selected through a structured decision process.</p>
            <div class="roadmap-tags">
              <span class="tag">Architecture design</span>
              <span class="tag">Technology selection</span>
              <span class="tag">Cost modelling</span>
              <span class="tag">Deliverable: Solution proposal</span>
            </div>
          </div>
        </div>

        <div class="roadmap-item">
          <div class="roadmap-dot">3</div>
          <div class="roadmap-card">
            <span class="roadmap-phase">Phase 3 — Prototype</span>
            <h3>Building & Testing Rapidly</h3>
            <p>A working prototype is built — hardware, firmware, and software integrated — then tested in realistic conditions with your team. Iterations are fast and documented.</p>
            <div class="roadmap-tags">
              <span class="tag">Hardware prototype</span>
              <span class="tag">Firmware development</span>
              <span class="tag">Field testing</span>
              <span class="tag">Deliverable: Working prototype</span>
            </div>
          </div>
        </div>

        <div class="roadmap-item">
          <div class="roadmap-dot gold">4</div>
          <div class="roadmap-card">
            <span class="roadmap-phase">Phase 4 — Implementation</span>
            <h3>Production & Deployment</h3>
            <p>The validated design transitions to production — DFM review, EMS manufacturing, system integration, on-site commissioning, and operator training. You get a fully running system.</p>
            <div class="roadmap-tags">
              <span class="tag">Production-ready design</span>
              <span class="tag">EMS manufacturing</span>
              <span class="tag">Commissioning</span>
              <span class="tag">Deliverable: Live system</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- Innovation Areas -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:48px">
      <div class="section-label">Focus Areas</div>
      <h2 class="section-title">Where We <em>Innovate</em></h2>
    </div>
    <div class="innovation-grid" data-stagger>
      <?php $areas=[['IoT & Industry 4.0','Connecting legacy machines to cloud dashboards, predictive maintenance, and real-time production monitoring systems.'],['Energy Management','Smart metering, load balancing, solar integration, and energy audit tools for industrial and commercial facilities.'],['Robotics & Motion','Custom robotic cell design, collaborative robot integration, and precision motion systems for assembly and inspection.'],['Embedded AI','Edge inference for quality vision, anomaly detection, and predictive control — running on low-power microcontrollers.'],['Wireless Industrial','WirelessHART, LoRaWAN, and private 5G solutions for sensor networks in challenging industrial environments.'],['Custom Instrumentation','Bespoke sensors, data loggers, and measurement systems where off-the-shelf instruments don\'t meet your requirements.']]; ?>
      <?php foreach($areas as [$t,$d]): ?>
      <div class="card"><h3><?=$t?></h3><p><?=$d?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Have an Engineering Challenge?</h2>
      <p>Bring us your toughest problems — we thrive on turning complex industrial challenges into elegant solutions.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Discuss Your Challenge</a>
        <a href="consultancy.php" class="btn btn-white">Consultancy →</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
