<?php
define('TECHZONE', 1);
session_start();
$page_title = 'Home';
require 'header.php';

// Fetch trending products
$trending = $conn->query("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id ORDER BY reviews_count DESC LIMIT 8");
$new_arrivals = $conn->query("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC LIMIT 4");

function stars($r)
{
  $f = floor($r);
  $h = $r - $f >= .5 ? 1 : 0;
  $e = 5 - $f - $h;
  return str_repeat('★', $f) . str_repeat('½', $h) . str_repeat('☆', $e);
}
function discount($now, $old)
{
  if (!$old || $old <= $now)
    return 0;
  return round(100 - ($now / $old * 100));
}
function badge_class($b)
{
  $m = ['Hot' => 'badge-hot', 'New' => 'badge-new', 'Sale' => 'badge-sale', 'Best Seller' => 'badge-best', 'Top Rated' => 'badge-best', 'Premium' => 'badge-pro', 'Professional' => 'badge-pro', 'Compact' => 'badge-new', 'Gaming' => 'badge-hot'];
  return $m[$b] ?? 'badge-best';
}
?>

<!-- HERO -->
<script type="module" src="https://unpkg.com/@splinetool/viewer@latest/build/spline-viewer.js"></script>
<section class="hero">
  <div class="hero-content fade-up">
    <div class="hero-eyebrow anim-pulse">⚡ New Arrivals Just Dropped</div>
    <h1>Your Next <span class="grad-text-anim">Tech Upgrade</span></h1>
    <p>Discover Malaysia's premium electronics.</p>
    <div class="hero-btns">
      <a href="products.php" class="btn btn-primary">🛍️ Shop Now</a>
      <a href="products.php?cat=1" class="btn btn-outline">📱 Smartphones</a>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hero-img-wrap" style="position:relative;">
      <spline-viewer url="robot.splinecode" events-target="global" loading-anim-type="none" style="width:100%;height:100%;position:absolute;inset:0;pointer-events:none;mix-blend-mode:screen;"></spline-viewer>
    </div>
  </div>
</section>

<!-- MARQUEE TICKER -->
<div class="tech-marquee-wrap">
  <div class="tech-marquee" id="marquee">
    <?php
    $items = ['📱 Smartphones', '💻 Laptops', '🎧 Audio', '📸 Cameras', '🎮 Gaming', '⌚ Wearables', '⚡ Fast Delivery', '🛡️ Genuine Warranty', '🇲🇾 Ships Nationwide', '💳 Flexible Payment'];
    $double = array_merge($items, $items); // duplicate for seamless loop
    foreach ($double as $itm): ?>
      <div class="tech-marquee-item"><span class="dot"></span><?= $itm ?></div>
    <?php endforeach; ?>
  </div>
</div>

<!-- STATS -->
<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item">
      <div class="stat-num">16+</div>
      <div class="stat-label">Product Categories</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">50K+</div>
      <div class="stat-label">Happy Customers</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">100%</div>
      <div class="stat-label">Genuine Products</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">1-Day</div>
      <div class="stat-label">Fast Delivery</div>
    </div>
  </div>
</div>

<!-- CATEGORIES -->
<section class="section">
  <div class="section-title">
    <h2>Shop by <span>Category</span></h2>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:20px;margin-bottom:20px">
    <?php foreach ($cats as $cat): ?>
      <a href="products.php?cat=<?= $cat['id'] ?>" class="category-card"
        style="background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:30px 15px;text-align:center;transition:all .3s cubic-bezier(.175,.885,.32,1.275);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;backdrop-filter:blur(10px)">
        <div
          style="font-size:3rem;background:rgba(255,255,255,0.05);width:80px;height:80px;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:all .3s">
          <?= $cat['icon'] ?></div>
        <div style="font-size:1rem;font-weight:700;letter-spacing:0.5px"><?= htmlspecialchars($cat['name']) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- PROMO BANNER -->
<div class="promo-banner anim-pulse" style="animation-duration:8s">
  <div>
    <h3>🔥 Mega Tech Sale</h3>
  </div>
  <div style="text-align:center;flex-shrink:0">
    <div class="promo-code">TECHZONE30</div>
  </div>
  <a href="products.php" class="btn btn-primary" style="flex-shrink:0">Shop Sale →</a>
</div>

<!-- TRENDING PRODUCTS -->
<section class="section" style="padding-top:0">
  <div class="section-title">
    <h2>🔥 Trending <span>Now</span></h2>
    <a href="products.php" class="btn btn-outline btn-sm">View All →</a>
  </div>
  <div class="products-grid">
    <?php while ($p = $trending->fetch_assoc()):
      $disc = discount($p['price'], $p['original_price']); ?>
      <div class="product-card" onclick="window.location='product.php?id=<?= $p['id'] ?>'">
        <div class="card-img" style="background:hsl(<?= $p['hue'] ?>,30%,12%)">
          <?php if (!empty($p['image_url'])): ?>
            <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
              style="object-fit:cover;width:100%;height:100%">
          <?php else: ?>
            <img src="hero.png" alt="<?= htmlspecialchars($p['name']) ?>"
              style="filter:hue-rotate(<?= $p['hue'] ?>deg) saturate(1.5)">
          <?php endif; ?>
          <?php if ($p['badge']): ?>
            <div class="card-badge"><span
                class="badge <?= badge_class($p['badge']) ?>"><?= htmlspecialchars($p['badge']) ?></span></div>
          <?php endif; ?>
          <div class="card-wish">♡</div>
        </div>
        <div class="card-body">
          <div class="card-cat"><?= htmlspecialchars($p['cat_name']) ?></div>
          <div class="card-title"><?= htmlspecialchars($p['name']) ?></div>
          <div class="card-specs"><?= htmlspecialchars($p['specs']) ?></div>
          <div class="card-stars">
            <span class="stars"><?= str_repeat('★', floor($p['rating'])) ?></span>
            <span class="review-count">(<?= number_format($p['reviews_count']) ?>)</span>
          </div>
          <div class="card-price">
            <span class="price-now">RM <?= number_format($p['price'], 2) ?></span>
            <?php if ($p['original_price']): ?>
              <span class="price-old">RM <?= number_format($p['original_price'], 2) ?></span>
              <?php if ($disc > 0): ?><span class="price-disc">-<?= $disc ?>%</span><?php endif; ?>
            <?php endif; ?>
          </div>
          <a href="add_cart.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-block btn-sm"
            onclick="event.stopPropagation()">🛒 Add to Cart</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- BECOME A SELLER CTA -->
