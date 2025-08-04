<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id=?");
    $stmt->execute([$user_id]);
    echo json_encode($stmt->fetch());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("SELECT id FROM user_profiles WHERE user_id=?");
    $stmt->execute([$user_id]);
    if ($stmt->fetch()) {
        $pdo->prepare("UPDATE user_profiles SET full_name=?, address=?, phone=?, dob=?, gender=? WHERE user_id=?")
            ->execute([$data['full_name'], $data['address'], $data['phone'], $data['dob'], $data['gender'], $user_id]);
    } else {
        $pdo->prepare("INSERT INTO user_profiles (user_id, full_name, address, phone, dob, gender) VALUES (?,?,?,?,?,?)")
            ->execute([$user_id, $data['full_name'], $data['address'], $data['phone'], $data['dob'], $data['gender']]);
    }
    echo json_encode(['success'=>true]);
    exit;
}
?>