<?php
include_once __DIR__ . '/../inc/config_secure.php';
include_once APP_ROOT . '/inc/db.php';
include_once APP_ROOT . '/inc/csrf.php';
include_once APP_ROOT . '/inc/validator.php';
include_once APP_ROOT . '/inc/logger.php';

session_start();

// Verifica proteção CSRF
CSRFProtection::checkRequest();

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Verifica rate limiting
if (LoginRateLimit::isBlocked($ip)) {
    $timeRemaining = LoginRateLimit::getTimeRemaining($ip);
    $minutes = ceil($timeRemaining / 60);
    
    SecurityLogger::logSecurityEvent("Blocked login attempt during lockout", ['ip' => $ip]);
    header("Location: " . site_base('pages/login.php?erro=blocked&time=' . $minutes));
    exit;
}

$usuario = Validator::sanitizeString($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validação básica
if (empty($usuario) || empty($senha)) {
    LoginRateLimit::recordFailedAttempt($ip);
    SecurityLogger::logLoginAttempt($usuario, false);
    header("Location: " . site_base('pages/login.php?erro=campos'));
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        // Login bem-sucedido
        LoginRateLimit::clearAttempts($ip);
        SecurityLogger::logLoginAttempt($usuario, true);
        
        // Regenera session ID por segurança
        session_regenerate_id(true);
        
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['login_time'] = time();
        
        header("Location: " . site_base('pages/admin.php'));
    } else {
        // Login falhou
        LoginRateLimit::recordFailedAttempt($ip);
        SecurityLogger::logLoginAttempt($usuario, false);
        header("Location: " . site_base('pages/login.php?erro=credenciais'));
    }
} catch (Exception $e) {
    SecurityLogger::logError("Database error during login", ['error' => $e->getMessage()]);
    header("Location: " . site_base('pages/login.php?erro=sistema'));
}
