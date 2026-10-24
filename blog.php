<?php
define('TECHZONE',1);
session_start();
$page_title = 'Tech Blog';
require 'header.php';

$posts = [
  ['slug'=>'best-laptops-2026','emoji'=>'💻','cat'=>'Laptops','title'=>'Best Laptops for Students in Malaysia 2026','excerpt'=>'Looking for the perfect laptop under RM 3,000? We tested 12 models and picked the absolute best for students, creators and everyday use.','author'=>'Ahmad Razif','date'=>'22 Apr 2026','read'=>'5 min','img_hue'=>'240'],
  ['slug'=>'gaming-phone-guide','emoji'=>'🎮','cat'=>'Gaming','title'=>'Gaming Phone Buyer\'s Guide: What Really Matters','excerpt'=>'Refresh rate, battery, or cooling? We break down what specs actually affect gaming performance and which phones deliver real results.','author'=>'Hazwan Malik','date'=>'19 Apr 2026','read'=>'7 min','img_hue'=>'280'],
  ['slug'=>'earbuds-vs-headphones','emoji'=>'🎧','cat'=>'Audio','title'=>'Earbuds vs Headphones: Which Should You Buy?','excerpt'=>'ANC earbuds are compact and convenient. Over-ear headphones deliver richer audio. We compare them head-to-head so you can choose wisely.','author'=>'Nur Aisyah','date'=>'15 Apr 2026','read'=>'4 min','img_hue'=>'160'],
  ['slug'=>'best-cameras-beginners','emoji'=>'📸','cat'=>'Cameras','title'=>'Best Cameras for Beginners in 2026','excerpt'=>'Starting your photography journey? These cameras make it easy to take stunning shots without a steep learning curve.','author'=>'Farah Nadia','date'=>'10 Apr 2026','read'=>'6 min','img_hue'=>'30'],
  ['slug'=>'smartwatch-health-tracking','emoji'=>'⌚','cat'=>'Wearables','title'=>'How Accurate Are Smartwatch Health Metrics?','excerpt'=>'We tested heart rate, SpO2, sleep tracking and stress sensors on 6 smartwatches. The results might surprise you.','author'=>'Ahmad Razif','date'=>'5 Apr 2026','read'=>'8 min','img_hue'=>'200'],
  ['slug'=>'5g-phones-worth-it','emoji'=>'📱','cat'=>'Smartphones','title'=>'Is 5G Actually Worth It in Malaysia Right Now?','excerpt'=>'5G coverage is growing but is it worth paying more for? We look at real-world speeds, coverage maps, and value-for-money in 2026.','author'=>'Hazwan Malik','date'=>'1 Apr 2026','read'=>'5 min','img_hue'=>'320'],
];

$active_cat = $_GET['cat'] ?? '';
$filtered = $active_cat ? array_filter($posts, fn($p) => $p['cat']==$active_cat) : $posts;
$cats_list = array_unique(array_column($posts, 'cat'));
?>
<style>
.blog-wrap{max-width:1200px;margin:0 auto;padding:50px 24px}
.blog-hero{text-align:center;margin-bottom:48px}
.blog-hero h1{font-family:'Outfit',sans-serif;font-size:2.5rem;font-weight:900;margin-bottom:12px}
.blog-hero p{color:var(--muted);font-size:1rem}
.cat-filter{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-bottom:40px}
.cat-filter a{padding:8px 20px;border-radius:25px;font-size:.85rem;font-weight:600;border:1px solid var(--border);color:var(--muted);transition:all .2s}
.cat-filter a:hover,.cat-filter a.active{background:var(--accent);color:#fff;border-color:var(--accent)}
.blog-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.blog-card{background:rgba(19,19,23,.85);border:1px solid var(--border);border-radius:20px;overflow:hidden;transition:transform .3s,border-color .3s;cursor:pointer}
.blog-card:hover{transform:translateY(-6px);border-color:rgba(108,99,255,.4)}
.blog-thumb{height:180px;display:flex;align-items:center;justify-content:center;font-size:4rem;position:relative;overflow:hidden}
.blog-thumb::after{content:'';position:absolute;inset:0;background:linear-gradient(to bottom,transparent 50%,rgba(13,13,15,.8))}
.blog-body{padding:22px}
.blog-cat{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:rgba(108,99,255,.15);color:var(--accent);margin-bottom:10px}
.blog-title{font-family:'Outfit',sans-serif;font-size:1.05rem;font-weight:700;line-height:1.4;margin-bottom:10px}
.blog-excerpt{color:var(--muted);font-size:.85rem;line-height:1.6;margin-bottom:16px}
.blog-meta{display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--muted)}
.blog-author{display:flex;align-items:center;gap:6px}
.blog-featured{grid-column:span 2;display:grid;grid-template-columns:1fr 1fr}
.blog-featured .blog-thumb{height:auto;min-height:260px}
.blog-featured .blog-body{padding:30px}
.blog-featured .blog-title{font-size:1.4rem}
@media(max-width:900px){.blog-grid{grid-template-columns:1fr 1fr}.blog-featured{grid-column:span 2}}
@media(max-width:600px){.blog-grid{grid-template-columns:1fr}.blog-featured{grid-template-columns:1fr}}
</style>

<div class="blog-wrap fade-up">
  <div class="blog-hero">
    <h1>📝 Tech <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Blog</span></h1>
    <p>Expert reviews, buying guides, and the latest tech news from Malaysia</p>
  </div>

  <div class="cat-filter">
    <a href="blog.php" class="<?= !$active_cat?'active':'' ?>">All</a>
    <?php foreach($cats_list as $c): ?>
      <a href="blog.php?cat=<?= urlencode($c) ?>" class="<?= $active_cat==$c?'active':'' ?>"><?= $c ?></a>
    <?php endforeach; ?>
  </div>

  <div class="blog-grid">
    <?php foreach(array_values($filtered) as $i => $p):
      $hue = $p['img_hue'];
      $featured = ($i===0 && !$active_cat);
    ?>
    <div class="blog-card <?= $featured?'blog-featured':'' ?>">
      <div class="blog-thumb" style="background:hsl(<?=$hue?>,30%,8%)">
        <span style="filter:hue-rotate(<?=$hue?>deg);font-size:<?=$featured?'5':'4'?>rem;position:relative;z-index:1"><?=$p['emoji']?></span>
      </div>
      <div class="blog-body">
        <div class="blog-cat"><?=$p['cat']?></div>
        <div class="blog-title"><?=htmlspecialchars($p['title'])?></div>
        <div class="blog-excerpt"><?=htmlspecialchars($p['excerpt'])?></div>
        <div class="blog-meta">
          <div class="blog-author">👤 <?=$p['author']?></div>
          <div><?=$p['date']?> · <?=$p['read']?> read</div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div style="text-align:center;margin-top:50px">
    <p style="color:var(--muted);margin-bottom:16px">Want to write for TechZone Blog?</p>
    <a href="contact.php" class="btn btn-outline">Get in Touch →</a>
  </div>
</div>

<?php require 'footer.php'; ?>
