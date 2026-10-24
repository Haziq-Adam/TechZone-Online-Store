<?php
define('TECHZONE',1);
session_start();
$page_title = 'Products';
require 'header.php';

function stars($r){return str_repeat('★',floor($r)).str_repeat('☆',5-floor($r));}
function discount($now,$old){if(!$old||$old<=$now)return 0;return round(100-($now/$old*100));}
function badge_class($b){$m=['Hot'=>'badge-hot','New'=>'badge-new','Sale'=>'badge-sale','Best Seller'=>'badge-best','Top Rated'=>'badge-best','Premium'=>'badge-pro','Professional'=>'badge-pro','Compact'=>'badge-new','Gaming'=>'badge-hot'];return $m[$b]??'badge-best';}

$cat_id = intval($_GET['cat'] ?? 0);
$q = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'popular';

$where = "WHERE 1";
$params = [];
$types = "";
if($cat_id){$where .= " AND p.category_id=?"; $params[]=$cat_id; $types.="i";}
if($q){$where .= " AND p.name LIKE ?"; $params[]="%$q%"; $types.="s";}

$order = match($sort){
  'price_asc' => 'p.price ASC',
  'price_desc' => 'p.price DESC',
  'rating' => 'p.rating DESC',
  default => 'p.reviews_count DESC'
};

$sql = "SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id $where ORDER BY $order";
$stmt = $conn->prepare($sql);
if($params){$stmt->bind_param($types,...$params);}
$stmt->execute();
$result = $stmt->get_result();

$cat_name = $cat_id ? ($conn->query("SELECT name FROM categories WHERE id=$cat_id")->fetch_assoc()['name']??'All') : 'All Products';
?>

<div style="padding:32px 5%">
  <!-- Page Title -->
  <div style="margin-bottom:28px">
    <div style="font-size:.8rem;color:var(--muted);margin-bottom:6px"><a href="index.php">Home</a> › <?= htmlspecialchars($cat_name) ?></div>
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
      <h1 style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:800">
        <?= $q ? "Search: \"".htmlspecialchars($q)."\"" : htmlspecialchars($cat_name) ?>
      </h1>
      <div style="display:flex;align-items:center;gap:12px">
        <span style="font-size:.85rem;color:var(--muted)"><?= $result->num_rows ?> products</span>
        <select onchange="window.location=this.value" style="background:var(--bg-card);border:1px solid var(--border);color:var(--text);padding:8px 14px;border-radius:10px;outline:none;cursor:pointer;font-size:.85rem">
          <option value="?cat=<?=$cat_id?>&q=<?=$q?>&sort=popular" <?=$sort=='popular'?'selected':''?>>Most Popular</option>
          <option value="?cat=<?=$cat_id?>&q=<?=$q?>&sort=price_asc" <?=$sort=='price_asc'?'selected':''?>>Price: Low–High</option>
          <option value="?cat=<?=$cat_id?>&q=<?=$q?>&sort=price_desc" <?=$sort=='price_desc'?'selected':''?>>Price: High–Low</option>
          <option value="?cat=<?=$cat_id?>&q=<?=$q?>&sort=rating" <?=$sort=='rating'?'selected':''?>>Top Rated</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Category Pills -->
  <div class="cat-pills">
    <a href="products.php" class="cat-pill <?= !$cat_id?'active':'' ?>">All Products</a>
    <?php foreach($cats as $cat): ?>
    <a href="products.php?cat=<?= $cat['id'] ?>" class="cat-pill <?= $cat_id==$cat['id']?'active':'' ?>">
      <?= $cat['icon'].' '.htmlspecialchars($cat['name']) ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Products Grid -->
  <?php if($result->num_rows === 0): ?>
  <div style="text-align:center;padding:80px 0;color:var(--muted)">
    <div style="font-size:4rem;margin-bottom:16px">🔍</div>
    <h3>No products found</h3>
    <p style="margin-top:8px">Try a different search term or category.</p>
    <a href="products.php" class="btn btn-primary" style="margin-top:24px">Browse All Products</a>
  </div>
  <?php else: ?>
  <div class="products-grid">
    <?php while($p = $result->fetch_assoc()):
      $disc = discount($p['price'], $p['original_price']); ?>
    <div class="product-card" onclick="window.location='product.php?id=<?= $p['id'] ?>'">
      <div class="card-img" style="background:hsl(<?= $p['hue'] ?>,30%,12%)">
        <?php if(!empty($p['image_url'])): ?>
          <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="object-fit:cover;width:100%;height:100%">
        <?php else: ?>
          <img src="hero.png" alt="<?= htmlspecialchars($p['name']) ?>" style="filter:hue-rotate(<?= $p['hue'] ?>deg) saturate(1.5)">
        <?php endif; ?>
        <?php if($p['badge']): ?><div class="card-badge"><span class="badge <?= badge_class($p['badge']) ?>"><?= htmlspecialchars($p['badge']) ?></span></div><?php endif; ?>
        <div class="card-wish" title="Add to Wishlist">♡</div>
      </div>
      <div class="card-body">
        <div class="card-cat"><?= htmlspecialchars($p['cat_name']) ?></div>
        <div class="card-title"><?= htmlspecialchars($p['name']) ?></div>
        <div class="card-specs"><?= htmlspecialchars($p['specs']) ?></div>
        <div class="card-stars">
          <span class="stars"><?= str_repeat('★',floor($p['rating'])) ?></span>
          <span class="review-count">(<?= number_format($p['reviews_count']) ?> reviews)</span>
        </div>
        <div class="card-price">
          <span class="price-now">RM <?= number_format($p['price'],2) ?></span>
          <?php if($p['original_price']): ?>
            <span class="price-old">RM <?= number_format($p['original_price'],2) ?></span>
            <?php if($disc>0): ?><span class="price-disc">-<?= $disc ?>%</span><?php endif; ?>
          <?php endif; ?>
        </div>
        <div style="display:flex;gap:8px">
          <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline btn-sm" onclick="event.stopPropagation()">Details</a>
          <a href="add_cart.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm" style="flex:1;justify-content:center" onclick="event.stopPropagation()">🛒 Add to Cart</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>

<?php require 'footer.php'; ?>
