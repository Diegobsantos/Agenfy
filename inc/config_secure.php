<?php
/**
 * Configuração Segura - Carrega variáveis de ambiente
 */

// Função para carregar .env
function loadEnv($file) {
    if (!file_exists($file)) {
        die('Arquivo .env não encontrado. Copie .env.example para .env e configure.');
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue; // Ignora comentários
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Carrega o arquivo .env
loadEnv(__DIR__ . '/../.env');

// Configurações do banco com fallback seguro
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'agenda');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Configurações do site
$sitebase = $_ENV['SITE_BASE_URL'] ?? 'http://localhost/Agenfy/';
define('APP_ROOT', __DIR__ . '/..');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');

// Configurações de segurança
define('SESSION_LIFETIME', (int)($_ENV['SESSION_LIFETIME'] ?? 3600));
define('CSRF_TOKEN_LIFETIME', (int)($_ENV['CSRF_TOKEN_LIFETIME'] ?? 1800));
define('MAX_LOGIN_ATTEMPTS', (int)($_ENV['MAX_LOGIN_ATTEMPTS'] ?? 5));
define('LOGIN_LOCKOUT_TIME', (int)($_ENV['LOGIN_LOCKOUT_TIME'] ?? 900));

// Configurações de log
define('LOG_LEVEL', $_ENV['LOG_LEVEL'] ?? 'error');
define('LOG_FILE', $_ENV['LOG_FILE'] ?? 'logs/app.log');

function site_base($path = '') {
    global $sitebase;
    return rtrim($sitebase, '/') . '/' . ltrim($path, '/');
}

// Configuração segura de sessões
function configureSecureSessions() {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
}

configureSecureSessions();
?>