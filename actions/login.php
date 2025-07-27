<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';

session_start();

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);
$user = $stmt->fetch();

if ($user && password_verify($senha, $user['senha'])) {
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['usuario_id'] = $user['id'];
    header("Location: " . site_base('pages/admin.php'));
} else {
    header("Location: " . site_base('pages/login.php?erro=1'));
}
