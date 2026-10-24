<?php
session_start();
require 'db.php';
$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';
// Avoid redirecting users to an external site after login.
if (!preg_match('/^[a-zA-Z0-9_-]+\.php(?:\?[a-zA-Z0-9_=&%-]*)?$/D', $redirect)) {
    $redirect = 'index.php';
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    if($login && $password){
        $stmt = $conn->prepare("SELECT id,username,password,role FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss",$login,$login); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if($user && password_verify($password,$user['password'])){
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ".($user['role']==='admin' ? 'admin.php' : $redirect));
            exit();
        }
        $error = "Incorrect email or password.";
    } else { $error = "Please fill in all fields."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Sign In – TechZone</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;color:#f0f0f5;min-height:100vh;display:flex;align-items:center;justify-content:center;background:transparent}
.shell{display:flex;width:min(800px,95vw);min-height:560px;border-radius:20px;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.6);border:1px solid rgba(255,255,255,0.05);z-index:1}

/* BACKGROUND FX */
.bg-fx{position:fixed;inset:0;z-index:-1;overflow:hidden;background:#0d0d0f}
.orb{position:absolute;border-radius:50%;filter:blur(100px);opacity:0.4;animation:float 12s ease-in-out infinite alternate}
.orb1{width:500px;height:500px;background:#6c63ff;top:-150px;left:-150px}
.orb2{width:400px;height:400px;background:#ff6584;bottom:-100px;right:-100px;animation-delay:-4s}
.orb3{width:300px;height:300px;background:#c084fc;top:30%;left:40%;animation-delay:-8s}
@keyframes float{0%{transform:translate(0,0)}100%{transform:translate(40px,60px)}}

/* LEFT PANEL */
.vis{flex:1;position:relative;overflow:hidden}
.vis-bg{position:absolute;inset:0;background:url('auth_bg.png') center/cover}
.vis-overlay{position:absolute;inset:0;background:linear-gradient(160deg,rgba(108,99,255,.4) 0%,rgba(13,13,15,.8) 100%)}
/* Animated shimmer layer */
.vis-shimmer{position:absolute;inset:0;background:linear-gradient(120deg,transparent 0%,rgba(255,101,132,.1) 50%,transparent 100%);animation:shimPass 4s ease-in-out infinite}
@keyframes shimPass{0%,100%{opacity:0;transform:translateX(-100%)}50%{opacity:1;transform:translateX(100%)}}
.vis-content{position:absolute;bottom:0;left:0;right:0;padding:36px;z-index:2}
.vis-brand{font-size:1rem;font-weight:700;letter-spacing:.5px;color:rgba(255,255,255,.9);margin-bottom:auto;position:absolute;top:28px;left:28px;display:flex;align-items:center;gap:7px}
.vis-brand span{background:linear-gradient(135deg,#6c63ff,#ff6584);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.vis-back{position:absolute;top:28px;right:28px;display:flex;align-items:center;gap:6px;font-size:.78rem;color:rgba(255,255,255,.6);padding:7px 14px;border:1px solid rgba(255,255,255,.15);border-radius:20px;backdrop-filter:blur(8px);transition:all .2s;text-decoration:none}
.vis-back:hover{color:#fff;border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.06)}
.vis-tagline{font-size:1.5rem;font-weight:700;line-height:1.3;color:#fff;margin-bottom:8px}
.vis-sub{font-size:.82rem;color:rgba(255,255,255,.5);margin-bottom:20px}
/* Slider dots */
.vis-dots{display:flex;gap:7px}
.dot{width:24px;height:3px;border-radius:2px;background:rgba(255,255,255,.25)}
.dot.active{background:rgba(255,255,255,.85);width:32px}

/* RIGHT PANEL */
.form-panel{flex:1;background:rgba(19,19,23,0.85);backdrop-filter:blur(10px);padding:44px 36px;display:flex;flex-direction:column;justify-content:center}
.fp-title{font-size:1.7rem;font-weight:700;margin-bottom:6px;letter-spacing:-.3px}
.fp-sub{font-size:.82rem;color:rgba(255,255,255,.4);margin-bottom:28px}

/* Inputs */
.inp{width:100%;padding:14px 18px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;color:#fff;font-family:'Inter',sans-serif;font-size:.9rem;outline:none;transition:all .3s;margin-bottom:12px}
.inp::placeholder{color:rgba(255,255,255,.3)}
.inp:focus{border-color:#6c63ff;background:rgba(255,255,255,0.07);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.inp-wrap{position:relative}
.inp-wrap .eye{position:absolute;right:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.35);cursor:pointer;font-size:.9rem;user-select:none}

.row-extra{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:.78rem}
.row-extra label{display:flex;align-items:center;gap:7px;color:rgba(255,255,255,.45);cursor:pointer}
.row-extra a{color:#6c63ff;text-decoration:none;transition:color .2s}
.row-extra a:hover{color:#ff6584}

.btn-main{width:100%;padding:14px;background:linear-gradient(135deg,#6c63ff,#ff6584);border:none;border-radius:10px;color:#fff;font-size:.9rem;font-weight:600;cursor:pointer;transition:opacity .2s,transform .2s;margin-bottom:20px;font-family:'Inter',sans-serif}
.btn-main:hover{opacity:.9;transform:translateY(-1px)}

.bottom-link{text-align:center;font-size:.85rem;color:rgba(255,255,255,.5);margin-top:10px}
.bottom-link a{color:#6c63ff;font-weight:600;text-decoration:none;transition:color .2s}
.bottom-link a:hover{color:#ff6584}

.err{color:#f87171;font-size:.8rem;margin-bottom:14px;padding:10px 14px;background:rgba(248,113,113,.08);border-radius:8px;border-left:3px solid #f87171}

@media(max-width:640px){.shell{flex-direction:column;border-radius:16px}.vis{min-height:200px;flex:none}.vis-content{bottom:0}.form-panel{padding:32px 28px}}
</style>
</head>
<body>
<div class="bg-fx">
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>
</div>

<div class="shell">
  <!-- LEFT: Visual -->
  <div class="vis">
    <div class="vis-bg"></div>
    <div class="vis-overlay"></div>
    <div class="vis-shimmer"></div>
    <a href="index.php" class="vis-back">← Back to site</a>
    <div class="vis-brand"><span>⚡ TechZone</span></div>
    <div class="vis-content">
      <div class="vis-tagline">Malaysia's Premium<br>Electronics Store.</div>
      <div class="vis-sub">Trusted by 50,000+ customers</div>
      <div class="vis-dots">
        <div class="dot active"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
    </div>
  </div>

  <!-- RIGHT: Form -->
  <div class="form-panel">
    <div class="fp-title">Welcome back</div>
    <div class="fp-sub">Sign in to your TechZone account</div>

    <?php if($error): ?><div class="err">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
      <input class="inp" type="text" name="login" placeholder="Email or username" required value="<?= htmlspecialchars($_POST['login']??'') ?>">
      <div class="inp-wrap">
        <input class="inp" type="password" name="password" placeholder="Password" id="pw" required >
        <span class="eye" onclick="togglePw()">👁</span>
      </div>
      <div class="row-extra">
        <label><input type="checkbox" style="accent-color:#7c6df5"> Remember me</label>
        <a href="#">Forgot password?</a>
      </div>
      <button type="submit" class="btn-main">Sign in</button>
    </form>

    <div class="bottom-link">
      New here? <a href="register.php">Create an account</a>
    </div>
  </div>
</div>

<script>
function togglePw(){
  const pw = document.getElementById('pw');
  pw.type = pw.type==='password' ? 'text' : 'password';
}
</script>
</body>
</html>
