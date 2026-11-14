<?php
declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';
$products = db()->query("SELECT * FROM products WHERE is_active = 1 ORDER BY featured DESC, id DESC")->fetchAll();
$categories = db()->query("SELECT DISTINCT category FROM products WHERE is_active = 1 ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
function toman(int $price): string { return number_format($price, 0, '.', ',') . ' تومان'; }
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#f7f5f0">
  <meta name="description" content="مینی‌شاپ؛ انتخاب‌های کوچک، حس خوب بزرگ. فروشگاه آنلاین محصولات منتخب.">
  <title>مینی‌شاپ | انتخاب‌های دوست‌داشتنی</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="announcement">ارسال رایگان برای سفارش‌های بالای ۲ میلیون تومان <span>✦</span> تجربه‌ای ساده، خریدی دوست‌داشتنی</div>
<header class="site-header wrap">
  <a class="brand" href="index.php"><span class="brand-mark">m</span><span>mini<span class="brand-light">shop</span><small>انتخاب‌های کوچک، حس خوب بزرگ</small></span></a>
  <nav class="main-nav"><a class="active" href="#home">خانه</a><a href="#products">فروشگاه</a><a href="#story">داستان ما</a><a href="#footer">تماس با ما</a></nav>
  <div class="header-actions"><button class="icon-btn search-toggle" aria-label="جستجو">⌕</button><button class="cart-btn" id="cart-open">سبد خرید <span id="cart-count">۰</span></button><button class="menu-toggle" id="menu-toggle" aria-label="منو">☰</button></div>
</header>
<main>
<section class="hero wrap" id="home">
  <div class="hero-copy"><span class="eyebrow"><i></i> مجموعه منتخب این فصل</span><h1>چیزهای خوب،<br><em>زندگی را قشنگ‌تر</em><br>می‌کنند.</h1><p>از بین چیزهایی که دوستشان داریم انتخاب کردیم؛ ساده، کاربردی و باکیفیت. حالا نوبت توست که انتخاب کنی.</p><div class="hero-actions"><a class="btn btn-dark" href="#products">دیدن محصولات <span>←</span></a><a class="text-link" href="#story">داستان مینی‌شاپ <span>↗</span></a></div><div class="hero-note"><div class="avatar-stack"><b>م</b><b>ن</b><b>ر</b></div><span><strong>بیش از ۲,۴۰۰</strong><br>انتخاب خوشحال‌کننده</span></div></div>
  <div class="hero-art"><div class="hero-photo"><img src="https://images.unsplash.com/photo-1490312278390-ab64016e0aa9?auto=format&fit=crop&w=1100&q=85" alt="چیدمان مینیمال محصولات و دکور خانه"></div><div class="float-card"><span>✳</span><div><b>انتخاب با سلیقه</b><small>جزئیات کوچک، تفاوت بزرگ</small></div></div><div class="hero-sticker">GOOD<br>THINGS<br>INSIDE.</div></div>
</section>
<section class="trust-strip wrap"><div><b>✦</b><span>انتخاب‌شده با دقت<small>کیفیت قبل از هر چیز</small></span></div><div><b>↗</b><span>ارسال سریع<small>تا رسیدن به دست تو</small></span></div><div><b>♡</b><span>خرید بی‌دردسر<small>پشتیبانی همراه توست</small></span></div><div><b>↺</b><span>ضمانت بازگشت<small>با خیال راحت انتخاب کن</small></span></div></section>
<section class="section wrap" id="products">
  <div class="section-heading"><div><span class="eyebrow">برای حال خوب تو</span><h2>محبوب‌های <em>مینی‌شاپ</em></h2><p>چند انتخاب دوست‌داشتنی که ارزش دیدن دارند.</p></div><a class="text-link desktop-only" href="#products">همه محصولات <span>←</span></a></div>
  <div class="toolbar"><div class="filters" id="filters"><button class="filter active" data-category="همه">همه محصولات</button><?php foreach ($categories as $category): ?><button class="filter" data-category="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></button><?php endforeach; ?></div><label class="sort-wrap">مرتب‌سازی <select id="sort"><option value="featured">پیشنهاد ما</option><option value="low">ارزان‌ترین</option><option value="high">گران‌ترین</option></select></label></div>
  <div class="product-grid" id="product-grid">
  <?php foreach ($products as $p): ?>
    <article class="product-card" data-category="<?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?>" data-price="<?= (int)$p['price'] ?>" data-featured="<?= (int)$p['featured'] ?>">
      <div class="product-image"><img src="<?= htmlspecialchars($p['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"><?php if ($p['badge']): ?><span class="product-badge"><?= htmlspecialchars($p['badge'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?><button class="quick-add" data-id="<?= (int)$p['id'] ?>" aria-label="افزودن به سبد">+</button><button class="heart" aria-label="علاقه‌مندی">♡</button></div>
      <div class="product-meta"><span><?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></span><span class="rating">★ <?= number_format((float)$p['rating'], 1) ?></span></div>
      <h3><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h3><div class="price-row"><strong><?= toman((int)$p['price']) ?></strong><?php if ($p['old_price']): ?><del><?= toman((int)$p['old_price']) ?></del><?php endif; ?></div>
    </article>
  <?php endforeach; ?>
  </div>
  <div class="empty-state" id="empty-state" hidden>محصولی در این دسته پیدا نشد.</div>
</section>
<section class="editorial wrap" id="story"><div class="editorial-image"><img src="https://images.unsplash.com/photo-1494438639946-1ebd1d20bf85?auto=format&fit=crop&w=1000&q=85" alt="فضای گرم و مینیمال خانه" loading="lazy"><span class="round-seal">LESS<br> BUT<br> BETTER</span></div><div class="editorial-copy"><span class="eyebrow">نگاهی به دنیای ما</span><h2>کمتر، اما<br><em>بهتر انتخاب کن.</em></h2><p>ما باور داریم چیزهایی که اطرافمان هستند باید به زندگی‌مان چیزی اضافه کنند. برای همین در مینی‌شاپ سراغ انتخاب‌هایی می‌رویم که هم زیبا هستند، هم به‌دردبخور و هم ماندنی.</p><a class="btn btn-outline" href="#products">کشف انتخاب‌ها <span>←</span></a></div></section>
<section class="newsletter wrap"><div><span class="eyebrow">خبرهای خوب در راه‌اند</span><h2>عضو جمع <em>مینی‌شاپ</em> شو.</h2><p>برای باخبر شدن از تازه‌ها و پیشنهادهای ویژه.</p></div><form id="newsletter-form"><input type="email" placeholder="ایمیل تو..." required><button class="btn btn-dark">عضو می‌شوم <span>←</span></button></form></section>
</main>
<footer id="footer"><div class="footer-main wrap"><div class="brand footer-brand"><span class="brand-mark">m</span><span>mini<span class="brand-light">shop</span><small>انتخاب‌های کوچک، حس خوب بزرگ</small></span></div><p>خریدن می‌تواند ساده، آرام و لذت‌بخش باشد.<br>ما اینجاییم تا انتخاب را برایت قشنگ‌تر کنیم.</p><div class="footer-links"><a href="#products">فروشگاه</a><a href="#story">درباره ما</a><a href="admin.php">مدیریت فروشگاه</a></div></div><div class="footer-bottom wrap"><span>© ۱۴۰۵ مینی‌شاپ. همه حقوق محفوظ است.</span><span>ساخته‌شده با عشق و کمی سلیقه ✳</span></div></footer>
<div class="cart-overlay" id="cart-overlay"><aside class="cart-drawer"><div class="drawer-head"><h3>سبد خرید تو</h3><button id="cart-close" aria-label="بستن">×</button></div><div id="cart-items" class="cart-items"><p class="cart-empty">هنوز چیزی انتخاب نکردی!</p></div><div class="cart-footer"><div class="total-line"><span>جمع سفارش</span><strong id="cart-total">۰ تومان</strong></div><button class="btn btn-dark checkout-btn" id="checkout">ثبت سفارش آزمایشی <span>←</span></button><small>این فروشگاه نسخه نمایشی است و پرداخت واقعی ندارد.</small></div></aside></div>
<div class="toast" id="toast"></div>
<script>window.MINI_PRODUCTS = <?= json_encode(array_map(fn($p)=>['id'=>(int)$p['id'],'name'=>$p['name'],'price'=>(int)$p['price'],'image'=>$p['image']],$products), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;</script>
<script src="assets/app.js"></script>
</body></html>