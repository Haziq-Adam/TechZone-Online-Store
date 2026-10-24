<?php
session_start();
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if(!$id){header("Location: products.php");exit();}
// Check product exists
$chk = $conn->prepare("SELECT id FROM products WHERE id=?");
$chk->bind_param("i",$id); $chk->execute();
if(!$chk->get_result()->num_rows){header("Location: products.php");exit();}

if(!isset($_SESSION['user_id'])){
    header("Location: login.php?redirect=product.php?id=$id"); exit();
}
$uid = $_SESSION['user_id'];
// Check if already in cart
$existing = $conn->prepare("SELECT id,quantity FROM cart WHERE user_id=? AND product_id=?");
$existing->bind_param("ii",$uid,$id); $existing->execute();
$row = $existing->get_result()->fetch_assoc();
if($row){
    $stmt = $conn->prepare("UPDATE cart SET quantity=quantity+1 WHERE id=?");
    $stmt->bind_param("i",$row['id']); $stmt->execute();
}else{
    $stmt = $conn->prepare("INSERT INTO cart(user_id,product_id,quantity) VALUES(?,?,1)");
    $stmt->bind_param("ii",$uid,$id); $stmt->execute();
}
$back = 'products.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $parsed = parse_url($_SERVER['HTTP_REFERER']);
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (!isset($parsed['host']) || strcasecmp($parsed['host'], $host) === 0) {
        $path = basename($parsed['path'] ?? '');
        if (in_array($path, ['products.php', 'product.php', 'index.php'], true)) {
            $back = $path;
            if (!empty($parsed['query']) && preg_match('/^[a-zA-Z0-9_=&%.-]+$/', $parsed['query'])) {
                $back .= '?' . $parsed['query'];
            }
        }
    }
}
header('Location: ' . $back . (str_contains($back, '?') ? '&' : '?') . 'added=1');
exit();
?>
