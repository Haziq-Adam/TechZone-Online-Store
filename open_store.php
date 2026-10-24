<?php
define('TECHZONE',1);
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){ header("Location: login.php?redirect=open_store.php"); exit(); }

$check = $conn->prepare("SELECT id FROM stores WHERE user_id=?");
$check->bind_param("i", $_SESSION['user_id']);
$check->execute();
if($check->get_result()->num_rows > 0){ header("Location: seller_dashboard.php"); exit(); }

$error = '';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $store_name = trim($_POST['store_name']??'');
    $store_desc = trim($_POST['store_desc']??'');
    $phone      = trim($_POST['phone']??'');
    $location   = trim($_POST['location']??'');
    if(!$store_name){ $error = "Store name is required."; }
    else {
        $stmt = $conn->prepare("INSERT INTO stores (user_id, store_name, store_description, phone, location) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", $_SESSION['user_id'], $store_name, $store_desc, $phone, $location);
        if($stmt->execute()){
            header("Location: seller_dashboard.php?msg=Your+store+is+live!"); exit();
        } else { $error = "Error creating store."; }
    }
}

$page_title = 'Open Your Store';
require 'header.php';
?>
<style>
.open-store-wrap{max-width:980px;margin:50px auto;padding:0 24px;display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start}
.os-left h1{font-family:'Outfit',sans-serif;font-size:2.8rem;font-weight:900;line-height:1.2;margin-bottom:16px}
.os-left p{color:var(--muted);font-size:1rem;line-height:1.7;margin-bottom:28px}
.os-benefit{display:flex;align-items:flex-start;gap:14px;margin-bottom:18px}
.os-benefit-icon{width:44px;height:44px;border-radius:12px;background:rgba(108,99,255,.15);border:1px solid rgba(108,99,255,.3);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
.os-benefit-text h4{font-size:.95rem;font-weight:700;margin-bottom:2px}
.os-benefit-text p{color:var(--muted);font-size:.82rem;margin:0}
.os-form{background:rgba(19,19,23,.85);border:1px solid var(--border);border-radius:20px;padding:32px;backdrop-filter:blur(12px)}
.fg{margin-bottom:18px}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.fg input,.fg textarea{width:100%;padding:13px 16px;border-radius:12px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:var(--text);font-family:'Inter',sans-serif;font-size:.95rem;outline:none;transition:all .3s;box-sizing:border-box}
.fg input:focus,.fg textarea:focus{background:rgba(255,255,255,.07);border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
@media(max-width:768px){.open-store-wrap{grid-template-columns:1fr}}
</style>

<div class="open-store-wrap fade-up">
  <div class="os-left">
    <div class="anim-float" style="font-size:3rem;margin-bottom:20px">🏪</div>
    <h1>Open Your <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Store</span> Today</h1>
    <p>Join sellers on TechZone and reach customers across Malaysia. Set up your store in under 2 minutes.</p>

    <div class="os-benefit">
      <div class="os-benefit-icon">⚡</div>
      <div class="os-benefit-text">
        <h4>Instant Setup</h4>
        <p>Your store goes live immediately after registration.</p>
      </div>
    </div>
    <div class="os-benefit">
      <div class="os-benefit-icon">🖼️</div>
      <div class="os-benefit-text">
        <h4>Full Product Control</h4>
        <p>Upload images, set pricing, manage stock — all in one place.</p>
      </div>
    </div>
    <div class="os-benefit">
      <div class="os-benefit-icon">📞</div>
      <div class="os-benefit-text">
        <h4>Direct Buyer Contact</h4>
        <p>Share your phone and WhatsApp so buyers can reach you.</p>
      </div>
    </div>
  </div>

  <div class="os-form">
    <h3 style="font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:800;margin-bottom:4px">Store Details</h3>
    <p style="color:var(--muted);font-size:.85rem;margin-bottom:24px">You can change these anytime in Store Settings.</p>

    <?php if($error): ?><div class="err">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
      <div class="fg">
        <label>Store Name *</label>
        <input type="text" name="store_name" placeholder="e.g. Gadget Pro Store" required>
      </div>
      <div class="fg">
        <label>Store Description</label>
        <textarea name="store_desc" rows="3" placeholder="Tell buyers what you sell..." style="resize:vertical"></textarea>
      </div>
      <div class="fg">
        <label>Phone / WhatsApp</label>
        <input type="text" name="phone" placeholder="01x-xxxxxxx">
      </div>
      <div class="fg">
        <label>Location</label>
        <input type="text" name="location" placeholder="e.g. Kuala Lumpur">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;padding:15px;font-size:1rem;border-radius:12px;margin-top:8px">🚀 Create My Store</button>
    </form>
  </div>
</div>
<?php require 'footer.php'; ?>
