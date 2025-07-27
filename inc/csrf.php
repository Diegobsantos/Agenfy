<?php
/**
 * Sistema de Proteção CSRF
 */

class CSRFProtection {
    private static $tokenName = 'csrf_token';
    
    /**
     * Gera um novo token CSRF
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::$tokenName] = $token;
        $_SESSION[self::$tokenName . '_time'] = time();
        
        return $token;
    }
    
    /**
     * Valida o token CSRF
     */
    public static function validateToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verifica se o token existe na sessão
        if (!isset($_SESSION[self::$tokenName])) {
            return false;
        }
        
        // Verifica se o token não expirou
        if (isset($_SESSION[self::$tokenName . '_time'])) {
            $tokenAge = time() - $_SESSION[self::$tokenName . '_time'];
            if ($tokenAge > CSRF_TOKEN_LIFETIME) {
                unset($_SESSION[self::$tokenName]);
                unset($_SESSION[self::$tokenName . '_time']);
                return false;
            }
        }
        
        // Compara os tokens de forma segura
        $isValid = hash_equals($_SESSION[self::$tokenName], $token);
        
        // Remove o token após uso (proteção adicional)
        if ($isValid) {
            unset($_SESSION[self::$tokenName]);
            unset($_SESSION[self::$tokenName . '_time']);
        }
        
        return $isValid;
    }
    
    /**
     * Gera campo hidden HTML com token
     */
    public static function getTokenField() {
        $token = self::generateToken();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
    
    /**
     * Middleware para verificar CSRF em requisições POST
     */
    public static function checkRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[self::$tokenName] ?? '';
            
            if (!self::validateToken($token)) {
                http_response_code(403);
                die('Token CSRF inválido ou expirado. Recarregue a página e tente novamente.');
            }
        }
    }
}
?>