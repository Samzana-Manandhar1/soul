<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id=?");
$stmt->execute([$user_id]);
$cart = $stmt->fetch();
if (!$cart) {
    $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)")->execute([$user_id]);
    $cart_id = $pdo->lastInsertId();
} else {
    $cart_id = $cart['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT ci.*, p.name, p.price, p.image FROM cart_items ci
        JOIN products p ON ci.product_id = p.id WHERE ci.cart_id=?");
    $stmt->execute([$cart_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $product_id = $data['product_id'];
    $qty = $data['quantity'] ?? 1;
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id=? AND product_id=?");
    $stmt->execute([$cart_id, $product_id]);
    $item = $stmt->fetch();
    if ($item) {
        $pdo->prepare("UPDATE cart_items SET quantity=quantity+? WHERE id=?")->execute([$qty, $item['id']]);
    } else {
        $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)")->execute([$cart_id, $product_id, $qty]);
    }
    echo json_encode(['success'=>true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $cart_item_id = $data['cart_item_id'];
    $pdo->prepare("DELETE FROM cart_items WHERE id=? AND cart_id=?")->execute([$cart_item_id, $cart_id]);
    echo json_encode(['success'=>true]);
    exit;
}
?>