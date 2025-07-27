<?php
include_once __DIR__ . '/../config.php';


session_start();

// Redireciona se já estiver logado
if (isset($_SESSION['usuario'])) {
    header("Location: " . site_base('pages/admin.php'));
    exit;
}
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="max-width: 400px; margin: 60px auto; padding: 20px;">
    <h2 style="text-align: center;">Login</h2>

    <?php if (isset($_GET['erro'])): ?>
        <p style="color: red; text-align: center;">Usuário ou senha inválidos!</p>
    <?php endif; ?>

    <form action="<?= site_base('actions/login.php') ?>" method="post" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="text" name="usuario" placeholder="Usuário" required style="padding: 10px;" />
        <input type="password" name="senha" placeholder="Senha" required style="padding: 10px;" />
        <button type="submit" style="padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer;">Entrar</button>
    </form>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
