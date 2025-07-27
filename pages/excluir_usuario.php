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

// Protege contra autoexclusão
if ($_SESSION['usuario_id'] == $id) {
    exit('Você não pode excluir seu próprio usuário.');
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    exit('Usuário não encontrado');
}

if (isset($_POST['confirmar'])) {
    $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);
    header("Location: " . site_base('pages/usuarios.php'));
    exit;
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="max-width: 600px; margin: 40px auto;">
    <h2>Excluir Usuário</h2>

    <p>Tem certeza que deseja excluir o usuário <strong><?= htmlspecialchars($usuario['nome']) ?></strong>?</p>

    <form method="post" style="display: flex; gap: 15px; margin-top: 20px;">
        <button type="submit" name="confirmar" style="background-color: #dc3545; color: white; padding: 10px;">Sim, excluir</button>
        <a href="<?= site_base('pages/usuarios.php') ?>" style="padding: 10px; background-color: #6c757d; color: white; text-decoration: none;">Cancelar</a>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
