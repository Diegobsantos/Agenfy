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
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    // Verificar se usuário já existe
    $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $verifica->execute([$usuario]);

    if ($verifica->rowCount() > 0) {
        $mensagem = "❌ Nome de usuário já está em uso.";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO usuarios (nome, usuario, senha) VALUES (?, ?, ?)");
        $insert->execute([$nome, $usuario, $hash]);

        header("Location: " . site_base('pages/usuarios.php'));
        exit;
    }
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>
<?php include_once APP_ROOT . '/inc/admin_nav.php'; ?>

<section id="main" style="max-width: 600px; margin: 40px auto;">
    <h2>Novo Usuário</h2>

    <?php if ($mensagem): ?>
        <p style="color: red; font-weight: bold;"><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="text" name="usuario" placeholder="Nome de usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>

        <button type="submit" style="background-color: #28a745; color: white; padding: 10px;">Cadastrar</button>
        <a href="<?= site_base('pages/usuarios.php') ?>" style="text-align: center;">← Voltar</a>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
