<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand_name = $_POST['brand_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $uploads = [];
    for ($i=1;$i<=4;$i++) {
        $img = $_FILES['image'.$i] ?? null;
        if ($img && $img['tmp_name']) {
            $imgname = uniqid().basename($img['name']);
            move_uploaded_file($img['tmp_name'], "uploads/$imgname");
            $uploads[$i] = "uploads/$imgname";
        } else {
            $uploads[$i] = "";
        }
    }
    $stmt = $pdo->prepare("INSERT INTO products (name, brand_name, description, price, stock, image1, image2, image3, image4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $brand_name, $description, $price, $stock, $uploads[1], $uploads[2], $uploads[3], $uploads[4]]);
    echo json_encode(["success" => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$data['id']]);
    echo json_encode(["success" => true]);
    exit;
}
?>