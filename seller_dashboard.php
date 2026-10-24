<?php
define('TECHZONE',1);
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php"); exit();
}

$stmt = $conn->prepare("SELECT * FROM stores WHERE user_id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$store = $stmt->get_result()->fetch_assoc();

if(!$store){ header("Location: open_store.php"); exit(); }
$store_id = $store['id'];
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Stats
$prod_count = $conn->query("SELECT COUNT(*) as c FROM products WHERE store_id=$store_id")->fetch_assoc()['c'];
$low_stock   = $conn->query("SELECT COUNT(*) as c FROM products WHERE store_id=$store_id AND stock < 5")->fetch_assoc()['c'];

// Products
$products = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.store_id=$store_id ORDER BY p.created_at DESC");

$page_title = 'My Store';
require 'header.php';
?>
<style>
.seller-wrap { max-width:1200px; margin:40px auto; padding:0 24px; }
.seller-header { display:flex; align-items:center; gap:20px; margin-bottom:32px; padding:28px 32px; background:rgba(19,19,23,.8); border:1px solid var(--border); border-radius:20px; backdrop-filter:blur(12px); }
.seller-logo { width:72px; height:72px; border-radius:16px; object-fit:cover; background:var(--accent-grad); display:flex; align-items:center; justify-content:center; font-size:2rem; flex-shrink:0; }
.seller-logo img { width:100%; height:100%; border-radius:16px; object-fit:cover; }
.seller-meta h2 { font-family:'Outfit',sans-serif; font-size:1.6rem; font-weight:800; }
.seller-meta p { color:var(--muted); font-size:.9rem; margin-top:4px; }
.seller-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
.badge-active { background:rgba(0,214,143,.15); color:var(--green); border:1px solid rgba(0,214,143,.3); }
.stat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
.stat-card { background:rgba(19,19,23,.8); border:1px solid var(--border); border-radius:16px; padding:20px 24px; display:flex; align-items:center; gap:16px; }
.stat-icon { font-size:1.8rem; }
.stat-num { font-size:1.5rem; font-weight:800; font-family:'Outfit',sans-serif; }
.stat-lbl { color:var(--muted); font-size:.8rem; }
.stat-card.warn { border-color:rgba(255,165,0,.3); }
.products-panel { background:rgba(19,19,23,.8); border:1px solid var(--border); border-radius:20px; overflow:hidden; }
.panel-top { display:flex; align-items:center; justify-content:space-between; padding:20px 28px; border-bottom:1px solid var(--border); }
.panel-top h3 { font-size:1.1rem; font-weight:700; }
.prod-table { width:100%; border-collapse:collapse; }
.prod-table th { font-size:.75rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; padding:12px 16px; border-bottom:1px solid var(--border); text-align:left; }
.prod-table td { padding:14px 16px; border-bottom:1px solid rgba(255,255,255,.04); vertical-align:middle; }
.prod-table tr:last-child td { border-bottom:none; }
.prod-table tr:hover td { background:rgba(255,255,255,.02); }
.prod-thumb { width:48px; height:48px; border-radius:10px; object-fit:cover; background:rgba(255,255,255,.05); display:inline-flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; vertical-align:middle; }
.prod-thumb img { width:48px; height:48px; border-radius:10px; object-fit:cover; }
.badge-chip { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:600; }
.badge-new { background:rgba(108,99,255,.2); color:#a5a0ff; }
.badge-sale { background:rgba(255,101,132,.2); color:#ff6584; }
.badge-hot { background:rgba(255,165,0,.2); color:orange; }
.stock-pill { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.8rem; font-weight:600; }
.stock-ok { background:rgba(0,214,143,.1); color:var(--green); }
.stock-low { background:rgba(255,165,0,.1); color:orange; }
.stock-out { background:rgba(255,85,85,.1); color:#ff5555; }
.acts { display:flex; gap:8px; }
</style>

<div class="seller-wrap fade-up">

  <?php if(isset($_GET['msg'])): ?>
  <div class="suc" style="margin-bottom:20px">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
  <?php endif; ?>
  <?php if(isset($_GET['err'])): ?>
  <div class="err" style="margin-bottom:20px">⚠️ <?= htmlspecialchars($_GET['err']) ?></div>
  <?php endif; ?>

  <!-- Store Header -->
  <div class="seller-header">
    <div class="seller-logo">
      <?php if(!empty($store['logo_url'])): ?>
        <img src="<?= htmlspecialchars($store['logo_url']) ?>" alt="logo">
      <?php else: ?>
        🏪
      <?php endif; ?>
    </div>
    <div class="seller-meta" style="flex:1">
      <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <h2><?= htmlspecialchars($store['store_name']) ?></h2>
        <span class="seller-badge badge-active">● Active</span>
      </div>
      <p><?= htmlspecialchars($store['store_description'] ?: 'No description yet.') ?></p>
      <?php if(!empty($store['location'])): ?>
        <p style="margin-top:4px;font-size:.82rem;color:var(--muted)">📍 <?= htmlspecialchars($store['location']) ?></p>
      <?php endif; ?>
    </div>
    <div style="display:flex;gap:10px">
      <a href="seller_settings.php" class="btn btn-outline btn-sm">⚙️ Store Settings</a>
      <a href="seller_product_form.php" class="btn btn-primary btn-sm">+ Add Product</a>
    </div>
  </div>

  <!-- Stats -->
  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-icon">📦</div>
      <div>
        <div class="stat-num"><?= $prod_count ?></div>
        <div class="stat-lbl">Total Products</div>
      </div>
    </div>
    <div class="stat-card <?= $low_stock>0?'warn':'' ?>">
      <div class="stat-icon">⚠️</div>
      <div>
        <div class="stat-num" style="<?= $low_stock>0?'color:orange':'' ?>"><?= $low_stock ?></div>
        <div class="stat-lbl">Low Stock (&lt;5)</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">🛒</div>
      <div>
        <div class="stat-num">0</div>
        <div class="stat-lbl">Orders</div>
      </div>
    </div>
  </div>

  <!-- Products Table -->
  <div class="products-panel">
    <div class="panel-top">
      <h3>📦 My Products <span style="color:var(--muted);font-weight:400;font-size:.9rem">(<?= $prod_count ?>)</span></h3>
      <a href="seller_product_form.php" class="btn btn-primary btn-sm">+ Add Product</a>
    </div>

    <?php if($products->num_rows > 0): ?>
    <table class="prod-table">
      <thead>
        <tr>
          <th style="width:60px"></th>
          <th>Product</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Badge</th>
          <th style="width:150px">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($p = $products->fetch_assoc()):
          $stock_class = $p['stock']==0 ? 'stock-out' : ($p['stock']<5 ? 'stock-low' : 'stock-ok');
          $stock_label = $p['stock']==0 ? 'Out' : $p['stock'];
          $badge_class = 'badge-new';
          if(strtolower($p['badge'])=='sale') $badge_class='badge-sale';
          if(strtolower($p['badge'])=='hot') $badge_class='badge-hot';
        ?>
        <tr>
          <td>
            <div class="prod-thumb">
              <?php if(!empty($p['image_url'])): ?>
                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="">
              <?php else: ?>
                <img src="hero.png" style="filter:hue-rotate(<?= $p['hue'] ?>deg);opacity:.7">
              <?php endif; ?>
            </div>
          </td>
          <td>
            <div style="font-weight:600;color:var(--text)"><?= htmlspecialchars($p['name']) ?></div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:2px"><?= htmlspecialchars(mb_strimwidth($p['specs']??'', 0, 45, '...')) ?></div>
          </td>
          <td style="color:var(--muted);font-size:.88rem"><?= htmlspecialchars($p['cat_name']) ?></td>
          <td>
            <div style="font-weight:700">RM <?= number_format($p['price'],2) ?></div>
            <?php if($p['original_price']): ?><div style="font-size:.78rem;color:var(--muted);text-decoration:line-through">RM <?= number_format($p['original_price'],2) ?></div><?php endif; ?>
          </td>
          <td><span class="stock-pill <?= $stock_class ?>"><?= $stock_label ?></span></td>
          <td>
            <?php if($p['badge']): ?>
              <span class="badge-chip <?= $badge_class ?>"><?= htmlspecialchars($p['badge']) ?></span>
            <?php else: ?>
              <span style="color:var(--muted);font-size:.8rem">—</span>
            <?php endif; ?>
          </td>
          <td>
            <div class="acts">
              <a href="seller_product_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline" style="padding:5px 12px">Edit</a>
              <form method="post" action="seller_delete_product.php" style="display:inline" onsubmit="return confirm('Delete this product?')">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" class="btn btn-sm btn-danger" style="padding:5px 12px">Del</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div style="padding:60px;text-align:center;color:var(--muted)">
      <div style="font-size:3.5rem;margin-bottom:16px">📦</div>
      <h3 style="margin-bottom:8px">No Products Yet</h3>
      <p style="margin-bottom:24px">Add your first product to start selling!</p>
      <a href="seller_product_form.php" class="btn btn-primary">+ Add First Product</a>
    </div>
    <?php endif; ?>
  </div>

</div>
<?php require 'footer.php'; ?>
