<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $fullname = trim($_POST['fullname']);

    // Check if username or email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        echo "Username or Email already exists!";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);
    $user_id = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO user_profiles (user_id, full_name) VALUES (?, ?)")->execute([$user_id, $fullname]);
    header("Location: login.html?registered=1");
    exit;
}
?>