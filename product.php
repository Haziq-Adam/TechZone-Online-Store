<?php
define('TECHZONE',1);
session_start();
require 'db.php';

$id = intval($_GET['id'] ?? 0);
if(!$id){header("Location: products.php");exit();}

$stmt = $conn->prepare("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if(!$p){header("Location: products.php");exit();}

function discount($now,$old){if(!$old||$old<=$now)return 0;return round(100-($now/$old*100));}
function badge_class($b){$m=['Hot'=>'badge-hot','New'=>'badge-new','Sale'=>'badge-sale','Best Seller'=>'badge-best','Top Rated'=>'badge-best','Premium'=>'badge-pro','Professional'=>'badge-pro','Compact'=>'badge-new','Gaming'=>'badge-hot'];return $m[$b]??'badge-best';}

$page_title = $p['name'];
require 'header.php';
$disc = discount($p['price'], $p['original_price']);

// Related products
$related = $conn->prepare("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.category_id=? AND p.id!=? LIMIT 4");
$related->bind_param("ii",$p['category_id'],$id);
$related->execute();
$related = $related->get_result();
?>

<div style="padding:40px 5%">
  <!-- Breadcrumb -->
  <div style="font-size:.82rem;color:var(--muted);margin-bottom:28px">
    <a href="index.php">Home</a> › <a href="products.php?cat=<?=$p['category_id']?>"><?=htmlspecialchars($p['cat_name'])?></a> › <?=htmlspecialchars($p['name'])?>
  </div>

  <!-- Product Detail -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;margin-bottom:60px">
    <!-- Image -->
    <div style="background:hsl(<?=$p['hue']?>,30%,10%);border:1px solid var(--border);border-radius:24px;min-height:420px;display:flex;align-items:center;justify-content:center;overflow:hidden">
      <?php if(!empty($p['image_url'])): ?>
        <img src="<?=htmlspecialchars($p['image_url'])?>" alt="<?=htmlspecialchars($p['name'])?>" style="width:100%;height:100%;object-fit:cover">
      <?php else: ?>
        <img src="hero.png" alt="<?=htmlspecialchars($p['name'])?>" style="filter:hue-rotate(<?=$p['hue']?>deg) saturate(1.5);width:100%;max-height:420px;object-fit:cover">
      <?php endif; ?>
    </div>

    <!-- Info -->
    <div>
      <?php if($p['badge']): ?><span class="badge <?=badge_class($p['badge'])?>" style="margin-bottom:12px;display:inline-block"><?=htmlspecialchars($p['badge'])?></span><?php endif; ?>
      <div style="font-size:.82rem;color:var(--accent);font-weight:600;margin-bottom:8px"><?=htmlspecialchars($p['cat_name'])?></div>
      <h1 style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:800;margin-bottom:12px"><?=htmlspecialchars($p['name'])?></h1>
      
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
        <span style="color:#ffd700"><?=str_repeat('★',floor($p['rating']))?></span>
        <span style="font-weight:600"><?=$p['rating']?>/5.0</span>
        <span style="color:var(--muted)">(<?=number_format($p['reviews_count'])?> reviews)</span>
        <span style="color:var(--green);font-size:.82rem">✓ <?=$p['stock']?> in stock</span>
      </div>

      <div style="font-size:.92rem;color:var(--muted);margin-bottom:24px;line-height:1.7"><?=htmlspecialchars($p['description'])?></div>

      <!-- Specs -->
      <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:24px">
        <div style="font-size:.78rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px">Specifications</div>
        <?php foreach(explode('|',$p['specs']) as $spec): ?>
        <div style="font-size:.85rem;padding:4px 0;color:var(--muted)">✓ <?=trim(htmlspecialchars($spec))?></div>
        <?php endforeach; ?>
      </div>

      <!-- Price -->
      <div style="display:flex;align-items:center;gap:14px;margin-bottom:28px">
        <span style="font-family:'Outfit',sans-serif;font-size:2.2rem;font-weight:900;background:linear-gradient(135deg,#6c63ff,#ff6584);-webkit-background-clip:text;-webkit-text-fill-color:transparent">RM <?=number_format($p['price'],2)?></span>
        <?php if($p['original_price']): ?><span style="font-size:1rem;color:var(--muted);text-decoration:line-through">RM <?=number_format($p['original_price'],2)?></span><?php endif; ?>
        <?php if($disc>0): ?><span class="price-disc">Save <?=$disc?>%</span><?php endif; ?>
      </div>

      <div style="display:flex;gap:12px">
        <a href="add_cart.php?id=<?=$p['id']?>" class="btn btn-primary" style="flex:1;justify-content:center">🛒 Add to Cart</a>
        <button class="btn btn-outline">♡ Wishlist</button>
      </div>
      
      <div style="margin-top:20px;display:flex;gap:20px;flex-wrap:wrap">
        <span style="font-size:.82rem;color:var(--muted)">📦 Free shipping RM200+</span>
        <span style="font-size:.82rem;color:var(--muted)">🔒 Secure checkout</span>
        <span style="font-size:.82rem;color:var(--muted)">🔄 30-day returns</span>
      </div>
    </div>
  </div>

  <!-- Related Products -->
  <?php if($related->num_rows > 0): ?>
  <div>
    <h2 style="font-family:'Outfit',sans-serif;font-size:1.5rem;margin-bottom:24px">You may also like</h2>
    <div class="products-grid" style="grid-template-columns:repeat(4,1fr)">
      <?php while($rp = $related->fetch_assoc()): ?>
      <div class="product-card" onclick="window.location='product.php?id=<?=$rp['id']?>'">
        <div class="card-img" style="background:hsl(<?=$rp['hue']?>,30%,12%)">
          <?php if(!empty($rp['image_url'])): ?>
            <img src="<?=htmlspecialchars($rp['image_url'])?>" style="width:100%;height:100%;object-fit:cover">
          <?php else: ?>
            <img src="hero.png" style="filter:hue-rotate(<?=$rp['hue']?>deg) saturate(1.5)">
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="card-title"><?=htmlspecialchars($rp['name'])?></div>
          <div class="card-price"><span class="price-now">RM <?=number_format($rp['price'],2)?></span></div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require 'footer.php'; ?>
