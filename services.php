<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// services.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="All Engineering Services — PLC Repair, SCADA, Software Development, Diagnostics & More by Nikoji Technologies.">
  <title>All Services — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/services.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="All Services — Nikoji Technologies">
  <meta property="og:description" content="All Engineering Services — PLC Repair, SCADA, Software Development, Diagnostics & More by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/services.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="All Services — Nikoji Technologies">
  <meta name="twitter:description" content="All Engineering Services — PLC Repair, SCADA, Software Development, Diagnostics & More by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://nikojitechnologies.com/index.php"},
      {"@type": "ListItem", "position": 2, "name": "Services", "item": "https://nikojitechnologies.com/services.php"}
    ]
  }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    .services-layout { display:grid; grid-template-columns:260px 1fr; gap:40px; align-items:start; }
    .services-sidebar {
      position:sticky; top:calc(var(--nav-h) + 24px);
      background:var(--card-bg); border:1px solid var(--border);
      border-radius:var(--radius-lg); overflow:hidden;
    }
    .sidebar-header { background:var(--green); padding:20px 20px 16px; }
    .sidebar-header h3 { font-family:'Poppins',sans-serif; font-size:0.9rem; font-weight:700; color:#fff; }
    .sidebar-nav-links { padding:8px; }
    .sidebar-nav-links a {
      display:flex; align-items:center; gap:10px; padding:10px 12px;
      border-radius:8px; font-size:0.85rem; font-weight:500;
      color:var(--text-2); transition:all 0.2s; text-decoration:none;
    }
    .sidebar-nav-links a:hover,.sidebar-nav-links a.active { background:rgba(0,107,79,0.08); color:var(--green); }
    .sidebar-nav-links a span { font-size:1rem; }
    .service-section { margin-bottom:40px; scroll-margin-top:calc(var(--nav-h) + 32px); }
    .service-section-header {
      display:flex; align-items:center; gap:16px; margin-bottom:24px;
      padding-bottom:16px; border-bottom:2px solid var(--border);
    }
    .service-section-header .num {
      width:40px; height:40px; border-radius:10px;
      background:var(--green); color:#fff;
      display:flex; align-items:center; justify-content:center;
      font-family:'Poppins',sans-serif; font-weight:700; font-size:0.9rem;
      flex-shrink:0;
    }
    .service-section-header h2 { font-family:'Poppins',sans-serif; font-size:1.2rem; font-weight:700; color:var(--text); }
    .service-section-header a { margin-left:auto; font-size:0.8rem; color:var(--green); font-weight:600; white-space:nowrap; }
    .sub-services { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .sub-service {
      background:var(--card-bg); border:1px solid var(--border);
      border-radius:var(--radius); padding:20px; cursor:pointer;
      transition:all 0.25s;
    }
    .sub-service:hover { border-color:rgba(0,107,79,0.3); box-shadow:var(--shadow); }
    .sub-service h4 { font-size:0.9rem; font-weight:600; color:var(--text); margin-bottom:6px; }
    .sub-service p  { font-size:0.8rem; color:var(--text-3); line-height:1.65; }
    @media(max-width:900px){ .services-layout{grid-template-columns:1fr;} .services-sidebar{position:static;} }
    @media(max-width:600px){ .sub-services{grid-template-columns:1fr;} }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">All Services</div>
    <h1>Everything We Offer —<br>Under One Roof</h1>
    <p>A complete reference of all Nikoji Technologies engineering services — from field automation to software development and everything in between.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="services-layout">

      <!-- Sidebar -->
      <aside class="services-sidebar">
        <div class="sidebar-header"><h3>Service Categories</h3></div>
        <div class="sidebar-nav-links">
          <a href="#automation" class="active"><span>⚙️</span> Automation</a>
          <a href="#design"><span>🔧</span> Design</a>
          <a href="#testing"><span>🔬</span> Testing</a>
          <a href="#ems"><span>📦</span> EMS</a>
          <a href="#consultancy"><span>🤝</span> Consultancy</a>
          <a href="#innovation"><span>🚀</span> Innovation Lab</a>
          <a href="#software"><span>💻</span> Software Dev</a>
          <a href="#repair"><span>🛠</span> Repair & Support</a>
        </div>
      </aside>

      <!-- Main Content -->
      <div>

        <div class="service-section reveal" id="automation">
          <div class="service-section-header">
            <div class="num">1</div>
            <h2>Industrial Automation</h2>
            <a href="automation.php">Full page →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>PLC Programming</h4><p>Ladder, STL, FBD, and SFC programming for all major PLC platforms including Siemens, Allen-Bradley, Mitsubishi, and Delta.</p></div>
            <div class="sub-service"><h4>SCADA Systems</h4><p>Design and deployment of plant-wide supervisory systems with real-time monitoring, historian, and alarm management.</p></div>
            <div class="sub-service"><h4>HMI Development</h4><p>Custom operator interfaces — from touchscreen panels to web-based multi-screen SCADA front-ends.</p></div>
            <div class="sub-service"><h4>VFD & Drives</h4><p>Variable frequency drive selection, wiring, parameterisation, and commissioning for all motor control applications.</p></div>
            <div class="sub-service"><h4>Servo Systems</h4><p>High-precision servo drive and motion controller configuration for CNC, robotics, and precision positioning applications.</p></div>
            <div class="sub-service"><h4>Control Panels</h4><p>Custom MCC and control panel design, fabrication, wiring, and FAT — to IEC standards with full documentation package.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="design">
          <div class="service-section-header">
            <div class="num">2</div>
            <h2>Electronics & Design</h2>
            <a href="design.php">Full page →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>PCB Design</h4><p>Schematic capture and multi-layer PCB layout using KiCad, Altium, and Eagle — from simple 2-layer to complex 12-layer high-speed boards.</p></div>
            <div class="sub-service"><h4>Embedded Systems</h4><p>Firmware development for STM32, ESP32, nRF52, and Arduino platforms — RTOS, protocols, OTA, and motor control.</p></div>
            <div class="sub-service"><h4>CAD / Mechanical</h4><p>3D enclosure design, sheet metal DXFs, thermal modelling, and assembly drawings using SolidWorks and Fusion 360.</p></div>
            <div class="sub-service"><h4>UI/UX Design</h4><p>Industrial HMI faceplate libraries, SCADA screen design, and web dashboard interfaces for operator use.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="testing">
          <div class="service-section-header">
            <div class="num">3</div>
            <h2>Testing & Quality</h2>
            <a href="testing.php">Full page →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>ICT — In-Circuit Test</h4><p>Bed-of-nails fixture testing for component verification, short/open detection, and power rail validation at production speed.</p></div>
            <div class="sub-service"><h4>FCT — Functional Test</h4><p>End-to-end functional board testing simulating real application conditions with custom fixture design.</p></div>
            <div class="sub-service"><h4>ATE — Automated Test</h4><p>Software-driven automated test systems using NI TestStand/LabVIEW with full traceability reporting.</p></div>
            <div class="sub-service"><h4>Flying Probe Test</h4><p>Fixtureless electrical testing for prototypes and low-volume boards — fast setup, no fixture cost.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="ems">
          <div class="service-section-header">
            <div class="num">4</div>
            <h2>EMS & Manufacturing</h2>
            <a href="ems.php">Full page →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>SMT Assembly</h4><p>Surface mount assembly with automated pick & place, solder paste printing, and lead-free reflow soldering.</p></div>
            <div class="sub-service"><h4>Through-Hole Assembly</h4><p>Wave, selective, and manual through-hole soldering for mixed-technology and high-reliability boards.</p></div>
            <div class="sub-service"><h4>Box Build</h4><p>Complete system assembly — PCBs into enclosures, wiring harnesses, cable management, and system test.</p></div>
            <div class="sub-service"><h4>Rapid Prototyping</h4><p>1–10 piece prototype assembly with 24–72 hour turnaround. Ideal for NPI, design validation, and investor demos.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="consultancy">
          <div class="service-section-header">
            <div class="num">5</div>
            <h2>Engineering Consultancy</h2>
            <a href="consultancy.php">Full page →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>Automation Feasibility</h4><p>Independent technical and commercial assessment of automation opportunities with ROI modelling and implementation risk evaluation.</p></div>
            <div class="sub-service"><h4>Technology Selection</h4><p>Objective, vendor-neutral guidance on platform selection for PLCs, SCADA, networks, and industrial software.</p></div>
            <div class="sub-service"><h4>Process Optimization</h4><p>Systematic bottleneck analysis and improvement planning for manufacturing and utility processes.</p></div>
            <div class="sub-service"><h4>Energy Audit</h4><p>Industrial energy consumption analysis with an actionable reduction plan and investment prioritisation.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="innovation">
          <div class="service-section-header">
            <div class="num">6</div>
            <h2>Innovation Lab & R&D</h2>
            <a href="innovation.php">Full page →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>Concept & Feasibility</h4><p>Turning an early idea or field problem into a scoped technical brief with a realistic feasibility and cost assessment.</p></div>
            <div class="sub-service"><h4>Rapid Prototyping</h4><p>Working proof-of-concept hardware and firmware built fast, so you can test an idea before committing to full development.</p></div>
            <div class="sub-service"><h4>Custom R&D</h4><p>Focused research and development for products or processes that don't fit an off-the-shelf solution.</p></div>
            <div class="sub-service"><h4>Turnkey Engineering</h4><p>Full delivery from prototype to production-ready design, documentation, and handover.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="software">
          <div class="service-section-header">
            <div class="num">7</div>
            <h2>Software Development</h2>
            <a href="contact.php">Enquire →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>SCADA Logic Development</h4><p>Custom scripting, alarm strategies, trending, and historian configuration for WinCC, Ignition, and InTouch platforms.</p></div>
            <div class="sub-service"><h4>Industrial Web Apps</h4><p>Browser-based production dashboards, KPI monitoring, and OEE tracking systems connected to plant data sources.</p></div>
            <div class="sub-service"><h4>Database & Reporting</h4><p>Production data historians, batch tracking systems, and automated shift/daily reports for management.</p></div>
            <div class="sub-service"><h4>Custom Industrial Software</h4><p>Bespoke desktop and embedded applications for machine operators, maintenance teams, and production managers.</p></div>
          </div>
        </div>

        <div class="service-section reveal" id="repair">
          <div class="service-section-header">
            <div class="num">8</div>
            <h2>Repair, Diagnostics & Support</h2>
            <a href="contact.php">Enquire →</a>
          </div>
          <div class="sub-services">
            <div class="sub-service"><h4>PLC Repair & Recovery</h4><p>Hardware diagnosis, program recovery, battery replacement, and I/O module repair for Siemens, Allen-Bradley, Mitsubishi, and Delta PLCs.</p></div>
            <div class="sub-service"><h4>Drive Diagnostics</h4><p>VFD and servo drive fault diagnosis, parameter backup, and repair or replacement coordination.</p></div>
            <div class="sub-service"><h4>Control System Audit</h4><p>Comprehensive health check of existing automation systems with a documented risk register and recommended actions.</p></div>
            <div class="sub-service"><h4>AMC & Support Contracts</h4><p>Annual maintenance contracts providing scheduled preventive maintenance, emergency callout, and remote support for automation systems.</p></div>
          </div>
        </div>

      </div><!-- /main -->
    </div><!-- /layout -->
  </div>
</section>

<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Can't Find What You Need?</h2>
      <p>If your engineering challenge isn't listed here, talk to us anyway — we may still be able to help or point you in the right direction.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Contact Our Engineers</a>
        <a href="tel:+917678334459" class="btn btn-white">+91 7678334459</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
<script>
// Sidebar scrollspy
const sections = document.querySelectorAll('.service-section');
const sideLinks = document.querySelectorAll('.sidebar-nav-links a');
const obs = new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      sideLinks.forEach(l=>l.classList.remove('active'));
      const link = document.querySelector('.sidebar-nav-links a[href="#'+e.target.id+'"]');
      if(link) link.classList.add('active');
    }
  });
},{threshold:0.3});
sections.forEach(s=>obs.observe(s));
</script>
</body>
</html>
