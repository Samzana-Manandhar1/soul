<?php
require 'config.php';
$stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY id DESC");
$products = $stmt->fetchAll();
echo json_encode($products);
?>