<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    exit('ID inválido');
}

$id = (int) $_GET['id'];

// Buscar dados
$stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch();

if (!$agendamento) {
    exit('Agendamento não encontrado');
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'];
    $inicio = $_POST['data_inicio'];
    $fim = $_POST['data_fim'];
    $obs = $_POST['observacao'];
    $usuario_id = $_SESSION['usuario_id'];

    $update = $pdo->prepare("UPDATE agendamentos SET cliente = ?, data_inicio = ?, data_fim = ?, observacao = ? WHERE id = ?");
    $update->execute([$cliente, $inicio, $fim, $obs, $id]);

    // Log
    $log = $pdo->prepare("INSERT INTO logs_agendamentos (usuario_id, agendamento_id, acao) VALUES (?, ?, 'EDITAR')");
    $log->execute([$usuario_id, $id]);

    header("Location: " . site_base('pages/admin.php'));
    exit;
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="max-width: 600px; margin: 40px auto;">
    <h2>Editar Agendamento</h2>

    <form method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="text" name="cliente" value="<?= htmlspecialchars($agendamento['cliente']) ?>" required>
        <input type="date" name="data_inicio" value="<?= $agendamento['data_inicio'] ?>" required>
        <input type="date" name="data_fim" value="<?= $agendamento['data_fim'] ?>" required>
        <textarea name="observacao" rows="4"><?= htmlspecialchars($agendamento['observacao']) ?></textarea>
        <button type="submit" style="background-color: #007bff; color: white; padding: 10px;">Salvar Alterações</button>
        <a href="<?= site_base('pages/admin.php') ?>" style="text-align: center;">← Voltar</a>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
