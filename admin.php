<?php
define('TECHZONE', 1);
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_type'], $_POST['delete_id'])) {
    if (!hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
        http_response_code(403);
        exit('Invalid form token. Reload the page.');
    }
    $id = (int) $_POST['delete_id'];
    if ($id > 0 && $_POST['delete_type'] === 'product') {
        $stmt = $conn->prepare('DELETE FROM products WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: admin.php?tab=products');
        exit;
    }
    if ($id > 0 && $_POST['delete_type'] === 'user' && $id !== (int)$_SESSION['user_id']) {
        $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: admin.php?tab=users');
        exit;
    }
}
$page_title = 'Admin Panel';
require 'header.php';
$tab = $_GET['tab'] ?? 'dashboard';

// Stats
$total_users = $conn->query("SELECT COUNT(*) as n FROM users")->fetch_assoc()['n'];
$total_products = $conn->query("SELECT COUNT(*) as n FROM products")->fetch_assoc()['n'];
$total_orders = $conn->query("SELECT COALESCE(SUM(quantity),0) as n FROM cart")->fetch_assoc()['n'];
$total_revenue = $conn->query("SELECT COALESCE(SUM(p.price*c.quantity),0) as n FROM cart c JOIN products p ON c.product_id=p.id")->fetch_assoc()['n'];
?>

