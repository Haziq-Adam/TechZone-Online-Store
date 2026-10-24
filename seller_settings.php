<?php
define('TECHZONE',1);
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM stores WHERE user_id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$store = $stmt->get_result()->fetch_assoc();
if(!$store){ header("Location: open_store.php"); exit(); }
$store_id = $store['id'];
$error = ''; $success = '';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $store_name = trim($_POST['store_name']??'');
    $store_desc = trim($_POST['store_description']??'');
    $phone      = trim($_POST['phone']??'');
    $whatsapp   = trim($_POST['whatsapp']??'');
    $location   = trim($_POST['location']??'');
    $logo_url   = $store['logo_url'] ?? '';
    $banner_url = $store['banner_url'] ?? '';

    // Logo upload
    if(!empty($_FILES['logo_file']['name']) && $_FILES['logo_file']['error']===0){
        $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
        if(in_array($ext,['jpg','jpeg','png','webp','gif'])){
            $fn = 'store_logo_'.$store_id.'_'.time().'.'.$ext;
            move_uploaded_file($_FILES['logo_file']['tmp_name'], 'uploads/stores/'.$fn);
            $logo_url = 'uploads/stores/'.$fn;
        }
    } elseif(trim($_POST['logo_url_manual']??'')){
        $logo_url = trim($_POST['logo_url_manual']);
    }

    // Banner upload
    if(!empty($_FILES['banner_file']['name']) && $_FILES['banner_file']['error']===0){
        $ext = strtolower(pathinfo($_FILES['banner_file']['name'], PATHINFO_EXTENSION));
        if(in_array($ext,['jpg','jpeg','png','webp','gif'])){
            $fn = 'store_banner_'.$store_id.'_'.time().'.'.$ext;
            move_uploaded_file($_FILES['banner_file']['tmp_name'], 'uploads/stores/'.$fn);
            $banner_url = 'uploads/stores/'.$fn;
        }
    } elseif(trim($_POST['banner_url_manual']??'')){
        $banner_url = trim($_POST['banner_url_manual']);
    }

    if(!$store_name){ $error = "Store name is required."; }
    else {
        $st = $conn->prepare("UPDATE stores SET store_name=?,store_description=?,phone=?,whatsapp=?,location=?,logo_url=?,banner_url=? WHERE id=?");
        $st->bind_param("sssssssi",$store_name,$store_desc,$phone,$whatsapp,$location,$logo_url,$banner_url,$store_id);
        if($st->execute()){
            // Refresh store data
            $stmt2 = $conn->prepare("SELECT * FROM stores WHERE id=?");
            $stmt2->bind_param("i",$store_id);
            $stmt2->execute();
            $store = $stmt2->get_result()->fetch_assoc();
            $success = "Store settings saved successfully!";
        } else { $error = "Save failed."; }
    }
}

