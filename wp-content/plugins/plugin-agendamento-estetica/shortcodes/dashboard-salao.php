<?php
// Arquivo: shortcodes/dashboard-salao.php

function agend_dashboard_salao_shortcode() {
    if (!current_user_can('manage_options')) {
        return '<p>Você não tem permissão para acessar o painel do salão.</p>';
    }

    ob_start();
    global $wpdb;

    // Totais
    $total_clientes = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_clientes");
    $total_profissionais = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_profissionais");
    $total_servicos = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_servicos");
    $total_agendamentos = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}agend_agendamentos");

    echo '<h2>Dashboard do Salão</h2>';

    echo '<ul>';
    echo '<li><strong>Total de Clientes:</strong> ' . $total_clientes . '</li>';
    echo '<li><strong>Total de Profissionais:</strong> ' . $total_profissionais . '</li>';
    echo '<li><strong>Total de Serviços:</strong> ' . $total_servicos . '</li>';
    echo '<li><strong>Total de Agendamentos:</strong> ' . $total_agendamentos . '</li>';
    echo '</ul>';

    // Agendamentos recentes
    $agendamentos = $wpdb->get_results("
        SELECT a.*, s.nome AS servico_nome, p.nome AS profissional_nome, c.nome AS cliente_nome
        FROM {$wpdb->prefix}agend_agendamentos a
        LEFT JOIN {$wpdb->prefix}agend_servicos s ON a.servico_id = s.id
        LEFT JOIN {$wpdb->prefix}agend_profissionais p ON a.profissional_id = p.id
        LEFT JOIN {$wpdb->prefix}agend_clientes c ON a.cliente_id = c.user_id
        ORDER BY a.data DESC, a.hora DESC
        LIMIT 10
    ");

    if ($agendamentos) {
        echo '<h3>Últimos 10 Atendimentos</h3>';
        echo '<table border="1" cellpadding="8" cellspacing="0">';
        echo '<tr><th>Cliente</th><th>Serviço</th><th>Profissional</th><th>Data</th><th>Hora</th><th>Status</th></tr>';

        foreach ($agendamentos as $ag) {
            echo '<tr>';
            echo '<td>' . esc_html($ag->cliente_nome) . '</td>';
            echo '<td>' . esc_html($ag->servico_nome) . '</td>';
            echo '<td>' . esc_html($ag->profissional_nome) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($ag->data)) . '</td>';
            echo '<td>' . date('H:i', strtotime($ag->hora)) . '</td>';
            echo '<td>' . ucfirst($ag->status) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>Nenhum atendimento encontrado.</p>';
    }

    return ob_get_clean();
}

add_shortcode('agend_dashboard_salao', 'agend_dashboard_salao_shortcode');
