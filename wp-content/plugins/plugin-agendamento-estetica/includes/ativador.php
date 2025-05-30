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
}

/**
 * Função chamada na desativação do plugin
 */
function agend_plugin_desativar() {
    // Pode ser expandida futuramente (limpeza de dados temporários, cron jobs etc.)
}
