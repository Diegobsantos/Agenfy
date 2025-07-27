<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nome ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>
<?php include_once APP_ROOT . '/inc/admin_nav.php'; ?>

<section id="main" style="max-width: 800px; margin: 40px auto;">
    <h2>Gerenciar Usuários</h2>

    <a href="<?= site_base('pages/novo_usuario.php') ?>" style="padding: 10px 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 20px; display: inline-block;">➕ Novo Usuário</a>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #007bff; color: white;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Nome</th>
                <th style="padding: 10px;">Usuário</th>
                <th style="padding: 10px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr><td colspan="4" style="text-align: center; padding: 15px;">Nenhum usuário encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <td style="padding: 8px;"><?= $u['id'] ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($u['nome']) ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($u['usuario']) ?></td>
                        <td style="padding: 8px;">
                            <a href="<?= site_base('pages/editar_usuario.php?id=' . $u['id']) ?>" style="margin-right: 10px; color: #007bff;">✏️ Editar</a>
                            <a href="<?= site_base('pages/excluir_usuario.php?id=' . $u['id']) ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este usuário?')"
                               style="color: #dc3545;">🗑️ Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