$page_title = 'Store Settings';
require 'header.php';
?>
<style>
.settings-wrap{max-width:780px;margin:40px auto;padding:0 24px}
.scard{background:rgba(19,19,23,.85);border:1px solid var(--border);border-radius:20px;padding:30px;margin-bottom:20px;backdrop-filter:blur(12px)}
.ssec{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:1px;padding-bottom:12px;border-bottom:1px solid var(--border);margin-bottom:20px}
.fg{margin-bottom:18px}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.fg input,.fg select,.fg textarea{width:100%;padding:13px 16px;border-radius:12px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:var(--text);font-family:'Inter',sans-serif;font-size:.95rem;outline:none;transition:all .3s;box-sizing:border-box}
.fg input:focus,.fg select:focus,.fg textarea:focus{background:rgba(255,255,255,.07);border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.mini-uploader{border:2px dashed rgba(255,255,255,.1);border-radius:12px;padding:18px;text-align:center;cursor:pointer;transition:all .2s;position:relative}
.mini-uploader:hover{border-color:var(--accent)}
.mini-uploader input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.preview-thumb{width:80px;height:80px;border-radius:12px;object-fit:cover;margin:0 auto 10px;display:block}
.preview-banner{width:100%;height:100px;object-fit:cover;border-radius:10px;margin-top:10px;display:none}
</style>

<div class="settings-wrap fade-up">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <h2 style="font-family:'Outfit',sans-serif;font-weight:800">⚙️ Store Settings</h2>
    <a href="seller_dashboard.php" class="btn btn-outline btn-sm">← Back</a>
  </div>

  <?php if($error): ?><div class="err">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if($success): ?><div class="suc">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data">

    <div class="scard">
      <div class="ssec">🏪 Store Identity</div>
      <div class="fg">
        <label>Store Name *</label>
        <input type="text" name="store_name" required value="<?= htmlspecialchars($store['store_name']) ?>">
      </div>
      <div class="fg">
        <label>Store Description</label>
        <textarea name="store_description" rows="3" style="resize:vertical"><?= htmlspecialchars($store['store_description']??'') ?></textarea>
      </div>
    </div>

    <div class="scard">
      <div class="ssec">🖼️ Store Visuals</div>
      <div class="g2">
        <div class="fg">
          <label>Store Logo</label>
          <div class="mini-uploader">
            <input type="file" name="logo_file" accept="image/*" onchange="prevLogo(this)">
            <?php if(!empty($store['logo_url'])): ?>
              <img src="<?= htmlspecialchars($store['logo_url']) ?>" class="preview-thumb" id="logoPrev">
            <?php else: ?>
              <div id="logoPrev" style="font-size:2rem;margin-bottom:6px">🏪</div>
            <?php endif; ?>
            <div style="font-size:.8rem;color:var(--muted)">Upload Logo</div>
          </div>
          <div class="fg" style="margin-top:10px">
            <input type="url" name="logo_url_manual" placeholder="Or paste logo URL" value="<?= htmlspecialchars(!empty($store['logo_url'])&&strpos($store['logo_url'],'http')===0?$store['logo_url']:'') ?>">
          </div>
        </div>
        <div class="fg">
          <label>Store Banner</label>
          <div class="mini-uploader" style="height:auto;min-height:80px">
            <input type="file" name="banner_file" accept="image/*" onchange="prevBanner(this)">
            <?php if(!empty($store['banner_url'])): ?>
              <img src="<?= htmlspecialchars($store['banner_url']) ?>" class="preview-banner" style="display:block" id="bannerPrev">
            <?php else: ?>
              <div style="font-size:.8rem;color:var(--muted);padding:10px 0" id="bannerPrev">Upload Banner Image</div>
            <?php endif; ?>
          </div>
          <div class="fg" style="margin-top:10px">
            <input type="url" name="banner_url_manual" placeholder="Or paste banner URL" value="<?= htmlspecialchars(!empty($store['banner_url'])&&strpos($store['banner_url'],'http')===0?$store['banner_url']:'') ?>">
          </div>
        </div>
      </div>
    </div>

    <div class="scard">
      <div class="ssec">📞 Contact Info <span style="font-weight:400;text-transform:none;font-size:.85rem">(shown to buyers)</span></div>
      <div class="g2">
        <div class="fg">
          <label>Phone Number</label>
          <input type="text" name="phone" placeholder="e.g. 01x-xxxxxxx" value="<?= htmlspecialchars($store['phone']??'') ?>">
        </div>
        <div class="fg">
          <label>WhatsApp Number</label>
          <input type="text" name="whatsapp" placeholder="e.g. 601xxxxxxxx" value="<?= htmlspecialchars($store['whatsapp']??'') ?>">
        </div>
      </div>
      <div class="fg">
        <label>Location / Address</label>
        <input type="text" name="location" placeholder="e.g. Kuala Lumpur, Malaysia" value="<?= htmlspecialchars($store['location']??'') ?>">
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;padding:16px;font-size:1.05rem;border-radius:14px">💾 Save Settings</button>
  </form>
</div>
<script>
function prevLogo(input){
    if(!input.files[0]) return;
    const r = new FileReader();
    r.onload = e => {
        const el = document.getElementById('logoPrev');
        el.innerHTML='';
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'preview-thumb';
        el.parentNode.insertBefore(img, el);
        el.remove();
    };
    r.readAsDataURL(input.files[0]);
}
function prevBanner(input){
    if(!input.files[0]) return;
    const r = new FileReader();
    r.onload = e => {
        const el = document.getElementById('bannerPrev');
        if(el.tagName==='IMG'){ el.src=e.target.result; el.style.display='block'; }
        else {
            const img = document.createElement('img');
            img.src=e.target.result; img.className='preview-banner'; img.style.display='block';
            el.parentNode.insertBefore(img, el); el.remove();
        }
    };
    r.readAsDataURL(input.files[0]);
}
</script>
<?php require 'footer.php'; ?>
