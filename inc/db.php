<?php
// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'agenda');
define('DB_USER', 'root');
define('DB_PASS', 'mysql'); // Ajuste conforme sua senha

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}
?>
