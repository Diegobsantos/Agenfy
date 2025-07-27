<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';

$stmt = $pdo->query("SELECT * FROM agendamentos ORDER BY data_inicio ASC");
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendamentos - Impressão</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f1f1f1;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<h2>Relatório de Agendamentos</h2>

<table>
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Observação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dados as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['cliente']) ?></td>
            <td><?= date('d/m/Y', strtotime($item['data_inicio'])) ?></td>
            <td><?= date('d/m/Y', strtotime($item['data_fim'])) ?></td>
            <td><?= nl2br(htmlspecialchars($item['observacao'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="no-print" style="margin-top: 30px; text-align: center;">
    <button onclick="window.print()" style="padding: 10px 20px;">🖨️ Imprimir</button>
    <a href="<?= site_base('pages/admin.php') ?>" style="margin-left: 15px;">← Voltar</a>
</div>

</body>
</html>