<section class="section" style="padding-top:20px;padding-bottom:60px">
  <div
    style="background:linear-gradient(135deg,rgba(108,99,255,0.15),rgba(255,101,132,0.15));border:1px solid rgba(255,255,255,0.08);border-radius:30px;padding:60px 40px;display:flex;align-items:center;justify-content:space-between;gap:40px;position:relative;overflow:hidden">
    <div class="anim-spin"
      style="position:absolute;top:-50%;left:-10%;width:300px;height:300px;background:var(--accent);filter:blur(120px);opacity:0.3;border-radius:50%">
    </div>
    <div class="anim-spin"
      style="position:absolute;bottom:-50%;right:-10%;width:300px;height:300px;background:var(--accent-2);filter:blur(120px);opacity:0.3;border-radius:50%;animation-direction:reverse">
    </div>
    <div style="position:relative;z-index:2;flex:1">
      <h2 style="font-family:'Outfit',sans-serif;font-size:2.8rem;font-weight:900;margin-bottom:16px;line-height:1.2">
        Open Your <span
          style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Store</span>
      </h2>
      <div style="display:flex;gap:16px;margin-top:20px">
        <a href="open_store.php" class="btn btn-primary" style="padding:14px 28px;font-size:1.05rem">🏪 Start
          Selling</a>
      </div>
    </div>
    <div style="flex:1;display:flex;justify-content:flex-end;position:relative;z-index:2">
      <div class="anim-float"
        style="background:rgba(19,19,23,0.8);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:24px;width:320px;box-shadow:0 20px 40px rgba(0,0,0,0.4)">
        <div
          style="display:flex;align-items:center;gap:12px;margin-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.05);padding-bottom:16px">
          <div
            style="width:48px;height:48px;border-radius:12px;background:var(--accent-grad);display:flex;align-items:center;justify-content:center;font-size:1.5rem">
            🏪</div>
          <div>
            <div style="font-weight:700;font-size:1.1rem">Demo Store</div>
            <div style="font-size:0.8rem;color:var(--green)">Online • Verified</div>
          </div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:12px">
          <span style="color:var(--muted);font-size:0.9rem">Products</span>
          <span style="font-weight:700">12</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
          <span style="color:var(--muted);font-size:0.9rem">Sales</span>
          <span style="font-weight:700">RM 5,240</span>
        </div>
        <div style="width:100%;height:6px;background:rgba(255,255,255,0.1);border-radius:4px;overflow:hidden">
          <div style="width:75%;height:100%;background:var(--green)"></div>
        </div>
        <div style="text-align:right;font-size:0.75rem;color:var(--muted);margin-top:8px">75% to Top Seller</div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section" style="background:var(--bg-2);margin:0;padding:60px 5%">
  <div class="section-title">
    <h2>⭐ Customer <span>Reviews</span></h2>
  </div>
  <div class="testimonial-grid">
    <?php
    $tests = [
      ['★★★★★', 'Dapat iPhone 15 Pro Max dalam 2 hari! Packaging sangat selamat dan barangan ori. Highly recommend TechZone!', 'Ahmad Fadzil', 'iPhone 15 Pro Max', 'AF'],
      ['★★★★★', 'MacBook M3 Max sampai sehari! Customer service sangat helpful. Legit store, boleh percaya 100%.', 'Nurul Ain', 'MacBook Pro M3 Max', 'NA'],
      ['★★★★☆', 'Sony WH-1000XM5 memang terbaik. Harga kompetitif berbanding kedai lain. Free shipping pun jimat duit.', 'Raj Kumar', 'Sony WH-1000XM5', 'RK'],
      ['★★★★★', 'PS5 Slim selamat sampai, box tak rosak. Price pun terbaik dalam Malaysia. Akan beli lagi!', 'Mei Ling Tan', 'PlayStation 5 Slim', 'MT'],
      ['★★★★★', 'Apple Watch Ultra 2 legit 100%. Warranty card ada, semua lengkap. Packaging premium gila.', 'Hafiz Zulkifli', 'Apple Watch Ultra 2', 'HZ'],
      ['★★★★★', 'ROG Zephyrus gaming laptop sampai dalam kondisi sempurna. Performance luar biasa. TechZone the best!', 'Brandon Lee', 'ASUS ROG Zephyrus', 'BL'],
    ];
    foreach ($tests as $t): ?>
      <div class="testimonial-card">
        <div class="test-stars"><?= $t[0] ?></div>
        <p class="test-text">"<?= $t[1] ?>"</p>
        <div class="test-author">
          <div class="test-avatar"><?= $t[4] ?></div>
          <div>
            <div class="test-name"><?= $t[2] ?></div>
            <div class="test-item"><?= $t[3] ?></div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require 'footer.php'; ?>