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

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    exit('Usuário não encontrado');
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $login = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    // Verificar duplicidade (exceto o próprio)
    $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? AND id != ?");
    $verifica->execute([$login, $id]);

    if ($verifica->rowCount() > 0) {
        $mensagem = "❌ Nome de usuário já está em uso.";
    } else {
        if (!empty($senha)) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuarios SET nome = ?, usuario = ?, senha = ? WHERE id = ?");
            $update->execute([$nome, $login, $hash, $id]);
        } else {
            $update = $pdo->prepare("UPDATE usuarios SET nome = ?, usuario = ? WHERE id = ?");
            $update->execute([$nome, $login, $id]);
        }

        header("Location: " . site_base('pages/usuarios.php'));
        exit;
    }
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>
<?php include_once APP_ROOT . '/inc/admin_nav.php'; ?>

<section id="main" style="max-width: 600px; margin: 40px auto;">
    <h2>Editar Usuário</h2>

    <?php if ($mensagem): ?>
        <p style="color: red; font-weight: bold;"><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
        <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
        <input type="password" name="senha" placeholder="Nova senha (deixe em branco para manter)">
        <button type="submit" style="background-color: #007bff; color: white; padding: 10px;">Salvar Alterações</button>
        <a href="<?= site_base('pages/usuarios.php') ?>" style="text-align: center;">← Voltar</a>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
