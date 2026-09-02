<?php
require_once __DIR__ . '/config.php';
nikoji_start_session();
// consultancy.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Engineering Consultancy — Assessment, Strategy, Optimization & Deployment by Nikoji Technologies.">
  <title>Consultancy — Nikoji Technologies</title>
  <link rel="canonical" href="https://nikojitechnologies.com/consultancy.php">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Consultancy — Nikoji Technologies">
  <meta property="og:description" content="Engineering Consultancy — Assessment, Strategy, Optimization & Deployment by Nikoji Technologies.">
  <meta property="og:url" content="https://nikojitechnologies.com/consultancy.php">
  <meta property="og:site_name" content="Nikoji Technologies">
  <meta property="og:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Consultancy — Nikoji Technologies">
  <meta name="twitter:description" content="Engineering Consultancy — Assessment, Strategy, Optimization & Deployment by Nikoji Technologies.">
  <meta name="twitter:image" content="https://nikojitechnologies.com/assets/images/logo.jpg">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Engineering Consultancy",
    "name": "Engineering Consultancy",
    "description": "Plant automation assessment, technology roadmapping, process optimisation and compliance engineering consultancy.",
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
    .timeline { position:relative; }
    .timeline::before { content:''; position:absolute; left:50%; top:0; bottom:0; width:2px; background:var(--border); transform:translateX(-50%); }
    .timeline-item { display:grid; grid-template-columns:1fr 60px 1fr; gap:0; margin-bottom:48px; align-items:start; }
    .timeline-item:nth-child(even) .tl-content-left  { order:3; }
    .timeline-item:nth-child(even) .tl-center         { order:2; }
    .timeline-item:nth-child(even) .tl-content-right  { order:1; }
    .tl-center { display:flex; flex-direction:column; align-items:center; padding-top:4px; }
    .tl-dot { width:44px; height:44px; border-radius:50%; background:var(--green); color:#fff; display:flex; align-items:center; justify-content:center; font-family:'Poppins',sans-serif; font-weight:700; font-size:0.9rem; border:3px solid var(--bg); flex-shrink:0; }
    .tl-dot.gold { background:var(--gold); color:#333; }
    .tl-content-left, .tl-content-right {
      background:var(--card-bg); border:1px solid var(--border); border-radius:var(--radius-lg);
      padding:28px; margin:0 20px; transition:all 0.25s;
    }
    .tl-content-left:hover, .tl-content-right:hover { box-shadow:var(--shadow-lg); border-color:rgba(0,107,79,0.25); }
    .tl-content-left h3, .tl-content-right h3 { font-family:'Poppins',sans-serif; font-size:1rem; font-weight:700; color:var(--text); margin-bottom:8px; }
    .tl-content-left p, .tl-content-right p { font-size:0.875rem; color:var(--text-3); line-height:1.75; }
    .tl-empty { background:none; border:none; }
    .offerings-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:24px; }
    @media(max-width:768px){
      .timeline::before{left:20px;}
      .timeline-item{grid-template-columns:40px 1fr;grid-template-rows:auto;}
      .tl-content-left,.tl-content-right{margin:0 0 0 16px;}
      .timeline-item:nth-child(even) .tl-content-left{order:unset;} .timeline-item:nth-child(even) .tl-center{order:unset;} .timeline-item:nth-child(even) .tl-content-right{order:unset; display:none;}
      .tl-content-left{display:block!important;} .tl-content-right{display:none;}
      .tl-center{align-items:flex-start;}
      .offerings-grid{grid-template-columns:1fr;}
    }
  </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="section-label">Engineering Consultancy</div>
    <h1>Straight Answers on<br>Engineering Decisions</h1>
    <p>Independent, objective engineering consultancy — from technology roadmapping and process optimization to full project management and deployment oversight.</p>
  </div>
</div>

<!-- Consultancy Process Timeline -->
<section class="section">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:64px">
      <div class="section-label">Consultancy Process</div>
      <h2 class="section-title">How We <em>Work</em> With You</h2>
      <p class="section-sub">A structured engagement model that ensures you get practical, implementable advice — not just reports.</p>
    </div>

    <div class="timeline reveal">
      <div class="timeline-item">
        <div class="tl-content-left">
          <h3>Initial Assessment</h3>
          <p>We begin with a thorough evaluation of your current operations, technology stack, and pain points — interviewing key personnel and reviewing existing documentation to build an accurate picture.</p>
        </div>
        <div class="tl-center"><div class="tl-dot">1</div></div>
        <div class="tl-content-right tl-empty"></div>
      </div>

      <div class="timeline-item">
        <div class="tl-content-left tl-empty"></div>
        <div class="tl-center"><div class="tl-dot gold">2</div></div>
        <div class="tl-content-right">
          <h3>Strategy Development</h3>
          <p>Based on our findings, we develop a clear technology and operational strategy — with prioritized recommendations, investment estimates, and expected ROI for each initiative.</p>
        </div>
      </div>

      <div class="timeline-item">
        <div class="tl-content-left">
          <h3>Optimization Planning</h3>
          <p>Detailed engineering plans for each approved initiative — vendor selection guidance, system architecture, project schedules, and resource planning to ensure smooth execution.</p>
        </div>
        <div class="tl-center"><div class="tl-dot">3</div></div>
        <div class="tl-content-right tl-empty"></div>
      </div>

      <div class="timeline-item">
        <div class="tl-content-left tl-empty"></div>
        <div class="tl-center"><div class="tl-dot gold">4</div></div>
        <div class="tl-content-right">
          <h3>Deployment & Oversight</h3>
          <p>We can manage the full deployment — acting as your technical project manager — or provide oversight and review while your team or chosen contractors do the implementation.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Consulting Offerings -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div class="section-header-center" style="margin-bottom:48px">
      <div class="section-label">Service Offerings</div>
      <h2 class="section-title">Consultancy <em>Specialisations</em></h2>
    </div>
    <div class="offerings-grid" data-stagger>
      <?php $offs=[
        ['Automation Feasibility Study','Independent technical and commercial evaluation of automation opportunities in your facility — quantifying the ROI, payback period, and implementation risk before you commit capital.'],
        ['Technology Selection','Objective guidance on selecting PLCs, SCADA platforms, industrial networks, and vendors — without bias toward any particular supplier, based purely on your requirements.'],
        ['Process Optimization','Systematic analysis of your production process to identify bottlenecks, inefficiencies, and improvement opportunities — from cycle time reduction to yield improvement.'],
        ['Energy Audit & Strategy','Detailed energy consumption analysis for industrial facilities, identifying savings opportunities in motors, drives, compressed air, and HVAC — with an actionable reduction plan.'],
        ['Project Rescue','If an automation or engineering project is running late, over budget, or technically stuck — we provide independent review, root cause analysis, and a recovery plan.'],
        ['Vendor Management','Acting as your technical representative during vendor negotiations, FAT, and site acceptance testing — ensuring you get what you paid for, to the specification agreed.'],
      ]; ?>
      <?php foreach($offs as [$t,$d]): ?>
      <div class="card"><h3><?=$t?></h3><p><?=$d?></p></div>
      <?php endforeach; ?>
    </div>

    <!-- PDF Download -->
    <div style="margin-top:48px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-lg);padding:32px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap" class="reveal">
      <div>
        <h3 style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:var(--text);margin-bottom:6px">📄 Consultancy Services Brochure</h3>
        <p style="font-size:0.875rem;color:var(--text-3)">Download our detailed consultancy capabilities document outlining engagement models, deliverables, and case examples.</p>
      </div>
      <a href="contact.php?service=Consultancy" class="btn btn-primary">Request Brochure</a>
    </div>
  </div>
</section>

<!-- Accordion FAQ -->
<section class="section">
  <div class="container container-sm">
    <div class="section-header-center" style="margin-bottom:40px">
      <div class="section-label">Common Questions</div>
      <h2 class="section-title">Consultancy <em>FAQ</em></h2>
    </div>
    <?php $faqs=[
      ['How long does a typical engagement take?','Most consultancy engagements run 4–12 weeks depending on scope. A focused feasibility study can be completed in 2–3 weeks, while a full plant automation strategy may take 8–12 weeks.'],
      ['Do you only consult, or can you implement too?','We do both. We can act as pure independent consultants, or we can follow through with design, EMS, and system deployment. Many clients start with a consultancy engagement that naturally leads to implementation work.'],
      ['Will you recommend competitors\' products?','Yes. Our advice is independent. We recommend whatever technology best suits your requirements, budget, and operational context — regardless of which vendor it comes from.'],
      ['What industries have you consulted for?','Our consultants have worked across manufacturing, food & beverage, energy, water treatment, automotive, and pharmaceutical sectors throughout India.'],
      ['What is the fee structure?','Engagements can be structured as fixed-price (deliverable-based) or time & materials, depending on scope clarity. We always provide a clear proposal and fee schedule before any work begins.'],
    ]; ?>
    <?php foreach($faqs as [$q,$a]): ?>
    <div class="accordion-item">
      <div class="accordion-header">
        <h4><?=$q?></h4>
        <div class="accordion-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg></div>
      </div>
      <div class="accordion-body"><div class="accordion-body-inner"><p><?=$a?></p></div></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- CTA -->
<section class="section" style="background:var(--card-bg)">
  <div class="container">
    <div class="cta-banner reveal">
      <h2>Need an Independent Engineering Opinion?</h2>
      <p>Get expert guidance without the vendor bias — let's discuss your challenge in a no-obligation call.</p>
      <div class="btn-group">
        <a href="contact.php" class="btn btn-gold btn-lg">Book a Consultation</a>
        <a href="services.php" class="btn btn-white">All Services →</a>
      </div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
</body>
</html>
