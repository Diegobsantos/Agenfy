<?php
/**
 * Sistema de Logs de Segurança
 */

class SecurityLogger {
    
    private static function ensureLogDirectory() {
        $logDir = dirname(APP_ROOT . '/' . LOG_FILE);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    private static function writeLog($level, $message, $context = []) {
        self::ensureLogDirectory();
        
        $logFile = APP_ROOT . '/' . LOG_FILE;
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => strtoupper($level),
            'ip' => $ip,
            'user_agent' => $userAgent,
            'message' => $message,
            'context' => $context
        ];
        
        $logLine = json_encode($logEntry) . PHP_EOL;
        
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Log de tentativa de login
     */
    public static function logLoginAttempt($username, $success = false) {
        $message = $success ? "Login successful for user: $username" : "Failed login attempt for user: $username";
        $level = $success ? 'info' : 'warning';
        
        self::writeLog($level, $message, ['username' => $username, 'success' => $success]);
    }
    
    /**
     * Log de erro de segurança
     */
    public static function logSecurityEvent($event, $details = []) {
        self::writeLog('error', "Security event: $event", $details);
    }
    
    /**
     * Log de tentativa de CSRF
     */
    public static function logCSRFAttempt($page) {
        self::writeLog('error', "CSRF attempt detected", ['page' => $page]);
    }
    
    /**
     * Log de tentativa de acesso não autorizado
     */
    public static function logUnauthorizedAccess($page, $userId = null) {
        self::writeLog('warning', "Unauthorized access attempt", [
            'page' => $page,
            'user_id' => $userId
        ]);
    }
    
    /**
     * Log de alteração importante nos dados
     */
    public static function logDataChange($action, $table, $recordId, $userId) {
        self::writeLog('info', "Data change: $action", [
            'action' => $action,
            'table' => $table,
            'record_id' => $recordId,
            'user_id' => $userId
        ]);
    }
    
    /**
     * Log de erro genérico
     */
    public static function logError($message, $context = []) {
        self::writeLog('error', $message, $context);
    }
}

/**
 * Sistema de Rate Limiting para Login
 */
class LoginRateLimit {
    
    private static function getAttemptKey($ip) {
        return "login_attempts_" . md5($ip);
    }
    
    private static function getLockoutKey($ip) {
        return "login_lockout_" . md5($ip);
    }
    
    /**
     * Verifica se o IP está bloqueado
     */
    public static function isBlocked($ip) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $lockoutKey = self::getLockoutKey($ip);
        
        if (isset($_SESSION[$lockoutKey])) {
            $lockoutTime = $_SESSION[$lockoutKey];
            
            if (time() - $lockoutTime < LOGIN_LOCKOUT_TIME) {
                return true;
            } else {
                // Remove o bloqueio expirado
                unset($_SESSION[$lockoutKey]);
                unset($_SESSION[self::getAttemptKey($ip)]);
            }
        }
        
        return false;
    }
    
    /**
     * Registra uma tentativa de login falhada
     */
    public static function recordFailedAttempt($ip) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $attemptKey = self::getAttemptKey($ip);
        $attempts = $_SESSION[$attemptKey] ?? 0;
        $attempts++;
        
        $_SESSION[$attemptKey] = $attempts;
        
        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION[self::getLockoutKey($ip)] = time();
            SecurityLogger::logSecurityEvent("IP blocked due to multiple failed login attempts", ['ip' => $ip, 'attempts' => $attempts]);
        }
        
        SecurityLogger::logSecurityEvent("Failed login attempt", ['ip' => $ip, 'attempts' => $attempts]);
    }
    
    /**
     * Limpa tentativas após login bem-sucedido
     */
    public static function clearAttempts($ip) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION[self::getAttemptKey($ip)]);
        unset($_SESSION[self::getLockoutKey($ip)]);
    }
    
    /**
     * Retorna tempo restante de bloqueio
     */
    public static function getTimeRemaining($ip) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $lockoutKey = self::getLockoutKey($ip);
        
        if (isset($_SESSION[$lockoutKey])) {
            $remaining = LOGIN_LOCKOUT_TIME - (time() - $_SESSION[$lockoutKey]);
            return max(0, $remaining);
        }
        
        return 0;
    }
}
?>