<?php
define('TECHZONE',1);
session_start();
$page_title = 'About Us';
require 'header.php';
?>
<style>
.about-hero{padding:80px 5% 60px;text-align:center;position:relative;overflow:hidden}
.about-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center top,rgba(108,99,255,.15) 0%,transparent 70%);pointer-events:none}
.about-hero h1{font-family:'Outfit',sans-serif;font-size:clamp(2.2rem,5vw,3.5rem);font-weight:900;margin-bottom:20px}
.about-hero p{color:var(--muted);font-size:1.1rem;max-width:600px;margin:0 auto 40px;line-height:1.8}
.floating-icons{display:flex;justify-content:center;gap:24px;margin-bottom:60px;flex-wrap:wrap}
.fi{width:72px;height:72px;border-radius:20px;background:rgba(108,99,255,.1);border:1px solid rgba(108,99,255,.2);display:flex;align-items:center;justify-content:center;font-size:2rem}
.fi:nth-child(1){animation:float 5s ease-in-out infinite}
.fi:nth-child(2){animation:float 4s ease-in-out infinite 0.5s}
.fi:nth-child(3){animation:float 6s ease-in-out infinite 1s}
.fi:nth-child(4){animation:float 4.5s ease-in-out infinite 1.5s}
.fi:nth-child(5){animation:float 5.5s ease-in-out infinite 2s}

.mission-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;padding:60px 5%;background:var(--bg-2)}
.mission-text h2{font-family:'Outfit',sans-serif;font-size:2rem;font-weight:800;margin-bottom:16px}
.mission-text p{color:var(--muted);line-height:1.8;margin-bottom:16px}
.mission-visual{position:relative}
.mission-card{background:rgba(19,19,23,.9);border:1px solid var(--border);border-radius:20px;padding:24px;margin-bottom:16px;display:flex;align-items:center;gap:16px;backdrop-filter:blur(12px)}
.mission-card-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
.mc-purple{background:rgba(108,99,255,.15);border:1px solid rgba(108,99,255,.3)}
.mc-green{background:rgba(0,214,143,.15);border:1px solid rgba(0,214,143,.3)}
.mc-pink{background:rgba(255,101,132,.15);border:1px solid rgba(255,101,132,.3)}

.values-sec{padding:60px 5%;text-align:center}
.values-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:40px}
.value-card{background:rgba(19,19,23,.8);border:1px solid var(--border);border-radius:20px;padding:32px 24px;transition:transform .3s,border-color .3s}
.value-card:hover{transform:translateY(-6px);border-color:rgba(108,99,255,.4)}
.value-card .vc-icon{font-size:2.5rem;margin-bottom:16px;display:block}
.value-card h3{font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:10px}
.value-card p{color:var(--muted);font-size:.88rem;line-height:1.7}

.team-sec{padding:60px 5%;background:var(--bg-2)}
.team-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;margin-top:40px}
.team-card{background:rgba(19,19,23,.8);border:1px solid var(--border);border-radius:20px;padding:28px 20px;text-align:center;transition:transform .3s}
.team-card:hover{transform:translateY(-5px)}
.team-avatar{width:80px;height:80px;border-radius:50%;margin:0 auto 16px;font-size:2.5rem;display:flex;align-items:center;justify-content:center}
.team-card h4{font-weight:700;margin-bottom:4px}
.team-card p{color:var(--muted);font-size:.82rem}

