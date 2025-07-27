# 📅 Agenfy – Sistema de Agendamentos

**Agenfy** é um sistema moderno e leve, desenvolvido em **PHP puro com MySQL**, ideal para controle de **agendamentos de espaços, serviços ou atendimentos**. Pensado para ser simples, modular e personalizável, o projeto é uma excelente base para soluções comerciais ou institucionais.

## 🚀 Funcionalidades

### Área Pública
- Página inicial com galeria responsiva de imagens
- Agenda pública com calendário interativo (FullCalendar)
- Página de contato com links diretos para WhatsApp e redes sociais

### Área Administrativa
- Login seguro com controle de sessão
- Painel com calendário de agendamentos
- Listagem detalhada de agendamentos com:
  - Inclusão
  - Edição
  - Exclusão
- Gerenciamento de usuários (CRUD)
- Registro de logs de ações (auditoria)
- Impressão rápida da lista de agendamentos

## 🧱 Estrutura

```
/agenfy
├── assets/            # CSS, JS, imagens e bibliotecas externas
├── inc/               # Includes reutilizáveis (header, footer, db, etc.)
├── pages/             # Páginas internas do sistema
├── actions/           # Scripts para login, CRUD e lógica do sistema
├── index.php          # Página inicial
├── config.php         # Configuração central do sistema
└── .htaccess          # Regras de URL e segurança
```

## 🧑‍💻 Tecnologias

- **PHP** (sem frameworks)
- **MySQL** (via PDO)
- **JavaScript** / **jQuery**
- **FullCalendar**
- **HTML5 + CSS3**
- Estrutura preparada para modularização e expansão futura

## 📦 Requisitos

- PHP 7.4 ou superior (recomendado: PHP 8+)
- MySQL 5.7+ ou MariaDB
- Apache com suporte a `.htaccess`
- Extensão **PDO** habilitada

## 📄 Banco de Dados

O sistema utiliza 3 tabelas principais:

- `usuarios` – controle de login
- `agendamentos` – controle dos eventos
- `logs_agendamentos` – registro de ações administrativas

> ⚠️ Recomendado: `utf8mb4_general_ci` como collation para compatibilidade ampla.

## ⚙️ Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seuusuario/agenfy.git
   ```
2. Crie um banco MySQL e importe o script SQL incluído.
3. Configure a conexão no arquivo: `inc/db.php`
4. Ajuste a função `site_base()` no `config.php` conforme o ambiente.
5. Acesse o sistema via navegador.

## 🧪 Acesso de Teste

| Usuário  | Senha   |
|----------|---------|
| admin    | admin   |

> Troque a senha imediatamente após o primeiro acesso.

## 🔐 Segurança

- Login com verificação de sessão
- Confirmações para ações críticas
- Auditoria básica de alterações (logs)
- Código estruturado para separação entre lógica e apresentação

## 📌 Licença

Projeto open-source sob a licença [MIT](LICENSE).

## ✨ Possibilidades de Expansão

- Suporte a múltiplos perfis de acesso
- Envio de notificações por e-mail ou WhatsApp
- Relatórios por período
- Integração com API externa (Google Calendar, Zapier, etc.)

---

Desenvolvido por [Diego Barbosa](https://github.com/diegobarbosa)  
Ideal para projetos sob medida e revenda de sistemas de agendamento.
