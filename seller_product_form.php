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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null; $error = '';

if($id){
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND store_id=?");
    $stmt->bind_param("ii", $id, $store_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    if(!$product){ header("Location: seller_dashboard.php"); exit(); }
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name       = trim($_POST['name']??'');
    $cat_id     = intval($_POST['category_id']??0);
    $price      = floatval($_POST['price']??0);
    $orig_price = floatval($_POST['original_price']??0);
    $stock      = intval($_POST['stock']??0);
    $desc       = trim($_POST['description']??'');
    $specs      = trim($_POST['specs']??'');
    $badge      = trim($_POST['badge']??'');
    $hue        = intval($_POST['hue']??0);
    $img        = trim($_POST['image_url_manual']??'') ?: ($product['image_url'] ?? '');

    // Real file upload takes priority
    if(!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error']===0){
        $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, ['jpg','jpeg','png','webp','gif'])){
            $filename = 'prod_'.$store_id.'_'.time().'.'.$ext;
            $dest = 'uploads/products/'.$filename;
            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $dest)){
                $img = $dest;
            } else { $error = "Upload failed. Check folder permissions."; }
        } else { $error = "Invalid image type."; }
    }

    if(!$error && (!$name || !$price)){ $error = "Product name and price are required."; }

    if(!$error){
        if($id){
            $st = $conn->prepare("UPDATE products SET name=?,category_id=?,price=?,original_price=?,description=?,specs=?,stock=?,image_url=?,badge=?,hue=? WHERE id=? AND store_id=?");
            $st->bind_param("siddssissiii",$name,$cat_id,$price,$orig_price,$desc,$specs,$stock,$img,$badge,$hue,$id,$store_id);
            $st->execute();
            header("Location: seller_dashboard.php?msg=Product+updated"); exit();
        } else {
            $st = $conn->prepare("INSERT INTO products (store_id,name,category_id,price,original_price,description,specs,stock,image_url,badge,hue) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $st->bind_param("isiddssissi",$store_id,$name,$cat_id,$price,$orig_price,$desc,$specs,$stock,$img,$badge,$hue);
            $st->execute();
            header("Location: seller_dashboard.php?msg=Product+added"); exit();
        }
    }
}

$cats = $conn->query("SELECT * FROM categories ORDER BY name");
$cats_arr = [];
while($c = $cats->fetch_assoc()) $cats_arr[] = $c;

$page_title = $id ? 'Edit Product' : 'Add Product';
require 'header.php';
?>
<style>
.form-page-wrap{max-width:880px;margin:40px auto;padding:0 24px}
.fcard{background:rgba(19,19,23,.85);border:1px solid var(--border);border-radius:20px;padding:30px;margin-bottom:20px;backdrop-filter:blur(12px)}
.fsec{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:1px;padding-bottom:12px;border-bottom:1px solid var(--border);margin-bottom:20px}
.fg{margin-bottom:18px}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.fg input,.fg select,.fg textarea{width:100%;padding:13px 16px;border-radius:12px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:var(--text);font-family:'Inter',sans-serif;font-size:.95rem;outline:none;transition:all .3s;box-sizing:border-box}
.fg input:focus,.fg select:focus,.fg textarea:focus{background:rgba(255,255,255,.07);border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.fg select option{background:#13131a}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.uploader{border:2px dashed rgba(108,99,255,.4);border-radius:14px;padding:24px;text-align:center;cursor:pointer;transition:all .2s;position:relative}
.uploader:hover{border-color:var(--accent);background:rgba(108,99,255,.05)}
.uploader input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
#imgPrev{width:100%;max-height:220px;object-fit:cover;border-radius:10px;margin-top:14px;display:none}
</style>

<div class="form-page-wrap fade-up">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <h2 style="font-family:'Outfit',sans-serif;font-weight:800"><?= $id ? '✏️ Edit Product' : '➕ Add Product' ?></h2>
    <a href="seller_dashboard.php" class="btn btn-outline btn-sm">← Back</a>
  </div>

  <?php if($error): ?><div class="err">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data">

    <div class="fcard">
      <div class="fsec">🖼️ Product Image</div>
      <div class="uploader" id="uploader">
        <input type="file" name="image_file" id="imgFile" accept="image/*" onchange="prevImg(this)">
        <div id="upPh">
          <div style="font-size:2.5rem;margin-bottom:8px">📷</div>
          <div style="font-weight:600;margin-bottom:4px">Click or drag &amp; drop to upload</div>
          <div style="font-size:.82rem;color:var(--muted)">JPG · PNG · WEBP · GIF</div>
        </div>
        <img id="imgPrev" src="">
      </div>
      <div class="fg" style="margin-top:14px">
        <label>Or paste an image URL</label>
        <input type="url" name="image_url_manual" placeholder="https://..." value="<?= htmlspecialchars(!empty($product['image_url'])&&strpos($product['image_url'],'uploads/')===false?$product['image_url']:'') ?>">
      </div>
    </div>

    <div class="fcard">
      <div class="fsec">📋 Product Info</div>
      <div class="fg">
        <label>Product Name *</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($product['name']??'') ?>" placeholder="e.g. Samsung Galaxy S24">
      </div>
      <div class="g2">
        <div class="fg">
          <label>Category *</label>
          <select name="category_id" required>
            <?php foreach($cats_arr as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($product['category_id']??0)==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg">
          <label>Badge (New / Sale / Hot)</label>
          <input type="text" name="badge" value="<?= htmlspecialchars($product['badge']??'') ?>" placeholder="New">
        </div>
      </div>
      <div class="fg">
        <label>Short Specs</label>
        <input type="text" name="specs" value="<?= htmlspecialchars($product['specs']??'') ?>" placeholder="16GB RAM | 512GB SSD">
      </div>
      <div class="fg">
        <label>Full Description</label>
        <textarea name="description" rows="5" style="resize:vertical"><?= htmlspecialchars($product['description']??'') ?></textarea>
      </div>
    </div>

    <div class="fcard">
      <div class="fsec">💰 Pricing &amp; Stock</div>
      <div class="g2">
        <div class="fg">
          <label>Selling Price (RM) *</label>
          <input type="number" step="0.01" name="price" required value="<?= $product['price']??'' ?>" placeholder="0.00">
        </div>
        <div class="fg">
          <label>Original Price (strikethrough)</label>
          <input type="number" step="0.01" name="original_price" value="<?= $product['original_price']??'' ?>" placeholder="0.00">
        </div>
      </div>
      <div class="g2">
        <div class="fg">
          <label>Stock Quantity *</label>
          <input type="number" name="stock" required value="<?= $product['stock']??10 ?>" placeholder="10">
        </div>
        <div class="fg">
          <label>Color Hue (0–360) for default visual</label>
          <input type="number" name="hue" value="<?= $product['hue']??0 ?>" min="0" max="360">
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;padding:16px;font-size:1.05rem;border-radius:14px">
      <?= $id ? '💾 Save Changes' : '🚀 Create Product' ?>
    </button>
  </form>
</div>

<script>
function prevImg(input){
    if(!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('imgPrev').src = e.target.result;
        document.getElementById('imgPrev').style.display = 'block';
        document.getElementById('upPh').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
// Show existing uploaded image
(function(){
    <?php if(!empty($product['image_url'])): ?>
    const prev = document.getElementById('imgPrev');
    prev.src = '<?= htmlspecialchars($product['image_url']) ?>';
    prev.style.display = 'block';
    document.getElementById('upPh').style.display = 'none';
    <?php endif; ?>
})();
</script>
<?php require 'footer.php'; ?>
