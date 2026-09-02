<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// automation.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Industrial Automation Services — PLC, SCADA, HMI, VFD, Servo Drives & Motor Control Panels by Nikoji Technologies.">
  <title>Industrial Automation — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/automation.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Industrial Automation — Nikoji Technologies">
  <meta property="og:description" content="Industrial Automation Services — PLC, SCADA, HMI, VFD, Servo Drives & Motor Control Panels by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/automation.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Industrial Automation — Nikoji Technologies">
  <meta name="twitter:description" content="Industrial Automation Services — PLC, SCADA, HMI, VFD, Servo Drives & Motor Control Panels by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Industrial Automation",
    "name": "Industrial Automation",
    "description": "PLC, SCADA, HMI programming, VFD integration, servo drives and motor control panel design and commissioning.",
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
    .auto-diagram {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: var(--radius-lg); padding: 40px;
      position: relative; overflow: hidden;
    }
    .flow-nodes {
      display: flex; align-items: center; justify-content: center;
      gap: 0; flex-wrap: wrap;
    }
    .flow-node {
      background: var(--green); color: #fff;
      padding: 16px 22px; border-radius: 10px;
      font-size: 0.82rem; font-weight: 700;
      text-align: center; min-width: 110px;
      position: relative; transition: transform 0.2s;
    }
    .flow-node:hover { transform: translateY(-3px); }
    .flow-node small { display: block; font-size: 0.68rem; font-weight: 400; opacity: 0.8; margin-top: 3px; }
    .flow-node.gold { background: var(--gold); color: #333; }
    .flow-arrow {
      width: 36px; height: 2px; background: var(--border);
      position: relative; flex-shrink: 0;
    }
    .flow-arrow::after {
      content: '▶'; position: absolute; right: -8px; top: 50%;
      transform: translateY(-50%); color: var(--text-3); font-size: 0.65rem;
    }
    .spec-grid {
      display: grid; grid-template-columns: repeat(3,1fr); gap: 20px;
    }
    .spec-item {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 24px;
      transition: all 0.25s;
    }
    .spec-item:hover { border-color: rgba(0,107,79,0.3); transform: translateY(-3px); box-shadow: var(--shadow); }
    .spec-item h4 { font-family:'Poppins',sans-serif; font-size:0.95rem; font-weight:700; color:var(--text); margin-bottom:8px; }
    .spec-item p  { font-size:0.85rem; color:var(--text-3); line-height:1.7; }
    .spec-item .spec-tags { display:flex; flex-wrap:wrap; gap:6px; margin-top:12px; }
    .spec-tag { background:rgba(0,107,79,0.08); color:var(--green); font-size:0.7rem; font-weight:700; padding:3px 9px; border-radius:12px; }
    @media(max-width:768px){ .spec-grid{grid-template-columns:1fr 1fr;} .flow-nodes{flex-direction:column;} .flow-arrow{width:2px;height:24px;} .flow-arrow::after{content:'▼';right:50%;top:auto;bottom:-8px;transform:translateX(50%);} }
    @media(max-width:480px){ .spec-grid{grid-template-columns:1fr;} }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Industrial Automation</div>
    <h1>Smart Automation for<br>Modern Industry</h1>
    <p>From PLC programming to full SCADA deployments — we design and implement automation systems that run reliably in the most demanding industrial environments.</p>
  </div>
</div>

<!-- Flow Diagram -->
<section class="section-sm">
  <div class="container">
    <div class="auto-diagram reveal">
      <p style="text-align:center;font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-3);margin-bottom:28px">Typical Automation Architecture</p>
      <div class="flow-nodes">
        <div class="flow-node">Field Sensors<small>I/O Devices</small></div>
        <div class="flow-arrow"></div>
        <div class="flow-node gold">PLC / RTU<small>Logic Controller</small></div>
        <div class="flow-arrow"></div>
        <div class="flow-node">HMI<small>Operator Panel</small></div>
        <div class="flow-arrow"></div>
        <div class="flow-node">SCADA<small>Supervisory Control</small></div>
        <div class="flow-arrow"></div>
        <div class="flow-node gold">MES / ERP<small>Enterprise Layer</small></div>
      </div>
    </div>
  </div>
</section>

<!-- Services Grid -->
<section class="section">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:48px">
      <div class="section-label">Our Automation Services</div>
      <h2 class="section-title">Complete <em>Automation</em> Solutions</h2>
      <p class="section-sub">We work with leading industrial automation platforms — Siemens, Allen-Bradley, Mitsubishi, Delta, and more.</p>
    </div>
    <div class="spec-grid" data-stagger>

      <div class="spec-item">
        <h4>⚙️ PLC Programming</h4>
        <p>Ladder logic, structured text, and function block diagram programming for Siemens S7, Allen-Bradley ControlLogix, Mitsubishi MELSEC, and Delta PLCs.</p>
        <div class="spec-tags">
          <span class="spec-tag">Siemens S7-1200/1500</span>
          <span class="spec-tag">Allen-Bradley</span>
          <span class="spec-tag">Mitsubishi</span>
          <span class="spec-tag">Delta</span>
        </div>
      </div>

      <div class="spec-item">
        <h4>🖥 SCADA Systems</h4>
        <p>Design and deployment of supervisory control and data acquisition systems using WinCC, iFIX, Ignition, and InTouch — with real-time monitoring and historian.</p>
        <div class="spec-tags">
          <span class="spec-tag">WinCC</span>
          <span class="spec-tag">Ignition</span>
          <span class="spec-tag">iFIX</span>
          <span class="spec-tag">InTouch</span>
        </div>
      </div>

      <div class="spec-item">
        <h4>📟 HMI Development</h4>
        <p>Custom human-machine interface screens designed for ease of use — touchscreen panels, web-based HMIs, and multi-screen operator workstations.</p>
        <div class="spec-tags">
          <span class="spec-tag">Siemens TP/MP</span>
          <span class="spec-tag">Weintek</span>
          <span class="spec-tag">Pro-face</span>
          <span class="spec-tag">Web HMI</span>
        </div>
      </div>

      <div class="spec-item">
        <h4>🔁 VFD Integration</h4>
        <p>Variable frequency drive selection, wiring, parameter configuration, and commissioning for precise motor speed control in pumps, fans, conveyors, and compressors.</p>
        <div class="spec-tags">
          <span class="spec-tag">ABB</span>
          <span class="spec-tag">Danfoss</span>
          <span class="spec-tag">Yaskawa</span>
          <span class="spec-tag">Delta VFD</span>
        </div>
      </div>

      <div class="spec-item">
        <h4>🤖 Servo Drive Systems</h4>
        <p>High-precision servo drive configuration and commissioning for CNC, robotics, pick-and-place, and other applications requiring exact position and velocity control.</p>
        <div class="spec-tags">
          <span class="spec-tag">Siemens Sinamics</span>
          <span class="spec-tag">Panasonic</span>
          <span class="spec-tag">Omron</span>
        </div>
      </div>

      <div class="spec-item">
        <h4>🗄 Motor Control Panels</h4>
        <p>Custom-built MCC panels — DOL, star-delta, soft-starter, and VFD-based — designed, fabricated, and tested to IEC standards with full documentation.</p>
        <div class="spec-tags">
          <span class="spec-tag">DOL Starter</span>
          <span class="spec-tag">Star-Delta</span>
          <span class="spec-tag">Soft Starter</span>
          <span class="spec-tag">IEC Compliant</span>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Process -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center">
      <div class="reveal-left">
        <div class="section-label">Our Process</div>
        <h2 class="section-title">How We <em>Deliver</em></h2>
        <p class="section-sub" style="margin-bottom:36px">A structured, phase-gated approach that eliminates surprises and keeps your project on time.</p>
        <div style="display:flex;flex-direction:column;gap:24px">
          <?php $steps=[['Site Assessment','We visit your facility, document existing systems, and define automation objectives.'],['System Design','Architecture design, hardware selection, and detailed engineering drawings.'],['Panel Fabrication','In-house panel build, wiring, and factory acceptance testing (FAT).'],['Site Installation','On-site installation, cabling, and loop checking.'],['Commissioning','PLC/SCADA go-live, operator training, and handover documentation.']]; ?>
          <?php foreach($steps as $i=>[$t,$d]): ?>
          <div class="process-step">
            <div class="step-num"><?= $i+1 ?></div>
            <div class="step-content">
              <h4><?= $t ?></h4>
              <p><?= $d ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- SVG Animation -->
      <div class="reveal-right" aria-hidden="true">
        <svg viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:400px;margin:0 auto;display:block">
          <rect x="60" y="60" width="300" height="300" rx="20" fill="var(--green)" opacity="0.06"/>
          <rect x="80" y="80" width="260" height="100" rx="12" fill="var(--green)" opacity="0.9"/>
          <rect x="96" y="96" width="228" height="68" rx="6" fill="#0a1f16"/>
          <!-- PLC display -->
          <rect x="108" y="104" width="80" height="52" rx="4" fill="#0d2a1e"/>
          <text x="115" y="122" fill="#00d4aa" font-size="8" font-family="monospace">PLC-S7</text>
          <text x="115" y="134" fill="#FFD700" font-size="7" font-family="monospace">RUN ●</text>
          <text x="115" y="146" fill="rgba(255,255,255,0.5)" font-size="7" font-family="monospace">OK</text>
          <!-- Status LEDs -->
          <circle cx="204" cy="115" r="5" fill="#00d4aa"><animate attributeName="opacity" values="1;0.3;1" dur="1.5s" repeatCount="indefinite"/></circle>
          <circle cx="218" cy="115" r="5" fill="#FFD700"><animate attributeName="opacity" values="0.3;1;0.3" dur="1.8s" repeatCount="indefinite"/></circle>
          <circle cx="232" cy="115" r="5" fill="#ff4444" opacity="0.3"/>
          <!-- Terminals -->
          <?php for($t=0;$t<8;$t++): ?>
          <rect x="<?=204+$t*8?>" y="128" width="6" height="20" rx="2" fill="rgba(255,255,255,0.15)"/>
          <?php endfor; ?>
          <!-- Wiring lines -->
          <line x1="100" y1="200" x2="320" y2="200" stroke="rgba(0,107,79,0.15)" stroke-width="1"/>
          <?php for($i=0;$i<5;$i++): ?>
          <line x1="<?=120+$i*50?>" y1="190" x2="<?=120+$i*50?>" y2="280" stroke="rgba(0,107,79,0.2)" stroke-width="1.5" stroke-dasharray="4,4"/>
          <rect x="<?=108+$i*50?>" y="280" width="24" height="24" rx="4" fill="rgba(0,107,79,0.15)" stroke="var(--green)" stroke-width="1"/>
          <?php endfor; ?>
          <!-- Conveyor at bottom -->
          <rect x="80" y="330" width="260" height="24" rx="8" fill="#1a2a22"/>
          <circle cx="104" cy="342" r="10" fill="#2a3a2a" stroke="rgba(0,107,79,0.3)" stroke-width="2"/>
          <circle cx="316" cy="342" r="10" fill="#2a3a2a" stroke="rgba(0,107,79,0.3)" stroke-width="2"/>
          <rect x="104" y="334" width="212" height="16" rx="0" fill="#1e2e22"/>
          <!-- moving belt item -->
          <rect x="140" y="336" width="32" height="12" rx="3" fill="var(--gold)" opacity="0.7">
            <animateTransform attributeName="transform" type="translate" values="0,0;140,0;0,0" dur="3s" repeatCount="indefinite"/>
          </rect>
        </svg>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Ready to Automate Your Plant?</h2>
      <p>Get a free site assessment and automation feasibility report from our engineers.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Request Free Assessment</a>
        <a href="services.php" class="btn btn-white">All Services</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
