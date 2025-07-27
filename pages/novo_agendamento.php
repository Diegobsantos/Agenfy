<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'];
    $inicio = $_POST['data_inicio'];
    $fim = $_POST['data_fim'];
    $obs = $_POST['observacao'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica conflito de datas
    $verifica = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE (data_inicio <= ? AND data_fim >= ?)");
    $verifica->execute([$fim, $inicio]);
    $conflito = $verifica->fetchColumn();

    if ($conflito > 0) {
        $mensagem = "❌ Já existe agendamento nesse período.";
    } else {
        $insert = $pdo->prepare("INSERT INTO agendamentos (cliente, data_inicio, data_fim, observacao, criado_por) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$cliente, $inicio, $fim, $obs, $usuario_id]);

        // Log
        $id = $pdo->lastInsertId();
        $log = $pdo->prepare("INSERT INTO logs_agendamentos (usuario_id, agendamento_id, acao) VALUES (?, ?, 'INSERIR')");
        $log->execute([$usuario_id, $id]);

        header("Location: " . site_base('pages/admin.php'));
        exit;
    }
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="max-width: 600px; margin: 40px auto;">
    <h2>Novo Agendamento</h2>

    <?php if ($mensagem): ?>
        <p style="color: red; font-weight: bold;"><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="text" name="cliente" placeholder="Nome do cliente" required>
        <input type="date" name="data_inicio" required>
        <input type="date" name="data_fim" required>
        <textarea name="observacao" rows="4" placeholder="Observações (opcional)"></textarea>

        <button type="submit" style="background-color: #28a745; color: white; padding: 10px;">Salvar</button>
        <a href="<?= site_base('pages/admin.php') ?>" style="text-align: center;">← Voltar</a>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
