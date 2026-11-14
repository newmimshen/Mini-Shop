<?php
declare(strict_types=1);
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dir = dirname(__DIR__) . '/storage';
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    $pdo = new PDO('sqlite:' . $dir . '/minishop.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
      id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, category TEXT NOT NULL,
      price INTEGER NOT NULL, old_price INTEGER, rating REAL NOT NULL DEFAULT 4.8,
      badge TEXT, image TEXT NOT NULL, description TEXT, featured INTEGER NOT NULL DEFAULT 0,
      is_active INTEGER NOT NULL DEFAULT 1, created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
      id INTEGER PRIMARY KEY AUTOINCREMENT, customer_name TEXT NOT NULL, phone TEXT NOT NULL,
      items_json TEXT NOT NULL, total INTEGER NOT NULL, status TEXT NOT NULL DEFAULT 'در انتظار بررسی',
      created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    $count = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    if ($count === 0) {
      $seed = [
        ['چراغ رومیزی نُوا','خانه و دکور',1280000,1590000,4.9,'پرفروش','https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=85','نور گرم و طراحی مینیمال برای گوشه دنج خانه.',1],
        ['ماگ سرامیکی آوید','خانه و دکور',390000,490000,4.8,'محبوب','https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=800&q=85','یک همراه دوست‌داشتنی برای قهوه و چای روزانه.',1],
        ['کیف روزمره لینا','اکسسوری',890000,null,4.7,'تازه رسید','https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=85','سبک، ساده و مناسب روزهای پرماجرای تو.',1],
        ['دفتر یادداشت کاغذی','نوشت‌افزار',245000,290000,4.9,'','https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=800&q=85','برای فکرهایی که ارزش ثبت شدن دارند.',1],
        ['گلدان مینیمال آرا','خانه و دکور',560000,null,4.8,'انتخاب ما','https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=800&q=85','فرمی آرام برای سبزتر کردن فضای زندگی.',0],
        ['عینک آفتابی رُما','اکسسوری',1120000,1390000,4.6,'تخفیف ویژه','https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=85','جزئیاتی کوچک برای استایلی متمایز.',0],
        ['شمع معطر سپیدار','خانه و دکور',320000,null,4.9,'','https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&w=800&q=85','رایحه‌ای ملایم برای لحظه‌های آرام.',0],
        ['هدفون بی‌سیم موج','دیجیتال',2190000,2490000,4.7,'جدید','https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=85','صدایی شفاف برای همراهی با روز تو.',0]
      ];
      $stmt = $pdo->prepare('INSERT INTO products (name,category,price,old_price,rating,badge,image,description,featured) VALUES (?,?,?,?,?,?,?,?,?)');
      foreach ($seed as $row) $stmt->execute($row);
    }
    return $pdo;
}
function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    exit;
}