.stats-about{display:grid;grid-template-columns:repeat(4,1fr);gap:0;background:rgba(19,19,23,.9);border:1px solid var(--border);border-radius:20px;overflow:hidden;margin:0 5% 60px}
.sa-item{padding:32px 20px;text-align:center;border-right:1px solid var(--border)}
.sa-item:last-child{border-right:none}
.sa-num{font-family:'Outfit',sans-serif;font-size:2.2rem;font-weight:900;background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.sa-lbl{color:var(--muted);font-size:.82rem;margin-top:4px}

@media(max-width:768px){
  .mission-grid,.values-grid,.team-grid{grid-template-columns:1fr}
  .stats-about{grid-template-columns:1fr 1fr}
  .sa-item{border-right:none;border-bottom:1px solid var(--border)}
}
</style>

<!-- Hero -->
<div class="about-hero fade-up">
  <div class="floating-icons">
    <div class="fi">📱</div>
    <div class="fi">💻</div>
    <div class="fi">⚡</div>
    <div class="fi">🎧</div>
    <div class="fi">🎮</div>
  </div>
  <h1>We Are <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">TechZone</span></h1>
  <p>Malaysia's premier destination for cutting-edge electronics. Founded with a passion for technology and a commitment to bringing the best products to every doorstep.</p>
</div>

<!-- Stats -->
<div class="stats-about">
  <div class="sa-item"><div class="sa-num" data-count="50000">50K+</div><div class="sa-lbl">Happy Customers</div></div>
  <div class="sa-item"><div class="sa-num">2,400+</div><div class="sa-lbl">Products Listed</div></div>
  <div class="sa-item"><div class="sa-num">200+</div><div class="sa-lbl">Verified Sellers</div></div>
  <div class="sa-item"><div class="sa-num">4.9★</div><div class="sa-lbl">Average Rating</div></div>
</div>

<!-- Mission -->
<div class="mission-grid">
  <div class="mission-text fade-up">
    <h2>Our <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Mission</span></h2>
    <p>We believe everyone deserves access to premium technology. TechZone bridges the gap between world-class electronics brands and everyday Malaysians, offering genuine products at competitive prices with full warranty protection.</p>
    <p>Beyond being a marketplace, we're a community. From first-time buyers to tech enthusiasts, we serve everyone with the same commitment: honest service, quality products, and a seamless experience.</p>
    <a href="products.php" class="btn btn-primary" style="margin-top:8px">Shop Now →</a>
  </div>
  <div class="mission-visual">
    <div class="mission-card" style="animation:float 5s ease-in-out infinite">
      <div class="mission-card-icon mc-purple">🛡️</div>
      <div>
        <div style="font-weight:700;margin-bottom:2px">100% Genuine Products</div>
        <div style="color:var(--muted);font-size:.85rem">Every item is authentic and verified</div>
      </div>
    </div>
    <div class="mission-card" style="animation:float 6s ease-in-out infinite 1s">
      <div class="mission-card-icon mc-green">🚀</div>
      <div>
        <div style="font-weight:700;margin-bottom:2px">Fast Nationwide Delivery</div>
        <div style="color:var(--muted);font-size:.85rem">2-3 days to Peninsular, 5-7 Sabah/Sarawak</div>
      </div>
    </div>
    <div class="mission-card" style="animation:float 4.5s ease-in-out infinite 0.5s">
      <div class="mission-card-icon mc-pink">💬</div>
      <div>
        <div style="font-weight:700;margin-bottom:2px">24/7 Customer Support</div>
        <div style="color:var(--muted);font-size:.85rem">WhatsApp, email, and live chat</div>
      </div>
    </div>
  </div>
</div>

<!-- Values -->
<div class="values-sec">
  <h2 style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:800">What We <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Stand For</span></h2>
  <div class="values-grid">
    <div class="value-card fade-up">
      <span class="vc-icon">🔒</span>
      <h3>Trust & Transparency</h3>
      <p>No hidden fees, genuine product listings, and honest seller ratings. What you see is what you get.</p>
    </div>
    <div class="value-card fade-up" style="animation-delay:.15s">
      <span class="vc-icon">⚡</span>
      <h3>Innovation First</h3>
      <p>We constantly evolve our platform to make your shopping experience faster, smarter, and more enjoyable.</p>
    </div>
    <div class="value-card fade-up" style="animation-delay:.3s">
      <span class="vc-icon">🌍</span>
      <h3>Community Driven</h3>
      <p>We empower Malaysian entrepreneurs to open stores and grow their businesses on our platform.</p>
    </div>
  </div>
</div>

<!-- Team -->
<div class="team-sec">
  <h2 style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:800;text-align:center">Meet the <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Team</span></h2>
  <div class="team-grid">
    <?php
    $team = [
      ['🧑‍💼','Ahmad Razif','Founder & CEO','bg:rgba(108,99,255,.15)'],
      ['👩‍💻','Nur Aisyah','Head of Technology','bg:rgba(255,101,132,.15)'],
      ['🧑‍🎨','Hazwan Malik','Lead Designer','bg:rgba(0,214,143,.15)'],
      ['👩‍📊','Farah Nadia','Head of Operations','bg:rgba(255,200,0,.15)'],
    ];
    foreach($team as $i=>$m): ?>
    <div class="team-card">
      <div class="team-avatar" style="background:<?= $m[3] ?>; animation:float <?= 5+$i ?>s ease-in-out infinite <?= $i*0.5 ?>s"><?= $m[0] ?></div>
      <h4><?= $m[1] ?></h4>
      <p><?= $m[2] ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require 'footer.php'; ?>
