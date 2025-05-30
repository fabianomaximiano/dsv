<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

function agend_relatorios_pagina() {
    global $wpdb;

    $t_agendamentos  = $wpdb->prefix . 'agend_agendamentos';
    $t_profissionais = $wpdb->prefix . 'agend_profissionais';
    $t_servicos      = $wpdb->prefix . 'agend_servicos';

    // Filtro de datas (opcional)
    $data_inicio = isset($_GET['data_inicio']) ? sanitize_text_field($_GET['data_inicio']) : '';
    $data_fim    = isset($_GET['data_fim']) ? sanitize_text_field($_GET['data_fim']) : '';

    $where = '';
    if ($data_inicio && $data_fim) {
        $where = $wpdb->prepare("WHERE data BETWEEN %s AND %s", $data_inicio, $data_fim);
    }

    echo '<div class="wrap">';
    echo '<h1>Relatórios de Agendamentos</h1>';

    // Formulário de filtro
    echo '<form method="get">';
    echo '<input type="hidden" name="page" value="agend-relatorios">';
    echo '<label for="data_inicio">De: </label>';
    echo '<input type="date" id="data_inicio" name="data_inicio" value="' . esc_attr($data_inicio) . '">';
    echo ' <label for="data_fim">Até: </label>';
    echo '<input type="date" id="data_fim" name="data_fim" value="' . esc_attr($data_fim) . '">';
    echo ' <input type="submit" class="button button-primary" value="Filtrar">';
    echo '</form>';

    echo '<hr>';

    // 1. Quantidade por status
    $status_data = $wpdb->get_results("
        SELECT status, COUNT(*) as total 
        FROM $t_agendamentos 
        $where 
        GROUP BY status
    ");

    echo '<h2>Agendamentos por Status</h2>';
    if ($status_data) {
        echo '<ul>';
        foreach ($status_data as $linha) {
            echo '<li><strong>' . esc_html($linha->status) . ':</strong> ' . esc_html($linha->total) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Nenhum dado disponível.</p>';
    }

    // 2. Quantidade por profissional
    $prof_data = $wpdb->get_results("
        SELECT p.nome, COUNT(*) as total
        FROM $t_agendamentos a
        LEFT JOIN $t_profissionais p ON a.profissional_id = p.id
        $where
        GROUP BY a.profissional_id
    ");

    echo '<h2>Agendamentos por Profissional</h2>';
    if ($prof_data) {
        echo '<ul>';
        foreach ($prof_data as $linha) {
            echo '<li><strong>' . esc_html($linha->nome) . ':</strong> ' . esc_html($linha->total) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Nenhum dado disponível.</p>';
    }

    // 3. Quantidade por serviço
    $serv_data = $wpdb->get_results("
        SELECT s.nome, COUNT(*) as total
        FROM $t_agendamentos a
        LEFT JOIN $t_servicos s ON a.servico_id = s.id
        $where
        GROUP BY a.servico_id
    ");

    echo '<h2>Agendamentos por Serviço</h2>';
    if ($serv_data) {
        echo '<ul>';
        foreach ($serv_data as $linha) {
            echo '<li><strong>' . esc_html($linha->nome) . ':</strong> ' . esc_html($linha->total) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Nenhum dado disponível.</p>';
    }

    echo '</div>';
}
