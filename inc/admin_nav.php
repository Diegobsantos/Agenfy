<?php
// Impede acesso direto
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    exit('Acesso direto não permitido.');
}

// Garante sessão ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: " . site_base('login.php'));
    exit;
}
?>

<nav style="background-color: #f1f1f1; padding: 10px 20px; display: flex; gap: 20px; align-items: center; border-bottom: 1px solid #ccc; flex-wrap: wrap;">
    <a href="<?= site_base('pages/admin.php') ?>" style="text-decoration: none; font-weight: bold;">
        📅 Agenda
    </a>

    <a href="<?= site_base('pages/novo_agendamento.php') ?>" style="text-decoration: none; font-weight: bold;">
        ➕ Novo Agendamento
    </a>

    <a href="<?= site_base('pages/usuarios.php') ?>" style="text-decoration: none; font-weight: bold;">
        👤 Gerenciar Usuários
    </a>
    
    <span style="margin-left: auto;">👤 <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></span>

    <a href="<?= site_base('actions/logout.php') ?>"
       onclick="return confirm('Tem certeza que deseja sair?')"
       style="padding: 8px 16px; background-color: #dc3545; color: #fff; text-decoration: none; border-radius: 4px;">
        Sair
    </a>
</nav>