<div class="admin-layout">
  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div style="padding:20px 24px;border-bottom:1px solid var(--border);margin-bottom:8px">
      <div style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:1px">Admin Panel</div>
      <div style="font-size:.9rem;font-weight:600;margin-top:4px">⚙️ <?=htmlspecialchars($_SESSION['username'] ?? 'Admin')?></div>
    </div>
    <nav class="sidebar-nav">
      <a href="admin.php?tab=dashboard" class="<?=$tab=='dashboard'?'active':''?>"><span class="s-icon">📊</span>Dashboard</a>
      <a href="admin.php?tab=products" class="<?=$tab=='products'?'active':''?>"><span class="s-icon">📦</span>Products</a>
      <a href="admin.php?tab=users" class="<?=$tab=='users'?'active':''?>"><span class="s-icon">👥</span>Users</a>
      <a href="admin.php?tab=orders" class="<?=$tab=='orders'?'active':''?>"><span class="s-icon">🛒</span>Cart Orders</a>
      <div style="height:1px;background:var(--border);margin:12px 24px"></div>
      <a href="index.php"><span class="s-icon">🏠</span>View Website</a>
      <a href="logout.php"><span class="s-icon">🚪</span>Logout</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="admin-main">
    <?php if($tab=='dashboard'): ?>
    <div style="margin-bottom:28px">
      <h1 style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:800">📊 Dashboard</h1>
      <p style="color:var(--muted);margin-top:4px">Welcome back, <?=htmlspecialchars($_SESSION['username'] ?? 'Admin')?>! Here's your store overview.</p>
    </div>

    <div class="admin-cards">
      <div class="admin-card"><div class="ac-icon">👥</div><div class="ac-num"><?=$total_users?></div><div class="ac-label">Total Users</div></div>
      <div class="admin-card"><div class="ac-icon">📦</div><div class="ac-num"><?=$total_products?></div><div class="ac-label">Total Products</div></div>
      <div class="admin-card"><div class="ac-icon">🛒</div><div class="ac-num"><?=$total_orders?></div><div class="ac-label">Items in Carts</div></div>
      <div class="admin-card"><div class="ac-icon">💰</div><div class="ac-num" style="font-size:1.4rem">RM <?=number_format($total_revenue,0)?></div><div class="ac-label">Cart Value</div></div>
    </div>

    <!-- Top products -->
    <div class="table-wrap">
      <div class="table-head"><h3>🔥 Top Products by Reviews</h3></div>
      <table class="data-table">
        <thead><tr><th>#</th><th>Product</th><th>Category</th><th>Price</th><th>Rating</th><th>Reviews</th><th>Stock</th></tr></thead>
        <tbody>
          <?php $tp = $conn->query("SELECT p.*,c.name as cat FROM products p JOIN categories c ON p.category_id=c.id ORDER BY reviews_count DESC LIMIT 8");
          $i=1; while($r=$tp->fetch_assoc()): ?>
          <tr>
            <td style="color:var(--muted)"><?=$i++?></td>
            <td><strong><?=htmlspecialchars($r['name'])?></strong></td>
            <td><?=htmlspecialchars($r['cat'])?></td>
            <td>RM <?=number_format($r['price'],2)?></td>
            <td>⭐ <?=$r['rating']?></td>
            <td><?=number_format($r['reviews_count'])?></td>
            <td><span style="color:<?=$r['stock']<10?'#ff5555':'var(--green)'?>"><?=$r['stock']?></span></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php elseif($tab=='products'): ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
      <h1 style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:800">📦 Products</h1>
    </div>
    <div class="table-wrap">
      <div class="table-head"><h3>All Products (<?=$total_products?>)</h3></div>
      <table class="data-table">
        <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Rating</th><th>Stock</th><th>Actions</th></tr></thead>
        <tbody>
          <?php $all = $conn->query("SELECT p.*,c.name as cat FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.id");
          while($r=$all->fetch_assoc()): ?>
          <tr>
            <td style="color:var(--muted)">#<?=$r['id']?></td>
            <td><strong><?=htmlspecialchars($r['name'])?></strong><?php if($r['badge']): ?> <span class="badge badge-hot" style="font-size:.65rem"><?=htmlspecialchars($r['badge'])?></span><?php endif; ?></td>
            <td><?=htmlspecialchars($r['cat'])?></td>
            <td>RM <?=number_format($r['price'],2)?></td>
            <td>⭐ <?=$r['rating']?></td>
            <td><?=$r['stock']?></td>
            <td>
              <a href="product.php?id=<?=$r['id']?>" class="btn btn-outline btn-sm">View</a>
              <form method="post" style="display:inline" onsubmit="return confirm('Delete this product?')">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                <input type="hidden" name="delete_type" value="product">
                <input type="hidden" name="delete_id" value="<?=$r['id']?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php elseif($tab=='users'): ?>
    <h1 style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:800;margin-bottom:24px">👥 Users</h1>
    <div class="table-wrap">
      <div class="table-head"><h3>All Users (<?=$total_users?>)</h3></div>
      <table class="data-table">
        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
          <?php $all = $conn->query("SELECT * FROM users ORDER BY id");
          while($r=$all->fetch_assoc()): ?>
          <tr>
            <td style="color:var(--muted)">#<?=$r['id']?></td>
            <td><strong><?=htmlspecialchars($r['username'])?></strong></td>
            <td><?=htmlspecialchars($r['email'])?></td>
            <td><span class="badge <?=$r['role']=='admin'?'badge-hot':'badge-new'?>"><?=$r['role']?></span></td>
            <td style="color:var(--muted);font-size:.82rem"><?=date('d M Y',strtotime($r['created_at']))?></td>
            <td>
              <?php if($r['id']!=$_SESSION['user_id']): ?>
              <form method="post" style="display:inline" onsubmit="return confirm('Delete user?')">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                <input type="hidden" name="delete_type" value="user">
                <input type="hidden" name="delete_id" value="<?=$r['id']?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
              <?php else: ?><span style="color:var(--muted);font-size:.8rem">You</span><?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php elseif($tab=='orders'): ?>
    <h1 style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:800;margin-bottom:24px">🛒 Cart Orders</h1>
    <div class="table-wrap">
      <div class="table-head"><h3>All Cart Items</h3></div>
      <table class="data-table">
        <thead><tr><th>User</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th><th>Added</th></tr></thead>
        <tbody>
          <?php $all = $conn->query("SELECT c.*,u.username,p.name as pname,p.price FROM cart c JOIN users u ON c.user_id=u.id JOIN products p ON c.product_id=p.id ORDER BY c.created_at DESC");
          while($r=$all->fetch_assoc()): ?>
          <tr>
            <td><?=htmlspecialchars($r['username'])?></td>
            <td><?=htmlspecialchars($r['pname'])?></td>
            <td><?=$r['quantity']?></td>
            <td>RM <?=number_format($r['price'],2)?></td>
            <td><strong>RM <?=number_format($r['price']*$r['quantity'],2)?></strong></td>
            <td style="color:var(--muted);font-size:.8rem"><?=date('d M Y H:i',strtotime($r['created_at']))?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </main>
</div>

<?php require 'footer.php'; ?>
