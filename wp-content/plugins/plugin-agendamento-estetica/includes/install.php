<?php
// includes/install.php

if (!defined('ABSPATH')) {
    exit;
}

function agend_criar_tabelas() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $prefix = $wpdb->prefix . 'agend_';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = [];

    // Tabela de clientes
    $sql[] = "CREATE TABLE {$prefix}clientes (
        id INT NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20),
        cpf VARCHAR(20),
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Tabela de profissionais
    $sql[] = "CREATE TABLE {$prefix}profissionais (
        id INT NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20),
        especialidade VARCHAR(100),
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Tabela de serviços
    $sql[] = "CREATE TABLE {$prefix}servicos (
        id INT NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        duracao INT NOT NULL,
        preco DECIMAL(10,2) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Tabela de agendamentos
    $sql[] = "CREATE TABLE {$prefix}agendamentos (
        id INT NOT NULL AUTO_INCREMENT,
        cliente_id INT NOT NULL,
        profissional_id INT NOT NULL,
        servico_id INT NOT NULL,
        data_hora DATETIME NOT NULL,
        status VARCHAR(50) DEFAULT 'pendente',
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Tabela relacional entre profissionais e serviços
    $sql[] = "CREATE TABLE {$prefix}profissionais_servicos (
        id INT NOT NULL AUTO_INCREMENT,
        profissional_id INT NOT NULL,
        servico_id INT NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Executa todas as queries
    foreach ($sql as $query) {
        dbDelta($query);
    }
}
