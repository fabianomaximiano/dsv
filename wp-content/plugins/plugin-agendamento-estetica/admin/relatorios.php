<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Página de relatórios no admin
 */
function agend_relatorios_pagina() {
    global $wpdb;

    $total_clientes      = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_clientes");
    $total_profissionais = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_profissionais");
    $total_servicos      = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_servicos");
    $total_agendamentos  = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_agendamentos");

    echo '<div class="wrap">';
    echo '<h1>Relatórios do Sistema</h1>';

    echo '<table class="widefat fixed striped" style="max-width: 600px;">';
    echo '<thead><tr><th>Item</th><th>Total</th></tr></thead>';
    echo '<tbody>';
    echo '<tr><td>Total de Clientes</td><td>' . esc_html($total_clientes) . '</td></tr>';
    echo '<tr><td>Total de Profissionais</td><td>' . esc_html($total_profissionais) . '</td></tr>';
    echo '<tr><td>Total de Serviços</td><td>' . esc_html($total_servicos) . '</td></tr>';
    echo '<tr><td>Total de Agendamentos</td><td>' . esc_html($total_agendamentos) . '</td></tr>';
    echo '</tbody>';
    echo '</table>';

    echo '</div>';
}
