<?php
define('TECHZONE', 1);
session_start();
$page_title = 'My Cart';
require_once 'db.php';

// Handle POST actions before rendering any HTML so redirects work correctly.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
        http_response_code(403);
        exit('Invalid form token. Refresh the page.');
    }
    $user_id = (int) $_SESSION['user_id'];
    if (isset($_POST['remove_id'])) {
        $remove_id = (int) $_POST['remove_id'];
        if ($remove_id > 0) {
            $del = $conn->prepare('DELETE FROM cart WHERE id=? AND user_id=?');
            $del->bind_param('ii', $remove_id, $user_id);
            $del->execute();
        }
        header('Location: cart.php');
        exit;
    }
    if (isset($_POST['update_qty']) && isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $cid => $qty) {
            $qty_val = min(999, max(1, (int) $qty));
            $cid_val = (int) $cid;
            if ($cid_val <= 0) continue;
            $u = $conn->prepare('UPDATE cart SET quantity=? WHERE id=? AND user_id=?');
            $u->bind_param('iii', $qty_val, $cid_val, $user_id);
            $u->execute();
        }
        header('Location: cart.php');
        exit;
    }
}

$rows = [];
$subtotal = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT c.*, p.name, p.price, p.specs, p.hue, p.image_url FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=?');
    $user_id = (int) $_SESSION['user_id'];
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $items = $stmt->get_result();
    while ($r = $items->fetch_assoc()) {
        $rows[] = $r;
        $subtotal += $r['price'] * $r['quantity'];
    }
}
$shipping = $subtotal >= 200 ? 0 : 15;
$total = $subtotal + $shipping;
require 'header.php';
if (!isset($_SESSION['user_id'])) {
    echo "<div style='text-align:center;padding:80px'><h2>Please sign in to view your cart</h2><a href='login.php' class='btn btn-primary'>Login</a></div>";
    require 'footer.php';
    exit;
}
?>

<?php if(empty($rows)): ?>
<div style="text-align:center;padding:100px 5%">
  <div style="font-size:5rem;margin-bottom:20px">🛒</div>
  <h2 style="font-family:'Outfit',sans-serif;font-size:2rem;margin-bottom:10px">Your cart is empty!</h2>
  <p style="color:var(--muted);margin-bottom:28px">Looks like you haven't added anything yet.</p>
  <a href="products.php" class="btn btn-primary">Browse Products</a>
</div>
<?php else: ?>
<div class="cart-layout">
  <!-- Items -->
  <div>
    <h1 style="font-family:'Outfit',sans-serif;font-size:1.8rem;margin-bottom:24px">🛒 My Cart <span style="font-size:1rem;color:var(--muted);font-family:'Inter',sans-serif;font-weight:400">(<?=count($rows)?> items)</span></h1>
    <form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8')?>">
    <?php foreach($rows as $row): ?>
    <div class="cart-item">
      <div class="cart-thumb" style="background:hsl(<?=$row['hue']?>,30%,12%)">
        <?php if(!empty($row['image_url'])): ?>
          <img src="<?=htmlspecialchars($row['image_url'])?>" style="width:80px;height:80px;object-fit:cover;border-radius:10px">
        <?php else: ?>
          <img src="hero.png" style="width:80px;height:80px;object-fit:cover;border-radius:10px;filter:hue-rotate(<?=$row['hue']?>deg) saturate(1.5)">
        <?php endif; ?>
      </div>
      <div class="cart-info">
        <h4><?=htmlspecialchars($row['name'])?></h4>
        <p><?=htmlspecialchars($row['specs'])?></p>
        <div class="cart-qty">
          <span style="font-size:.82rem;color:var(--muted)">Qty:</span>
          <button type="button" class="qty-btn" onclick="let i=document.getElementById('q<?=$row['id']?>');if(i.value>1)i.value--">−</button>
          <input type="number" id="q<?=$row['id']?>" name="qty[<?=$row['id']?>]" value="<?=$row['quantity']?>" min="1" style="width:50px;text-align:center;background:var(--bg-card);border:1px solid var(--border);border-radius:8px;color:var(--text);padding:4px">
          <button type="button" class="qty-btn" onclick="let i=document.getElementById('q<?=$row['id']?>');i.value++">+</button>
        </div>
      </div>
      <div style="text-align:right;flex-shrink:0">
        <div style="font-size:1.1rem;font-weight:800;background:linear-gradient(135deg,#6c63ff,#ff6584);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:10px">RM <?=number_format($row['price']*$row['quantity'],2)?></div>
        <button type="submit" name="remove_id" value="<?= (int)$row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove item?')">Remove</button>
      </div>
    </div>
    <?php endforeach; ?>
    <button type="submit" name="update_qty" class="btn btn-outline btn-sm">🔄 Update Cart</button>
    </form>
  </div>

  <!-- Summary -->
  <div class="cart-summary">
    <h3 style="font-family:'Outfit',sans-serif;margin-bottom:20px">Order Summary</h3>
    <div class="summary-row"><span style="color:var(--muted)">Subtotal</span><span>RM <?=number_format($subtotal,2)?></span></div>
    <div class="summary-row"><span style="color:var(--muted)">Shipping</span><span><?=$shipping>0?"RM ".number_format($shipping,2):"<span style='color:var(--green)'>FREE</span>"?></span></div>
    <?php if($subtotal < 200): ?>
    <div style="background:rgba(108,99,255,.1);border:1px solid rgba(108,99,255,.2);border-radius:8px;padding:10px 14px;font-size:.8rem;color:var(--muted);margin-bottom:16px">Add RM <?=number_format(200-$subtotal,2)?> more for free shipping!</div>
    <?php endif; ?>
    <div class="summary-row total"><span>Total</span><span>RM <?=number_format($total,2)?></span></div>
    <button class="btn btn-primary btn-block" onclick="alert('Checkout feature coming soon! Thank you for shopping with TechZone 🎉')">Proceed to Checkout →</button>
    <a href="products.php" class="btn btn-outline btn-block" style="margin-top:10px">← Continue Shopping</a>
  </div>
</div>
<?php endif; ?>

<?php require 'footer.php'; ?>
