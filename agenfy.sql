-- Agenfy - Script de Criação de Banco de Dados
-- Banco: agenda
-- Compatível com MySQL 5.7+ ou MariaDB

CREATE DATABASE IF NOT EXISTS agenda DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE agenda;

-- Tabela: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabela: agendamentos
CREATE TABLE IF NOT EXISTS agendamentos (
  id INT NOT NULL AUTO_INCREMENT,
  cliente VARCHAR(100) NOT NULL,
  data_inicio DATE NOT NULL,
  data_fim DATE NOT NULL,
  observacao TEXT,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  criado_por INT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabela: logs_agendamentos
CREATE TABLE IF NOT EXISTS logs_agendamentos (
  id INT NOT NULL AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  agendamento_id INT DEFAULT NULL,
  acao ENUM('INSERIR','EDITAR','EXCLUIR') NOT NULL,
  data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY fk_usuario_id (usuario_id),
  CONSTRAINT logs_agendamentos_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de usuário padrão
INSERT INTO usuarios (nome, usuario, senha)
VALUES ('Administrador', 'admin', '$2y$10$cF2BFP5/M5GyI/UFEeprJuabbiIBx95bVHr5WtOiwUlN81GlzhWMi');

-- Fim do script
