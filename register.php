<?php
session_start();
require 'db.php';
$error = ''; $success = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']??'');
    $email    = trim($_POST['email']??'');
    $password = $_POST['password']??'';
    $confirm  = $_POST['confirm']??'';
    if(!$username||!$email||!$password||!$confirm){ $error="Please fill in all fields."; }
    elseif($password!==$confirm){ $error="Passwords do not match."; }
    elseif(strlen($password)<6){ $error="Password must be at least 6 characters."; }
    else{
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users(username,email,password,role) VALUES(?,?,?,'user')");
        $stmt->bind_param("sss",$username,$email,$hashed);
        try{ $stmt->execute(); $success = true; }
        catch(Exception $e){ $error = "Username or email already taken."; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Create Account – TechZone</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;color:#f0f0f5;min-height:100vh;display:flex;align-items:center;justify-content:center;background:transparent}
.shell{display:flex;width:min(800px,95vw);min-height:580px;border-radius:20px;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.6);border:1px solid rgba(255,255,255,0.05);z-index:1}

/* BACKGROUND FX */
.bg-fx{position:fixed;inset:0;z-index:-1;overflow:hidden;background:#0d0d0f}
.orb{position:absolute;border-radius:50%;filter:blur(100px);opacity:0.4;animation:float 12s ease-in-out infinite alternate}
.orb1{width:500px;height:500px;background:#6c63ff;top:-150px;left:-150px}
.orb2{width:400px;height:400px;background:#ff6584;bottom:-100px;right:-100px;animation-delay:-4s}
.orb3{width:300px;height:300px;background:#c084fc;top:30%;left:40%;animation-delay:-8s}
@keyframes float{0%{transform:translate(0,0)}100%{transform:translate(40px,60px)}}

/* LEFT VISUAL */
.vis{flex:1;position:relative;overflow:hidden}
.vis-bg{position:absolute;inset:0;background:url('auth_bg.png') center/cover;filter:hue-rotate(30deg) saturate(1.2)}
.vis-overlay{position:absolute;inset:0;background:linear-gradient(160deg,rgba(255,101,132,.3) 0%,rgba(13,13,15,.8) 100%)}
/* Animated scan line */
.vis-scan{position:absolute;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(108,99,255,.4),transparent);animation:scanDown 6s linear infinite}
@keyframes scanDown{0%{top:-5%}100%{top:105%}}
.vis-brand{position:absolute;top:28px;left:28px;font-size:1rem;font-weight:700;color:rgba(255,255,255,.9)}
.vis-brand span{background:linear-gradient(135deg,#6c63ff,#ff6584);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.vis-back{position:absolute;top:28px;right:28px;display:flex;align-items:center;gap:6px;font-size:.78rem;color:rgba(255,255,255,.6);padding:7px 14px;border:1px solid rgba(255,255,255,.15);border-radius:20px;backdrop-filter:blur(8px);text-decoration:none;transition:all .2s}
.vis-back:hover{color:#fff;border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.06)}
.vis-content{position:absolute;bottom:0;left:0;right:0;padding:36px;z-index:2}
.vis-tagline{font-size:1.5rem;font-weight:700;line-height:1.3;color:#fff;margin-bottom:8px}
.vis-sub{font-size:.82rem;color:rgba(255,255,255,.45);margin-bottom:20px}
.vis-dots{display:flex;gap:7px}
.dot{width:24px;height:3px;border-radius:2px;background:rgba(255,255,255,.25)}
.dot.active{background:rgba(255,255,255,.85);width:32px}

/* FORM PANEL */
.form-panel{flex:1;background:rgba(19,19,23,0.85);backdrop-filter:blur(10px);padding:44px 36px;display:flex;flex-direction:column;justify-content:center}
.fp-title{font-size:1.7rem;font-weight:700;margin-bottom:6px;letter-spacing:-.3px}
.fp-sub{font-size:.82rem;color:rgba(255,255,255,.4);margin-bottom:24px}

.inp{width:100%;padding:13px 18px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;color:#fff;font-family:'Inter',sans-serif;font-size:.88rem;outline:none;transition:all .3s;margin-bottom:10px}
.inp::placeholder{color:rgba(255,255,255,.3)}
.inp:focus{border-color:#6c63ff;background:rgba(255,255,255,0.07);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.inp-wrap{position:relative;margin-bottom:10px}
.inp-wrap .inp{margin-bottom:0}
.inp-wrap .eye{position:absolute;right:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.35);cursor:pointer;font-size:.85rem;user-select:none}
.row2{display:flex;gap:10px;margin-bottom:10px}
.row2 .inp{margin-bottom:0;flex:1}
.tos{display:flex;align-items:center;gap:8px;font-size:.78rem;color:rgba(255,255,255,.4);cursor:pointer;margin-bottom:18px;padding-top:2px}
.tos a{color:#6c63ff;text-decoration:none;transition:color .2s}
.tos a:hover{color:#ff6584}
.tos input{accent-color:#6c63ff;width:15px;height:15px;flex-shrink:0}

.btn-main{width:100%;padding:14px;background:linear-gradient(135deg,#6c63ff,#ff6584);border:none;border-radius:10px;color:#fff;font-size:.9rem;font-weight:600;cursor:pointer;transition:opacity .2s,transform .2s;margin-bottom:18px;font-family:'Inter',sans-serif}
.btn-main:hover{opacity:.9;transform:translateY(-1px)}

.bottom-link{text-align:center;font-size:.85rem;color:rgba(255,255,255,.5);margin-top:10px}
.bottom-link a{color:#6c63ff;font-weight:600;text-decoration:none;transition:color .2s}
.bottom-link a:hover{color:#ff6584}

.err{color:#f87171;font-size:.78rem;margin-bottom:12px;padding:9px 14px;background:rgba(248,113,113,.08);border-radius:8px;border-left:3px solid #f87171}
.suc{color:#00d68f;font-size:.82rem;margin-bottom:12px;padding:9px 14px;background:rgba(0,214,143,.08);border-radius:8px;border-left:3px solid #00d68f}
.suc a{color:#6c63ff;font-weight:600;text-decoration:none}

@media(max-width:640px){.shell{flex-direction:column;border-radius:16px}.vis{min-height:200px;flex:none}.form-panel{padding:30px 24px}}
</style>
</head>
<body>
<div class="bg-fx">
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>
</div>

<div class="shell">
  <!-- LEFT: Visual (same as login but different gradient accent) -->
  <div class="vis">
    <div class="vis-bg"></div>
    <div class="vis-overlay"></div>
    <div class="vis-scan"></div>
    <div class="vis-brand"><span>⚡ TechZone</span></div>
    <a href="index.php" class="vis-back">← Back to site</a>
    <div class="vis-content">
      <div class="vis-tagline">Shop Smart,<br>Live Premium.</div>
      <div class="vis-sub">Join 50,000+ satisfied customers</div>
      <div class="vis-dots">
        <div class="dot"></div>
        <div class="dot active"></div>
        <div class="dot"></div>
      </div>
    </div>
  </div>

  <!-- RIGHT: Form -->
  <div class="form-panel">
    <div class="fp-title">Create an account</div>
    <div class="fp-sub">Join Malaysia's premier electronics community</div>

    <?php if($error): ?><div class="err">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if($success): ?><div class="suc">✓ Account created! <a href="login.php">Sign in now</a></div><?php endif; ?>

    <?php if(!$success): ?>
    <form method="POST">
      <div class="row2">
        <input class="inp" type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username']??'') ?>">
        <input class="inp" type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
      </div>
      <div class="inp-wrap">
        <input class="inp" type="password" name="password" placeholder="Password" id="pw" required>
        <span class="eye" onclick="togglePw('pw')">👁</span>
      </div>
      <div class="inp-wrap">
        <input class="inp" type="password" name="confirm" placeholder="Confirm password" id="cpw" required>
        <span class="eye" onclick="togglePw('cpw')">👁</span>
      </div>
      <label class="tos">
        <input type="checkbox" required>
        I agree to the <a href="#">Terms &amp; Conditions</a>
      </label>
      <button type="submit" class="btn-main">Create account</button>
    </form>
    <?php endif; ?>

    <div class="bottom-link">
      Already have an account? <a href="login.php">Sign in instead</a>
    </div>
  </div>
</div>

<script>
function togglePw(id){
  const f = document.getElementById(id);
  f.type = f.type==='password' ? 'text' : 'password';
}
</script>
</body>
</html>
