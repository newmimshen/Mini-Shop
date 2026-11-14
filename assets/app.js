(() => {
  const products = window.MINI_PRODUCTS || [];
  const cart = new Map();
  const grid = document.getElementById('product-grid');
  const overlay = document.getElementById('cart-overlay');
  const count = document.getElementById('cart-count');
  const total = document.getElementById('cart-total');
  const itemsBox = document.getElementById('cart-items');
  const toastBox = document.getElementById('toast');
  const money = n => new Intl.NumberFormat('fa-IR').format(n) + ' تومان';
  let toastTimer;
  function toast(message) { toastBox.textContent = message; toastBox.classList.add('show'); clearTimeout(toastTimer); toastTimer = setTimeout(() => toastBox.classList.remove('show'), 2400); }
  function add(id) { const p = products.find(x => x.id === Number(id)); if (!p) return; const old = cart.get(p.id); cart.set(p.id, {...p, qty: old ? old.qty + 1 : 1}); renderCart(); toast('به سبد خرید اضافه شد ✦'); }
  function renderCart() {
    const all = [...cart.values()]; const qty = all.reduce((s,p)=>s+p.qty,0); const sum = all.reduce((s,p)=>s+p.price*p.qty,0);
    count.textContent = new Intl.NumberFormat('fa-IR').format(qty); total.textContent = money(sum);
    itemsBox.innerHTML = all.length ? all.map(p => '<div class="cart-line"><img src="'+p.image+'" alt=""><div class="cart-line-info"><b>'+p.name+'</b><small>'+money(p.price)+'</small></div><div class="qty-controls"><button data-qty="-1" data-id="'+p.id+'">−</button><span>'+new Intl.NumberFormat('fa-IR').format(p.qty)+'</span><button data-qty="1" data-id="'+p.id+'">+</button></div></div>').join('') : '<p class="cart-empty">هنوز چیزی انتخاب نکردی!</p>';
  }
  document.addEventListener('click', e => {
    const addBtn = e.target.closest('.quick-add'); if (addBtn) add(addBtn.dataset.id);
    const qtyBtn = e.target.closest('[data-qty]'); if (qtyBtn) { const id=Number(qtyBtn.dataset.id), item=cart.get(id); if(item){item.qty+=Number(qtyBtn.dataset.qty);if(item.qty<1)cart.delete(id);renderCart();} }
    const heart=e.target.closest('.heart'); if(heart){heart.classList.toggle('liked');heart.textContent=heart.classList.contains('liked')?'♥':'♡';}
  });
  document.getElementById('cart-open').addEventListener('click',()=>overlay.classList.add('open'));
  document.getElementById('cart-close').addEventListener('click',()=>overlay.classList.remove('open'));
  overlay.addEventListener('click',e=>{if(e.target===overlay)overlay.classList.remove('open')});
  document.getElementById('filters').addEventListener('click',e=>{const btn=e.target.closest('.filter');if(!btn)return;document.querySelectorAll('.filter').forEach(x=>x.classList.remove('active'));btn.classList.add('active');filterProducts();});
  document.getElementById('sort').addEventListener('change',filterProducts);
  function filterProducts(){const cat=document.querySelector('.filter.active')?.dataset.category||'همه';const cards=[...grid.children];cards.forEach(c=>c.hidden=cat!=='همه'&&c.dataset.category!==cat);const visible=cards.filter(c=>!c.hidden);const sort=document.getElementById('sort').value;visible.sort((a,b)=>sort==='low'?Number(a.dataset.price)-Number(b.dataset.price):sort==='high'?Number(b.dataset.price)-Number(a.dataset.price):Number(b.dataset.featured)-Number(a.dataset.featured));visible.forEach(c=>grid.appendChild(c));document.getElementById('empty-state').hidden=visible.length>0;}
  document.getElementById('checkout').addEventListener('click',async()=>{
    if(!cart.size){toast('اول یک محصول به سبد اضافه کن.');return;}
    const name=prompt('نام و نام خانوادگی برای سفارش نمایشی:');if(!name)return;
    const phone=prompt('شماره تماس:');if(!phone)return;
    try{const response=await fetch('api/order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,phone,items:[...cart.values()].map(p=>({id:p.id,qty:p.qty}))})});const result=await response.json();if(!response.ok)throw new Error(result.message||'خطا در ثبت سفارش');cart.clear();renderCart();overlay.classList.remove('open');toast('سفارش #'+new Intl.NumberFormat('fa-IR').format(result.order_id)+' ثبت شد. ممنون!');}catch(err){toast(err.message||'ارتباط با سرور برقرار نشد.');}
  });
  document.getElementById('newsletter-form').addEventListener('submit',e=>{e.preventDefault();toast('ممنون! به جمع مینی‌شاپ خوش آمدی.');e.target.reset();});
  document.querySelector('.search-toggle')?.addEventListener('click',()=>{const q=prompt('نام محصول موردنظرت را بنویس:');if(q){const cards=[...grid.children];let found=0;cards.forEach(c=>{const match=c.querySelector('h3').textContent.includes(q);c.hidden=!match;if(match)found++;});document.querySelectorAll('.filter').forEach(x=>x.classList.remove('active'));document.getElementById('empty-state').hidden=found>0;toast(found?found+' محصول پیدا شد.':'محصولی پیدا نشد.');}});
  document.getElementById('menu-toggle').addEventListener('click',()=>document.querySelector('.main-nav').classList.toggle('show'));
})();