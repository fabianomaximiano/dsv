<?php
// Arquivo: shortcodes/dashboard-profissional.php

function agend_dashboard_profissional_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Você precisa estar logado como profissional para acessar sua agenda.</p>';
    }

    ob_start();
    global $wpdb;

    $user_id = get_current_user_id();

    // Obter o ID do profissional pelo user_id
    $profissional = $wpdb->get_row($wpdb->prepare(
        "SELECT id, nome FROM {$wpdb->prefix}agend_profissionais WHERE user_id = %d",
        $user_id
    ));

    if (!$profissional) {
        return '<p>Você ainda não está registrado como profissional.</p>';
    }

    // Buscar agendamentos do profissional
    $agendamentos = $wpdb->get_results($wpdb->prepare(
        "SELECT a.*, s.nome AS servico_nome, s.duracao, s.preco, c.nome AS cliente_nome
         FROM {$wpdb->prefix}agend_agendamentos a
         LEFT JOIN {$wpdb->prefix}agend_servicos s ON a.servico_id = s.id
         LEFT JOIN {$wpdb->prefix}agend_clientes c ON a.cliente_id = c.user_id
         WHERE a.profissional_id = %d
         ORDER BY a.data ASC, a.hora ASC",
        $profissional->id
    ));

    echo '<h2>Agenda do Profissional: ' . esc_html($profissional->nome) . '</h2>';

    if ($agendamentos) {
        echo '<table border="1" cellpadding="8" cellspacing="0">';
        echo '<tr><th>Cliente</th><th>Serviço</th><th>Data</th><th>Hora</th><th>Status</th></tr>';

        foreach ($agendamentos as $ag) {
            echo '<tr>';
            echo '<td>' . esc_html($ag->cliente_nome) . '</td>';
            echo '<td>' . esc_html($ag->servico_nome) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($ag->data)) . '</td>';
            echo '<td>' . date('H:i', strtotime($ag->hora)) . '</td>';
            echo '<td>' . ucfirst($ag->status) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>Você ainda não possui agendamentos.</p>';
    }

    return ob_get_clean();
}

add_shortcode('agend_dashboard_profissional', 'agend_dashboard_profissional_shortcode');
