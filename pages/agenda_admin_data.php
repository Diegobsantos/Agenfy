<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    exit('Não autorizado');
}

try {
    $stmt = $pdo->query("SELECT cliente, data_inicio, data_fim FROM agendamentos");
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $eventos = [];

    foreach ($agendamentos as $a) {
        $eventos[] = [
            'title' => $a['cliente'],
            'start' => $a['data_inicio'],
            'end' => date('Y-m-d', strtotime($a['data_fim'] . ' +1 day')),
            'color' => '#007bff'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($eventos);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
