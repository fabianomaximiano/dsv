<?php
// includes/ativador.php

if (!defined('ABSPATH')) {
    exit;
}

require_once AGEND_PLUGIN_PATH . 'includes/install.php';

/**
 * Função chamada na ativação do plugin
 */
function agend_plugin_ativar() {
    agend_criar_tabelas();
    agend_criar_paginas_publicas(); // Adicionada chamada para criar páginas
}

/**
 * Função chamada na desativação do plugin
 */
function agend_plugin_desativar() {
    // Pode ser expandida futuramente (limpeza de dados temporários, cron jobs etc.)
}

/**
 * Cria as páginas públicas automaticamente na ativação
 */
function agend_criar_paginas_publicas() {
    $paginas = [
        'cadastro-cliente' => 'Cadastro de Cliente',
        'painel-cliente'   => 'Painel do Cliente',
        'cadastro-profissional' => 'Cadastro de Profissional',
        'dashboard-cliente' => 'Dashboard do Cliente',
        'dashboard-profissional' => 'Dashboard do Profissional',
        'dashboard-salao' => 'Dashboard do Salão'
        // Adicione mais se necessário
    ];

    foreach ($paginas as $slug => $titulo) {
        $pagina = get_page_by_path($slug);
        if (!$pagina) {
            wp_insert_post([
                'post_title'     => $titulo,
                'post_name'      => $slug,
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'post_content'   => '', // Evite usar shortcodes diretamente aqui
                'post_author'    => 1
            ]);
        }
    }
}
