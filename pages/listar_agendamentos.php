<?php
include_once APP_ROOT . '/inc/db.php';

$stmt = $pdo->query("SELECT * FROM agendamentos ORDER BY data_inicio ASC");
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0;">
    <h3>Lista de Agendamentos</h3>
    <a href="<?= site_base('pages/imprimir_agendamentos.php') ?>" target="_blank"
        title="Imprimir"
        style="text-decoration: none; font-size: 16px; padding: 8px 12px; background-color: #007bff; color: white; border-radius: 5px;">
        🖨️ Imprimir
    </a>
</div>

<div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f1f1f1; color: #333;">
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Cliente</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Entrada</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Saída</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Observação</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($agendamentos)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 15px;">Nenhum agendamento encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($agendamentos as $a): ?>
                    <tr style="border-bottom: 1px solid #eee; background-color: #fff;">
                        <td style="padding: 8px;"><?= htmlspecialchars($a['cliente']) ?></td>
                        <td style="padding: 8px;"><?= date('d/m/Y', strtotime($a['data_inicio'])) ?></td>
                        <td style="padding: 8px;"><?= date('d/m/Y', strtotime($a['data_fim'])) ?></td>
                        <td style="padding: 8px;"><?= nl2br(htmlspecialchars($a['observacao'])) ?></td>
                        <td style="padding: 8px;">
                            <a href="<?= site_base('pages/editar_agendamento.php?id=' . $a['id']) ?>"
                               title="Editar"
                               style="margin-right: 10px; padding: 4px 8px; background-color: #ffc107; color: white; border-radius: 3px; text-decoration: none;">
                                ✏️ Editar
                            </a>
                            <a href="<?= site_base('pages/excluir_agendamento.php?id=' . $a['id']) ?>"
                               onclick="return confirm('Deseja realmente excluir este agendamento?')"
                               title="Excluir"
                               style="padding: 4px 8px; background-color: #dc3545; color: white; border-radius: 3px; text-decoration: none;">
                                🗑️ Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
