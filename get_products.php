<?php
require 'db.php';
$res = $conn->query("SELECT id, name, image_url FROM products");
$products = [];
while($row = $res->fetch_assoc()){
    $products[] = $row;
}
echo json_encode($products, JSON_PRETTY_PRINT);
?>
