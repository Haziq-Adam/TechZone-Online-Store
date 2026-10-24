<?php
define('TECHZONE',1);
session_start();
$page_title = 'FAQ';
require 'header.php';
?>
<style>
.faq-wrap{max-width:860px;margin:0 auto;padding:60px 24px}
.faq-hero{text-align:center;margin-bottom:50px}
.faq-hero h1{font-family:'Outfit',sans-serif;font-size:2.5rem;font-weight:900;margin-bottom:12px}
.faq-hero p{color:var(--muted);font-size:1rem}
.faq-cats{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-bottom:40px}
.faq-cat{padding:8px 20px;border-radius:25px;font-size:.85rem;font-weight:600;border:1px solid var(--border);color:var(--muted);cursor:pointer;transition:all .2s}
.faq-cat.active,.faq-cat:hover{background:var(--accent);color:#fff;border-color:var(--accent)}
.faq-section-title{font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--accent);margin:32px 0 16px;display:flex;align-items:center;gap:8px}
.faq-item{background:rgba(19,19,23,.8);border:1px solid var(--border);border-radius:14px;margin-bottom:10px;overflow:hidden;transition:border-color .3s}
.faq-item.open{border-color:rgba(108,99,255,.4)}
.faq-q{padding:18px 22px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-weight:600;font-size:.95rem;user-select:none}
.faq-arrow{transition:transform .3s;color:var(--muted);font-size:1.1rem}
.faq-item.open .faq-arrow{transform:rotate(180deg);color:var(--accent)}
.faq-a{padding:0 22px;max-height:0;overflow:hidden;transition:max-height .4s ease,padding .3s}
.faq-item.open .faq-a{max-height:300px;padding:0 22px 20px}
.faq-a p{color:var(--muted);font-size:.9rem;line-height:1.7}
.faq-a a{color:var(--accent);font-weight:600}
.faq-cta{background:rgba(108,99,255,.08);border:1px solid rgba(108,99,255,.2);border-radius:20px;padding:40px;text-align:center;margin-top:50px}
.faq-cta h3{font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:800;margin-bottom:10px}
.faq-cta p{color:var(--muted);margin-bottom:20px;font-size:.9rem}
.search-faq{width:100%;padding:14px 20px;border-radius:12px;border:1px solid var(--border);background:rgba(255,255,255,.04);color:var(--text);font-family:'Inter',sans-serif;font-size:.95rem;outline:none;margin-bottom:32px;transition:all .3s}
.search-faq:focus{border-color:var(--accent);background:rgba(255,255,255,.07);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
</style>

<div class="faq-wrap fade-up">
  <div class="faq-hero">
    <div style="font-size:3.5rem;margin-bottom:16px;animation:float 4s ease-in-out infinite">❓</div>
    <h1>Frequently Asked <span style="background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Questions</span></h1>
    <p>Find answers to your most common questions about TechZone</p>
  </div>

  <input type="search" class="search-faq" placeholder="🔍 Search questions..." id="faqSearch" oninput="filterFAQ(this.value)">

  <div id="faqContainer">
    <div class="faq-section-title" data-section="ordering">🛒 Ordering & Payment</div>
    <?php
    $faqs = [
      ['section'=>'ordering','q'=>'How do I place an order?','a'=>'Browse products, click "Add to Cart", then proceed to checkout. You\'ll need an account to complete your purchase. It takes less than 2 minutes to register!'],
      ['section'=>'ordering','q'=>'What payment methods are accepted?','a'=>'We accept FPX (online banking), credit/debit cards (Visa, Mastercard), e-wallets (Touch \'n Go, GrabPay, Boost), and COD for selected areas.'],
      ['section'=>'ordering','q'=>'Can I cancel my order after placing it?','a'=>'Yes, you can cancel within 1 hour of placing your order. After that, you\'ll need to wait for delivery and initiate a return. Contact us at support@techzone.com.my.'],
      ['section'=>'ordering','q'=>'Is it safe to save my payment details?','a'=>'We never store your full card details. All transactions are processed through PCI-DSS compliant payment gateways.'],
      ['section'=>'delivery','q'=>'How long does delivery take?','a'=>'Peninsular Malaysia: 2-3 working days. Sabah/Sarawak: 5-7 working days. Express delivery (1 day) is available for Klang Valley orders placed before 12PM.'],
      ['section'=>'delivery','q'=>'Do you deliver to all states in Malaysia?','a'=>'Yes! We deliver nationwide including Sabah, Sarawak, and Labuan. International shipping is not available yet.'],
      ['section'=>'delivery','q'=>'How do I track my order?','a'=>'Once shipped, you\'ll receive a tracking number via SMS and email. You can also track your order from your account dashboard.'],
      ['section'=>'returns','q'=>'What is the return policy?','a'=>'We offer a 7-day easy return policy. Items must be in original condition with all accessories and packaging. DOA (Dead on Arrival) items are replaced within 24 hours.'],
      ['section'=>'returns','q'=>'How long does a refund take?','a'=>'Approved refunds are processed within 3-7 working days. Bank transfers may take an additional 1-3 days depending on your bank.'],
      ['section'=>'returns','q'=>'The product I received is defective. What should I do?','a'=>'Contact us immediately at support@techzone.com.my with your order number and photos/video of the defect. We\'ll arrange a replacement or full refund within 24 hours.'],
      ['section'=>'seller','q'=>'How do I open a store on TechZone?','a'=>'Login to your account, click "Open Store" in the navigation bar, fill in your store details, and you\'re live immediately! You can then add products with images, descriptions, and pricing.'],
      ['section'=>'seller','q'=>'Is there a fee to sell on TechZone?','a'=>'Currently, opening and maintaining a store on TechZone is completely free. We only apply a small commission on successful sales.'],
      ['section'=>'seller','q'=>'How do I upload product images?','a'=>'In your Seller Dashboard, go to My Products → Edit any product. You can upload images directly from your computer (JPG, PNG, WEBP) or paste an image URL.'],
    ];
    $last_section = '';
    foreach($faqs as $i => $f):
      if($f['section'] !== $last_section && $last_section !== ''):
        $sections = ['delivery'=>'📦 Delivery & Shipping','returns'=>'🔄 Returns & Refunds','seller'=>'🏪 Selling on TechZone'];
        echo '<div class="faq-section-title" data-section="'.$f['section'].'">'.$sections[$f['section']].'</div>';
      endif;
      $last_section = $f['section'];
    ?>
    <div class="faq-item" data-section="<?= $f['section'] ?>" data-text="<?= htmlspecialchars(strtolower($f['q'].' '.$f['a'])) ?>">
      <div class="faq-q" onclick="toggleFAQ(this.parentElement)">
        <?= htmlspecialchars($f['q']) ?>
        <span class="faq-arrow">▾</span>
      </div>
      <div class="faq-a"><p><?= htmlspecialchars($f['a']) ?></p></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="faq-cta">
    <h3>Still have questions?</h3>
    <p>Our support team is available 24/7 to help you out</p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="contact.php" class="btn btn-primary">💬 Contact Us</a>
      <a href="https://wa.me/60123456789" class="btn btn-outline" target="_blank">📱 WhatsApp</a>
    </div>
  </div>
</div>

<script>
function toggleFAQ(el){
  const isOpen = el.classList.contains('open');
  document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
  if(!isOpen) el.classList.add('open');
}
function filterFAQ(val){
  val = val.toLowerCase();
  document.querySelectorAll('.faq-item').forEach(item => {
    item.style.display = item.dataset.text.includes(val) ? '' : 'none';
  });
  document.querySelectorAll('.faq-section-title').forEach(t => {
    const sec = t.dataset.section;
    const visible = [...document.querySelectorAll('.faq-item[data-section="'+sec+'"]')].some(i=>i.style.display!=='none');
    t.style.display = visible ? '' : 'none';
  });
}
</script>
<?php require 'footer.php'; ?>
