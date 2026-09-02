<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// design.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PCB Design, CAD Modelling, Embedded Systems & UI/UX Engineering by Nikoji Technologies.">
  <title>Electronics & Design — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/design.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Electronics & Design — Nikoji Technologies">
  <meta property="og:description" content="PCB Design, CAD Modelling, Embedded Systems & UI/UX Engineering by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/design.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Electronics & Design — Nikoji Technologies">
  <meta name="twitter:description" content="PCB Design, CAD Modelling, Embedded Systems & UI/UX Engineering by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "PCB & Electronics Design",
    "name": "PCB & Electronics Design",
    "description": "PCB schematic and layout design, embedded systems firmware, CAD mechanical modelling and industrial UI/UX design.",
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
    .pcb-svg-wrap { background: #0a1a12; border-radius: var(--radius-lg); padding: 32px; overflow: hidden; }
    .design-tabs { display:flex; gap:8px; margin-bottom:32px; flex-wrap:wrap; }
    .design-tab {
      padding:9px 20px; border-radius:8px; border:1.5px solid var(--border);
      font-size:0.85rem; font-weight:600; color:var(--text-2); cursor:pointer;
      transition:all 0.2s; background:var(--card-bg);
    }
    .design-tab.active { background:var(--green); color:#fff; border-color:var(--green); }
    .design-tab:hover:not(.active) { border-color:rgba(0,107,79,0.4); color:var(--green); }
    .tab-panel { display:none; }
    .tab-panel.active { display:block; }
    .gallery-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
    .gallery-item {
      background:var(--card-bg); border:1px solid var(--border); border-radius:var(--radius);
      overflow:hidden; transition:all 0.25s;
    }
    .gallery-item:hover { box-shadow:var(--shadow-lg); transform:translateY(-3px); }
    .gallery-thumb {
      height:160px; background:linear-gradient(135deg, rgba(0,107,79,0.08), rgba(0,107,79,0.02));
      display:flex; align-items:center; justify-content:center;
    }
    .gallery-thumb svg { width:64px; height:64px; color:var(--green); opacity:0.4; }
    .gallery-caption { padding:16px; }
    .gallery-caption h4 { font-size:0.9rem; font-weight:600; color:var(--text); margin-bottom:4px; }
    .gallery-caption p  { font-size:0.78rem; color:var(--text-3); }
    @media(max-width:768px){ .gallery-grid{grid-template-columns:1fr 1fr;} }
    @media(max-width:480px){ .gallery-grid{grid-template-columns:1fr;} }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Electronics & Design</div>
    <h1>Precision Design from<br>Circuit to Chassis</h1>
    <p>PCB layout, embedded firmware, mechanical CAD, and interface design — we bring your product from schematic to production-ready hardware.</p>
  </div>
</div>

<!-- PCB Animation Section -->
<section class="section-sm">
  <div class="container">
    <div class="pcb-svg-wrap reveal">
      <p style="text-align:center;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:24px">PCB Trace Routing — Live Preview</p>
      <svg viewBox="0 0 800 220" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%">
        <!-- PCB board -->
        <rect x="20" y="10" width="760" height="200" rx="8" fill="#0d2a1e" stroke="#1a4030" stroke-width="1.5"/>
        <!-- Grid dots -->
        <?php for($r=0;$r<6;$r++) for($c=0;$c<20;$c++): ?>
        <circle cx="<?=60+$c*36?>" cy="<?=30+$r*32?>" r="1.5" fill="rgba(0,107,79,0.25)"/>
        <?php endfor; ?>
        <!-- Traces -->
        <path d="M 60 110 L 120 110 L 120 60 L 240 60" stroke="#FFD700" stroke-width="2.5" stroke-linecap="round">
          <animate attributeName="stroke-dasharray" values="0,500;500,0" dur="2.5s" repeatCount="indefinite"/>
        </path>
        <path d="M 60 130 L 180 130 L 180 160 L 360 160" stroke="#00d4aa" stroke-width="2" stroke-linecap="round">
          <animate attributeName="stroke-dasharray" values="0,500;500,0" dur="3s" begin="0.5s" repeatCount="indefinite"/>
        </path>
        <path d="M 240 60 L 400 60 L 400 110 L 560 110" stroke="#FFD700" stroke-width="2.5" stroke-linecap="round">
          <animate attributeName="stroke-dasharray" values="0,500;500,0" dur="2.8s" begin="0.3s" repeatCount="indefinite"/>
        </path>
        <path d="M 360 160 L 560 160 L 560 110" stroke="#00d4aa" stroke-width="2" stroke-linecap="round">
          <animate attributeName="stroke-dasharray" values="0,500;500,0" dur="3.2s" begin="0.8s" repeatCount="indefinite"/>
        </path>
        <path d="M 560 110 L 700 110 L 700 80 L 740 80" stroke="#FF6B35" stroke-width="2" stroke-linecap="round">
          <animate attributeName="stroke-dasharray" values="0,300;300,0" dur="2s" begin="1s" repeatCount="indefinite"/>
        </path>
        <!-- ICs -->
        <rect x="220" y="44" width="40" height="36" rx="4" fill="#1a3a28" stroke="rgba(0,107,79,0.6)" stroke-width="1.5"/>
        <text x="226" y="67" fill="#FFD700" font-size="9" font-family="monospace">MCU</text>
        <rect x="380" y="90" width="44" height="40" rx="4" fill="#1a3a28" stroke="rgba(0,107,79,0.6)" stroke-width="1.5"/>
        <text x="384" y="115" fill="#00d4aa" font-size="9" font-family="monospace">PWR</text>
        <rect x="540" y="90" width="44" height="48" rx="4" fill="#1a3a28" stroke="rgba(0,107,79,0.6)" stroke-width="1.5"/>
        <text x="546" y="118" fill="#FFD700" font-size="9" font-family="monospace">BLE</text>
        <!-- Connectors -->
        <?php for($p=0;$p<4;$p++): ?>
        <rect x="<?=42?>" y="<?=50+$p*22?>" width="12" height="16" rx="2" fill="#2a4a38" stroke="rgba(0,107,79,0.5)" stroke-width="1"/>
        <rect x="<?=742?>" y="<?=64+$p*18?>" width="12" height="16" rx="2" fill="#2a4a38" stroke="rgba(0,107,79,0.5)" stroke-width="1"/>
        <?php endfor; ?>
        <!-- Caps/Resistors -->
        <?php $comps=[[140,100],[310,55],[490,105],[640,75]]; ?>
        <?php foreach($comps as [$cx,$cy]): ?>
        <rect x="<?=$cx-8?>" y="<?=$cy-5?>" width="16" height="10" rx="2" fill="#2a5a38" stroke="rgba(0,212,170,0.4)" stroke-width="1"/>
        <?php endforeach; ?>
      </svg>
    </div>
  </div>
</section>

<!-- Tabbed Services -->
<section class="section">
  <div class="container">
    <div class="section-label">Design Capabilities</div>
    <h2 class="section-title" style="margin-bottom:32px">What We <em>Design</em></h2>

    <div class="design-tabs" role="tablist">
      <button class="design-tab active" data-tab="pcb" role="tab">PCB Design</button>
      <button class="design-tab" data-tab="embedded" role="tab">Embedded Systems</button>
      <button class="design-tab" data-tab="cad" role="tab">CAD / Mechanical</button>
      <button class="design-tab" data-tab="ui" role="tab">UI/UX</button>
    </div>

    <div class="tab-panel active" id="tab-pcb">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <h3 style="font-family:'Poppins',sans-serif;font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:12px">Professional PCB Design</h3>
          <p style="font-size:0.9rem;color:var(--text-3);line-height:1.8;margin-bottom:20px">From single-layer through to 12-layer high-speed boards — we handle schematic capture, layout, DFM review, and Gerber generation for any complexity level.</p>
          <div style="display:flex;flex-direction:column;gap:10px">
            <?php $pcb=['Schematic capture (KiCad, Altium, Eagle)','Multi-layer PCB layout & routing','High-speed signal integrity','RF / antenna design','Impedance-controlled traces','DFM & DFA review','Gerber & BOM preparation']; ?>
            <?php foreach($pcb as $item): ?>
            <div style="display:flex;align-items:center;gap:10px;font-size:0.875rem;color:var(--text-2)">
              <span style="width:20px;height:20px;background:rgba(0,107,79,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--green);font-size:0.7rem;flex-shrink:0">✓</span>
              <?= $item ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="gallery-grid" style="grid-template-columns:1fr 1fr">
          <?php $boards=[['4-Layer MCU Board','STM32 + BLE + USB-C'],['Power Management','Buck/Boost converters'],['Sensor Array PCB','Multi-axis IMU design'],['Industrial I/O','24V DIN-rail module']]; ?>
          <?php foreach($boards as [$n,$d]): ?>
          <div class="gallery-item">
            <div class="gallery-thumb"><svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="8" y="8" width="48" height="48" rx="4"/><rect x="16" y="16" width="12" height="10" rx="2"/><rect x="36" y="16" width="12" height="10" rx="2"/><line x1="28" y1="21" x2="36" y2="21"/><line x1="8" y1="32" x2="16" y2="32"/><line x1="48" y1="32" x2="56" y2="32"/></svg></div>
            <div class="gallery-caption"><h4><?=$n?></h4><p><?=$d?></p></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="tab-panel" id="tab-embedded">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px">
        <div>
          <h3 style="font-family:'Poppins',sans-serif;font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:12px">Embedded Firmware Development</h3>
          <p style="font-size:0.9rem;color:var(--text-3);line-height:1.8;margin-bottom:20px">Bare-metal and RTOS-based firmware for microcontrollers and SoCs — from simple sensor nodes to complex real-time control systems with communication stacks.</p>
          <div style="display:flex;flex-direction:column;gap:10px">
            <?php $emb=['STM32, ESP32, nRF52, RP2040','FreeRTOS / Zephyr RTOS','UART, SPI, I2C, CAN, RS-485','Wi-Fi, BLE, LoRa, MQTT','Bootloaders & OTA updates','Motor control (BLDC, stepper)','Low-power optimization']; foreach($emb as $item): ?>
            <div style="display:flex;align-items:center;gap:10px;font-size:0.875rem;color:var(--text-2)"><span style="width:20px;height:20px;background:rgba(0,107,79,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--green);font-size:0.7rem;flex-shrink:0">✓</span><?=$item?></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:var(--radius);padding:28px">
          <h4 style="font-size:0.85rem;font-weight:700;color:var(--text-3);margin-bottom:16px;text-transform:uppercase;letter-spacing:0.08em">Platform Support</h4>
          <?php $plats=[['STM32 Family','Nucleo/Discovery + custom'],['ESP32 / ESP8266','Wi-Fi & BLE IoT'],['nRF52840','BLE 5.0 + USB'],['Raspberry Pi','Linux embedded'],['Arduino','Rapid prototyping']]; ?>
          <?php foreach($plats as [$p,$d]): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border)">
            <span style="font-size:0.875rem;font-weight:600;color:var(--text)"><?=$p?></span>
            <span style="font-size:0.78rem;color:var(--text-3)"><?=$d?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="tab-panel" id="tab-cad">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px">
        <div>
          <h3 style="font-family:'Poppins',sans-serif;font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:12px">Mechanical CAD & Enclosure Design</h3>
          <p style="font-size:0.9rem;color:var(--text-3);line-height:1.8;margin-bottom:20px">3D modelling, enclosure design, and DXF-ready drawings for CNC machining, sheet metal fabrication, and 3D printing — all optimized for manufacturability.</p>
          <div style="display:flex;flex-direction:column;gap:10px">
            <?php $cad=['SolidWorks & Fusion 360','DIN-rail enclosure design','IP65/IP67 enclosure design','CNC & sheet metal DXFs','Thermal simulation','Assembly drawings & BOM','3D printing prototypes']; foreach($cad as $i): ?>
            <div style="display:flex;align-items:center;gap:10px;font-size:0.875rem;color:var(--text-2)"><span style="width:20px;height:20px;background:rgba(0,107,79,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--green);font-size:0.7rem;flex-shrink:0">✓</span><?=$i?></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div style="text-align:center">
          <svg viewBox="0 0 300 260" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:280px;margin:0 auto">
            <!-- Isometric box -->
            <path d="M150 40 L260 100 L260 200 L150 260 L40 200 L40 100 Z" fill="rgba(0,107,79,0.06)" stroke="rgba(0,107,79,0.3)" stroke-width="1.5"/>
            <path d="M150 40 L260 100 L150 160 L40 100 Z" fill="rgba(0,107,79,0.12)" stroke="rgba(0,107,79,0.4)" stroke-width="1.5"/>
            <path d="M150 160 L260 100 L260 200 L150 260 Z" fill="rgba(0,107,79,0.08)" stroke="rgba(0,107,79,0.3)" stroke-width="1.5"/>
            <path d="M150 160 L40 100 L40 200 L150 260 Z" fill="rgba(0,107,79,0.05)" stroke="rgba(0,107,79,0.25)" stroke-width="1.5"/>
            <!-- Dimension lines -->
            <line x1="40" y1="220" x2="260" y2="220" stroke="var(--gold)" stroke-width="1" stroke-dasharray="4,3" opacity="0.6"/>
            <line x1="280" y1="100" x2="280" y2="200" stroke="var(--gold)" stroke-width="1" stroke-dasharray="4,3" opacity="0.6"/>
            <!-- Labels -->
            <text x="140" y="235" fill="rgba(255,215,0,0.7)" font-size="10" text-anchor="middle" font-family="monospace">220mm</text>
            <text x="294" y="155" fill="rgba(255,215,0,0.7)" font-size="10" text-anchor="middle" font-family="monospace" transform="rotate(90,294,155)">100mm</text>
          </svg>
          <p style="font-size:0.8rem;color:var(--text-3);margin-top:12px">Custom enclosure — isometric CAD view</p>
        </div>
      </div>
    </div>

    <div class="tab-panel" id="tab-ui">
      <div>
        <h3 style="font-family:'Poppins',sans-serif;font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:12px">Industrial UI/UX Design</h3>
        <p style="font-size:0.9rem;color:var(--text-3);line-height:1.8;margin-bottom:28px">HMI screens, operator dashboards, and web interfaces designed for the industrial context — clear, intuitive, and optimized for use with gloves or in bright-light environments.</p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
          <?php $ui=[['SCADA Screen Design','Dashboard layouts for process monitoring with alarm management and trend views.'],['HMI Faceplate Libraries','Reusable graphic objects for pumps, valves, tanks, conveyors, and actuators.'],['Web-Based Dashboards','Responsive operator interfaces accessible from tablets, PCs, and mobile devices.']]; ?>
          <?php foreach($ui as [$t,$d]): ?>
          <div class="card"><h3><?=$t?></h3><p><?=$d?></p></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Have a Design Project?</h2>
      <p>Share your requirements — we'll review your brief and provide a detailed proposal within 48 hours.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Send Your Brief</a>
        <a href="testing.php" class="btn btn-white">Testing Services →</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
<script>
document.querySelectorAll('.design-tab').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.design-tab').forEach(b=>b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-'+btn.dataset.tab).classList.add('active');
  });
});
</script>
</body>
</html>
