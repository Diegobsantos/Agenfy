<?php include_once __DIR__ . '/../config.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agenfy</title>

  <link rel="stylesheet" href="<?= site_base('assets/lightbox2/css/lightbox.min.css') ?>">
  <link rel="stylesheet" href="<?= site_base('assets/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= site_base('assets/css/main.css') ?>">

  <style>
    nav#navbar {
      background: #222;
      padding: 10px 20px;
      display: flex;
      justify-content: flex-start;
      gap: 30px;
    }
    nav#navbar a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      padding: 10px;
    }
    nav#navbar a:hover {
      color: #0f0;
    }
    nav#navbar a.active {
      color: #0f0;
      border-bottom: 2px solid #0f0;
    }
  </style>
</head>
<body>

<nav id="navbar">
  <a href="<?= site_base('index.php') ?>" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Início</a>
  <a href="<?= site_base('pages/agenda.php') ?>" class="<?= basename($_SERVER['PHP_SELF']) == 'agenda.php' ? 'active' : '' ?>">Agenda</a>
  <a href="<?= site_base('pages/contato.php') ?>" class="<?= basename($_SERVER['PHP_SELF']) == 'contato.php' ? 'active' : '' ?>">Contato</a>
  <a href="<?= site_base('pages/login.php') ?>" class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">Admin</a>
</nav>
