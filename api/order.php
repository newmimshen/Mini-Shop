<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['ok'=>false,'message'=>'روش درخواست نامعتبر است.'],405);
$payload = json_decode(file_get_contents('php://input'), true) ?? [];
$name = trim((string)($payload['name'] ?? ''));
$phone = preg_replace('/\D+/', '', (string)($payload['phone'] ?? ''));
$items = $payload['items'] ?? [];
if ($name === '' || strlen($phone) < 10 || !is_array($items) || !$items) json_response(['ok'=>false,'message'=>'نام، شماره تماس و سبد خرید را بررسی کن.'],422);
$ids = array_values(array_unique(array_filter(array_map(fn($x)=>(int)($x['id'] ?? 0),$items))));
if (!$ids) json_response(['ok'=>false,'message'=>'سبد خرید خالی است.'],422);
$placeholders = implode(',', array_fill(0,count($ids),'?'));
$stmt = db()->prepare("SELECT id,name,price FROM products WHERE is_active=1 AND id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();
$byId = []; foreach ($products as $p) $byId[(int)$p['id']] = $p;
$normalized = []; $total = 0;
foreach ($items as $item) {
  $id = (int)($item['id'] ?? 0); $qty = max(1,min(20,(int)($item['qty'] ?? 1)));
  if (!isset($byId[$id])) continue;
  $p = $byId[$id]; $total += (int)$p['price'] * $qty;
  $normalized[] = ['id'=>$id,'name'=>$p['name'],'price'=>(int)$p['price'],'qty'=>$qty];
}
if (!$normalized) json_response(['ok'=>false,'message'=>'محصولات انتخاب‌شده معتبر نیستند.'],422);
$stmt = db()->prepare('INSERT INTO orders (customer_name,phone,items_json,total) VALUES (?,?,?,?)');
$stmt->execute([$name,$phone,json_encode($normalized,JSON_UNESCAPED_UNICODE),$total]);
json_response(['ok'=>true,'order_id'=>(int)db()->lastInsertId(),'message'=>'سفارش نمایشی تو ثبت شد. ممنون از اعتمادت!']);
