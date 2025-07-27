<?php
/**
 * Sistema de Validação e Sanitização
 */

class Validator {
    
    /**
     * Sanitiza string removendo tags e caracteres perigosos
     */
    public static function sanitizeString($input) {
        if ($input === null) return '';
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Valida e sanitiza email
     */
    public static function validateEmail($email) {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? $email : false;
    }
    
    /**
     * Valida data no formato Y-m-d H:i:s ou Y-m-d
     */
    public static function validateDateTime($date, $format = 'Y-m-d H:i:s') {
        if (empty($date)) return false;
        
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Valida se é uma data válida (aceita vários formatos)
     */
    public static function validateDate($date) {
        if (empty($date)) return false;
        
        // Tenta diferentes formatos
        $formats = ['Y-m-d', 'Y-m-d H:i:s', 'd/m/Y', 'd/m/Y H:i:s'];
        
        foreach ($formats as $format) {
            if (self::validateDateTime($date, $format)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Valida número inteiro
     */
    public static function validateInt($value, $min = null, $max = null) {
        $int = filter_var($value, FILTER_VALIDATE_INT);
        
        if ($int === false) return false;
        
        if ($min !== null && $int < $min) return false;
        if ($max !== null && $int > $max) return false;
        
        return $int;
    }
    
    /**
     * Valida string com comprimento mínimo/máximo
     */
    public static function validateString($string, $minLength = 1, $maxLength = 255) {
        $string = self::sanitizeString($string);
        $length = strlen($string);
        
        return $length >= $minLength && $length <= $maxLength ? $string : false;
    }
    
    /**
     * Valida senha forte
     */
    public static function validatePassword($password) {
        if (strlen($password) < 8) {
            return ['valid' => false, 'message' => 'Senha deve ter pelo menos 8 caracteres'];
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            return ['valid' => false, 'message' => 'Senha deve conter pelo menos uma letra maiúscula'];
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            return ['valid' => false, 'message' => 'Senha deve conter pelo menos uma letra minúscula'];
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            return ['valid' => false, 'message' => 'Senha deve conter pelo menos um número'];
        }
        
        return ['valid' => true, 'message' => 'Senha válida'];
    }
    
    /**
     * Valida dados de agendamento
     */
    public static function validateAgendamento($data) {
        $errors = [];
        
        // Valida cliente
        $cliente = self::validateString($data['cliente'] ?? '', 2, 100);
        if (!$cliente) {
            $errors['cliente'] = 'Nome do cliente é obrigatório (2-100 caracteres)';
        }
        
        // Valida data de início
        if (!self::validateDateTime($data['data_inicio'] ?? '')) {
            $errors['data_inicio'] = 'Data/hora de início inválida';
        }
        
        // Valida data de fim
        if (!self::validateDateTime($data['data_fim'] ?? '')) {
            $errors['data_fim'] = 'Data/hora de fim inválida';
        }
        
        // Valida se data de fim é posterior à de início
        if (empty($errors['data_inicio']) && empty($errors['data_fim'])) {
            $inicio = new DateTime($data['data_inicio']);
            $fim = new DateTime($data['data_fim']);
            
            if ($fim <= $inicio) {
                $errors['data_fim'] = 'Data de fim deve ser posterior à data de início';
            }
        }
        
        // Valida observação (opcional)
        $observacao = '';
        if (!empty($data['observacao'])) {
            $observacao = self::validateString($data['observacao'], 0, 500);
            if ($observacao === false) {
                $errors['observacao'] = 'Observação muito longa (máximo 500 caracteres)';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => [
                'cliente' => $cliente,
                'data_inicio' => $data['data_inicio'] ?? '',
                'data_fim' => $data['data_fim'] ?? '',
                'observacao' => $observacao
            ]
        ];
    }
}
?>