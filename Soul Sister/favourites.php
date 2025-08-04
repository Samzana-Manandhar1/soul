<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT f.*, p.name, p.price, p.image FROM favourites f
        JOIN products p ON f.product_id = p.id WHERE f.user_id=?");
    $stmt->execute([$user_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $product_id = $data['product_id'];
    $stmt = $pdo->prepare("SELECT id FROM favourites WHERE user_id=? AND product_id=?");
    $stmt->execute([$user_id, $product_id]);
    if (!$stmt->fetch()) {
        $pdo->prepare("INSERT INTO favourites (user_id, product_id) VALUES (?, ?)")->execute([$user_id, $product_id]);
    }
    echo json_encode(['success'=>true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $fid = $data['fav_id'];
    $pdo->prepare("DELETE FROM favourites WHERE id=? AND user_id=?")->execute([$fid, $user_id]);
    echo json_encode(['success'=>true]);
    exit;
}
?>