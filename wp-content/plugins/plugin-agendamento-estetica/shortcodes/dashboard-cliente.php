<?php
// Arquivo: shortcodes/dashboard-cliente.php

function agend_dashboard_cliente_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Você precisa estar logado para acessar sua agenda.</p>';
    }

    ob_start();
    global $wpdb;

    $user_id = get_current_user_id();

    // Buscar agendamentos do cliente atual
    $agendamentos = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT a.*, s.nome AS servico_nome, s.duracao, s.preco, p.nome AS profissional_nome
            FROM {$wpdb->prefix}agendamentos a
            LEFT JOIN {$wpdb->prefix}servicos s ON a.servico_id = s.id
            LEFT JOIN {$wpdb->prefix}profissionais p ON a.profissional_id = p.id
            WHERE a.cliente_id = %d
            ORDER BY a.data DESC, a.hora DESC",
            $user_id
        )
    );

    if ($agendamentos) {
        echo '<h2>Meus Agendamentos</h2>';
        echo '<table border="1" cellpadding="8" cellspacing="0">';
        echo '<tr><th>Serviço</th><th>Profissional</th><th>Data</th><th>Hora</th><th>Status</th><th>Valor</th></tr>';

        foreach ($agendamentos as $ag) {
            echo '<tr>';
            echo '<td>' . esc_html($ag->servico_nome) . '</td>';
            echo '<td>' . esc_html($ag->profissional_nome) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($ag->data)) . '</td>';
            echo '<td>' . date('H:i', strtotime($ag->hora)) . '</td>';
            echo '<td>' . ucfirst($ag->status) . '</td>';
            echo '<td>R$ ' . number_format($ag->preco, 2, ',', '.') . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>Você ainda não possui agendamentos.</p>';
    }

    return ob_get_clean();
}

add_shortcode('agend_dashboard_cliente', 'agend_dashboard_cliente_shortcode');
