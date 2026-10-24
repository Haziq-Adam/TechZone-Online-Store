<?php
define('TECHZONE',1);
session_start();
$page_title = 'Contact Us';

$sent = false;
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name']??'');
    $email = trim($_POST['email']??'');
    $subject = trim($_POST['subject']??'');
    $msg = trim($_POST['message']??'');
    if(!$name||!$email||!$subject||!$msg){ $err = "Please fill in all fields."; }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $err = "Invalid email address."; }
    else {
        // In a real app you'd send email here. We'll just mark as sent.
        $sent = true;
    }
}
require 'header.php';
?>
<style>
.contact-wrap{max-width:1100px;margin:0 auto;padding:60px 24px;display:grid;grid-template-columns:1fr 1.3fr;gap:60px;align-items:start}
.contact-left h1{font-family:'Outfit',sans-serif;font-size:2.4rem;font-weight:900;margin-bottom:16px}
.contact-left p{color:var(--muted);line-height:1.8;margin-bottom:32px}
.contact-method{display:flex;align-items:flex-start;gap:16px;margin-bottom:24px}
.cm-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
.cm-purple{background:rgba(108,99,255,.15);border:1px solid rgba(108,99,255,.3)}
.cm-green{background:rgba(0,214,143,.15);border:1px solid rgba(0,214,143,.3)}
.cm-pink{background:rgba(255,101,132,.15);border:1px solid rgba(255,101,132,.3)}
.cm-text h4{font-weight:700;margin-bottom:3px}
.cm-text p{color:var(--muted);font-size:.88rem}
.cm-text a{color:var(--accent)}
.contact-hours{background:rgba(19,19,23,.8);border:1px solid var(--border);border-radius:16px;padding:20px 24px;margin-top:32px}
.contact-hours h4{font-weight:700;margin-bottom:12px;display:flex;align-items:center;gap:8px}
.hour-row{display:flex;justify-content:space-between;font-size:.88rem;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.hour-row:last-child{border-bottom:none}
.hr-days{color:var(--muted)}
.hr-time{font-weight:600}

.contact-form-wrap{background:rgba(19,19,23,.85);border:1px solid var(--border);border-radius:22px;padding:36px;backdrop-filter:blur(16px)}
.contact-form-wrap h3{font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:800;margin-bottom:6px}
.contact-form-wrap .sub{color:var(--muted);font-size:.88rem;margin-bottom:28px}
.fg{margin-bottom:18px}
.fg label{display:block;font-size:.78rem;font-weight:600;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.fg input,.fg select,.fg textarea{width:100%;padding:13px 16px;border-radius:12px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:var(--text);font-family:'Inter',sans-serif;font-size:.95rem;outline:none;transition:all .3s;box-sizing:border-box}
.fg input:focus,.fg select:focus,.fg textarea:focus{background:rgba(255,255,255,.07);border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.fg select option{background:#13131a}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.sent-state{text-align:center;padding:40px 20px}
.sent-state .tick{font-size:4rem;margin-bottom:16px;animation:float 4s ease-in-out infinite}
.sent-state h3{font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:800;margin-bottom:10px}
.sent-state p{color:var(--muted)}
@media(max-width:768px){.contact-wrap{grid-template-columns:1fr}.g2{grid-template-columns:1fr}}
</style>

<div class="contact-wrap fade-up">
  <div class="contact-left">
    <div style="font-size:3rem;margin-bottom:20px;animation:float 5s ease-in-out infinite">📞</div>
    <h1>Get in <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Touch</span></h1>
    <p>Have a question, suggestion, or just want to say hi? We'd love to hear from you. Our support team responds within 24 hours.</p>

    <div class="contact-method">
      <div class="cm-icon cm-purple">📧</div>
      <div class="cm-text">
        <h4>Email Us</h4>
        <p><a href="mailto:hello@techzone.com.my">hello@techzone.com.my</a></p>
        <p>Response within 24 hours</p>
      </div>
    </div>
    <div class="contact-method">
      <div class="cm-icon cm-green">📱</div>
      <div class="cm-text">
        <h4>WhatsApp</h4>
        <p><a href="https://wa.me/60123456789" target="_blank">+60 12-345 6789</a></p>
        <p>Mon–Sat, 9AM–9PM</p>
      </div>
    </div>
    <div class="contact-method">
      <div class="cm-icon cm-pink">📍</div>
      <div class="cm-text">
        <h4>Head Office</h4>
        <p>Level 12, Menara TechZone</p>
        <p>Jalan Ampang, 50450 Kuala Lumpur</p>
      </div>
    </div>

    <div class="contact-hours">
      <h4>🕐 Support Hours</h4>
      <div class="hour-row"><span class="hr-days">Monday – Friday</span><span class="hr-time">9:00 AM – 10:00 PM</span></div>
      <div class="hour-row"><span class="hr-days">Saturday</span><span class="hr-time">9:00 AM – 6:00 PM</span></div>
      <div class="hour-row"><span class="hr-days">Sunday / Public Holiday</span><span class="hr-time">Email only</span></div>
    </div>
  </div>

  <div class="contact-form-wrap">
    <?php if($sent): ?>
    <div class="sent-state">
      <div class="tick">✅</div>
      <h3>Message Sent!</h3>
      <p>Thank you, we'll get back to you within 24 hours. Check your inbox for a confirmation email.</p>
      <a href="contact.php" class="btn btn-outline" style="margin-top:24px">Send Another</a>
    </div>
    <?php else: ?>
    <h3>Send Us a Message</h3>
    <p class="sub">Fill out the form and we'll respond as soon as possible.</p>
    <?php if($err): ?><div class="err">⚠️ <?= htmlspecialchars($err) ?></div><?php endif; ?>
    <form method="POST">
      <div class="g2">
        <div class="fg">
          <label>Your Name</label>
          <input type="text" name="name" required placeholder="Ahmad" value="<?= htmlspecialchars($_POST['name']??'') ?>">
        </div>
        <div class="fg">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email']??'') ?>">
        </div>
      </div>
      <div class="fg">
        <label>Subject</label>
        <select name="subject" required>
          <option value="">Select a topic...</option>
          <option value="Order Issue" <?= ($_POST['subject']??'')==='Order Issue'?'selected':'' ?>>🛒 Order Issue</option>
          <option value="Return/Refund" <?= ($_POST['subject']??'')==='Return/Refund'?'selected':'' ?>>🔄 Return / Refund</option>
          <option value="Product Question" <?= ($_POST['subject']??'')==='Product Question'?'selected':'' ?>>❓ Product Question</option>
          <option value="Seller Inquiry" <?= ($_POST['subject']??'')==='Seller Inquiry'?'selected':'' ?>>🏪 Seller Inquiry</option>
          <option value="Technical Issue" <?= ($_POST['subject']??'')==='Technical Issue'?'selected':'' ?>>🔧 Technical Issue</option>
          <option value="Other" <?= ($_POST['subject']??'')==='Other'?'selected':'' ?>>💬 Other</option>
        </select>
      </div>
      <div class="fg">
        <label>Message</label>
        <textarea name="message" rows="6" required placeholder="Describe your question or issue in detail..." style="resize:vertical"><?= htmlspecialchars($_POST['message']??'') ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;padding:16px;font-size:1rem;border-radius:12px">Send Message →</button>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php require 'footer.php'; ?>
