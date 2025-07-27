<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    exit('ID não informado.');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch();

if (!$agendamento) {
    exit('Agendamento não encontrado.');
}

if (isset($_POST['confirmar'])) {
    $pdo->prepare("DELETE FROM agendamentos WHERE id = ?")->execute([$id]);

    $log = $pdo->prepare("INSERT INTO logs_agendamentos (usuario_id, agendamento_id, acao) VALUES (?, ?, 'EXCLUIR')");
    $log->execute([$_SESSION['usuario_id'], $id]);

    header("Location: " . site_base('pages/admin.php'));
    exit;
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="max-width: 600px; margin: 40px auto;">
    <h2>Excluir Agendamento</h2>

    <p>Tem certeza que deseja excluir o agendamento de <strong><?= htmlspecialchars($agendamento['cliente']) ?></strong> de <strong><?= date('d/m/Y', strtotime($agendamento['data_inicio'])) ?></strong> até <strong><?= date('d/m/Y', strtotime($agendamento['data_fim'])) ?></strong>?</p>

    <form method="post" style="display: flex; gap: 15px; margin-top: 20px;">
        <button type="submit" name="confirmar" style="background-color: #dc3545; color: white; padding: 10px;">Sim, excluir</button>
        <a href="<?= site_base('pages/admin.php') ?>" style="padding: 10px; background-color: #6c757d; color: white; text-decoration: none;">Cancelar</a>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
