<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';

$stmt = $pdo->query("SELECT data_inicio, data_fim FROM agendamentos");
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$eventos = [];

foreach ($agendamentos as $a) {
    $eventos[] = [
        'title' => 'Ocupado',
        'start' => $a['data_inicio'],
        'end' => date('Y-m-d', strtotime($a['data_fim'] . ' +1 day')),
        'color' => '#d9534f'
    ];
}

header('Content-Type: application/json');
echo json_encode($eventos);
