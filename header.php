<?php
if(!defined('TECHZONE')) die('No direct access');
require_once 'db.php';
$cat_result = $conn->query("SELECT * FROM categories ORDER BY id");
$cats = [];
while($c = $cat_result->fetch_assoc()) $cats[] = $c;

// Cart count
$cart_count = 0;
if(isset($_SESSION['user_id'])){
  $cc = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id=?");
  $cc->bind_param("i",$_SESSION['user_id']);
  $cc->execute();
  $cart_count = $cc->get_result()->fetch_assoc()['total'] ?? 0;
}
$has_store = false;
if(isset($_SESSION['user_id'])){
    $store_check = $conn->prepare("SELECT id FROM stores WHERE user_id=?");
    $store_check->bind_param("i", $_SESSION['user_id']);
    $store_check->execute();
    if($store_check->get_result()->num_rows > 0){
        $has_store = true;
    }
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= isset($page_title) ? htmlspecialchars($page_title).' – ' : '' ?>TechZone</title>
  <meta name="description" content="TechZone – Malaysia's Premium Electronics Store. Shop the latest smartphones, laptops, audio, cameras and more.">
  <link rel="stylesheet" href="style.css?v=<?= time() ?>">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">
</head>
<body>
<header>
  <a href="index.php" class="logo">⚡ TechZone</a>
  <nav class="main-nav">
    <a href="index.php" class="<?= $current_page=='index.php'?'active':'' ?>">Home</a>
    <div class="nav-dropdown">
      <a href="products.php" class="<?= $current_page=='products.php'?'active':'' ?>">Products</a>
      <div class="dropdown-content">
        <?php 
        $icon_map = ['Smartphones'=>'📱','Laptops'=>'💻','Audio'=>'🎧','Cameras'=>'📸','Gaming'=>'🎮','Wearables'=>'⌚'];
        foreach($cats as $cat): 
          $cat_icon = $icon_map[$cat['name']] ?? $cat['icon'];
        ?>
        <a href="products.php?cat=<?= $cat['id'] ?>" class="<?= ($_GET['cat']??'')==$cat['id']?'active':'' ?>">
          <?= $cat_icon.' '.htmlspecialchars($cat['name']) ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <a href="about.php" class="<?= $current_page=='about.php'?'active':'' ?>">About Us</a>
    <a href="blog.php" class="<?= $current_page=='blog.php'?'active':'' ?>">Blog</a>
    <a href="faq.php" class="<?= $current_page=='faq.php'?'active':'' ?>">FAQ</a>
    <a href="contact.php" class="<?= $current_page=='contact.php'?'active':'' ?>">Contact</a>
  </nav>
  <div class="nav-right">
    <div class="nav-search">
      <svg width="16" height="16" fill="none" stroke="#8888aa" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" placeholder="Search products..." id="searchInput" onkeydown="if(event.key==='Enter'){window.location='products.php?q='+encodeURIComponent(this.value)}">
    </div>
    <a href="cart.php" class="cart-btn" title="Cart">
      🛒
      <?php if($cart_count > 0): ?><span class="cart-count"><?= $cart_count ?></span><?php endif; ?>
    </a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
        <a href="admin.php" class="btn btn-sm btn-outline">⚙️ Admin</a>
      <?php endif; ?>
      <div style="display:flex;align-items:center;gap:10px;">
        <span style="font-size:.85rem;color:var(--text);font-weight:500;display:flex;align-items:center;gap:6px">
          👤 <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
          <?php if($has_store): ?>
            <span title="Verified Seller" style="display:inline-flex;align-items:center;gap:3px;background:rgba(0,214,143,.15);color:var(--green);border:1px solid rgba(0,214,143,.3);border-radius:20px;padding:2px 8px;font-size:.65rem;font-weight:700;letter-spacing:.3px">✓ SELLER</span>
          <?php endif; ?>
        </span>
        <?php if($has_store): ?>
          <a href="seller_dashboard.php" class="btn btn-sm btn-outline" style="border-color:var(--green);color:var(--green);padding:5px 12px">🏪 My Store</a>
        <?php else: ?>
          <a href="open_store.php" class="btn btn-sm btn-outline" style="border-color:var(--accent);color:var(--accent);padding:5px 12px">🏪 Open Store</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
      </div>
    <?php else: ?>
      <a href="login.php" class="btn btn-sm btn-outline">Login</a>
      <a href="register.php" class="btn btn-sm btn-primary">Register</a>
    <?php endif; ?>
  </div>
</header>
