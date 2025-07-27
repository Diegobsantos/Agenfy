<?php
include_once __DIR__ . '/../inc/config_secure.php';
include_once APP_ROOT . '/inc/csrf.php';
include_once APP_ROOT . '/inc/logger.php';

session_start();

// Redireciona se já estiver logado
if (isset($_SESSION['usuario'])) {
    header("Location: " . site_base('pages/admin.php'));
    exit;
}

// Verifica se está bloqueado
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$isBlocked = LoginRateLimit::isBlocked($ip);
$timeRemaining = $isBlocked ? LoginRateLimit::getTimeRemaining($ip) : 0;
?>

<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="max-width: 400px; margin: 60px auto; padding: 20px;">
    <h2 style="text-align: center;">Login</h2>

    <?php if (isset($_GET['erro'])): ?>
        <?php 
        $erro = $_GET['erro'];
        $mensagem = '';
        $cor = 'red';
        
        switch($erro) {
            case 'credenciais':
                $mensagem = 'Usuário ou senha inválidos!';
                break;
            case 'campos':
                $mensagem = 'Todos os campos são obrigatórios!';
                break;
            case 'blocked':
                $minutes = $_GET['time'] ?? 0;
                $mensagem = "Muitas tentativas falhadas. Tente novamente em $minutes minuto(s).";
                break;
            case 'sistema':
                $mensagem = 'Erro interno do sistema. Tente novamente.';
                break;
            default:
                $mensagem = 'Erro no login. Tente novamente.';
        }
        ?>
        <div style="background-color: #ffe6e6; border: 1px solid #ff9999; color: <?= $cor ?>; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px;">
            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($isBlocked): ?>
        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px;">
            <strong>⚠️ Acesso Temporariamente Bloqueado</strong><br>
            Seu IP foi bloqueado devido a múltiplas tentativas de login.<br>
            Tempo restante: <span id="countdown"><?= ceil($timeRemaining / 60) ?></span> minuto(s)
        </div>
        
        <script>
        let timeLeft = <?= $timeRemaining ?>;
        const countdown = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            timeLeft--;
            const minutes = Math.ceil(timeLeft / 60);
            countdown.textContent = minutes;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                location.reload();
            }
        }, 1000);
        </script>
    <?php endif; ?>

    <form action="<?= site_base('actions/login.php') ?>" method="post" style="display: flex; flex-direction: column; gap: 15px;" <?= $isBlocked ? 'style="opacity: 0.5; pointer-events: none;"' : '' ?>>
        <?= CSRFProtection::getTokenField() ?>
        
        <input type="text" name="usuario" placeholder="Usuário" required 
               style="padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
               <?= $isBlocked ? 'disabled' : '' ?> />
               
        <input type="password" name="senha" placeholder="Senha" required 
               style="padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
               <?= $isBlocked ? 'disabled' : '' ?> />
               
        <button type="submit" 
                style="padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;"
                <?= $isBlocked ? 'disabled' : '' ?>>
            <?= $isBlocked ? 'Bloqueado' : 'Entrar' ?>
        </button>
    </form>
    
    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; font-size: 14px; color: #6c757d;">
        <strong>Dicas de Segurança:</strong><br>
        • Use senhas fortes com letras, números e símbolos<br>
        • Não compartilhe suas credenciais<br>
        • Faça logout ao terminar de usar o sistema
    </div>
</section>

<style>
button:disabled {
    background-color: #6c757d !important;
    cursor: not-allowed !important;
}

input:disabled {
    background-color: #e9ecef !important;
    cursor: not-allowed !important;
}
</style>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
