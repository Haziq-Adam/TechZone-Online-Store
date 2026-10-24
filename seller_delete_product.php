<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
    http_response_code(403);
    exit('Invalid request.');
}
$id = (int)($_POST['id'] ?? 0);

if($id){
    // Verify ownership
    $stmt = $conn->prepare("SELECT id FROM stores WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $store = $stmt->get_result()->fetch_assoc();

    if($store){
        $store_id = $store['id'];
        $del = $conn->prepare("DELETE FROM products WHERE id=? AND store_id=?");
        $del->bind_param("ii", $id, $store_id);
        $del->execute();
    }
}

header("Location: seller_dashboard.php?msg=Product+deleted");
exit();
?>
