<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Página de listagem de agendamentos no admin
 */
function agend_agendamentos_pagina() {
    global $wpdb;
    $tabela_agendamentos = $wpdb->prefix . 'agend_agendamentos';
    $tabela_clientes     = $wpdb->prefix . 'agend_clientes';
    $tabela_profissionais = $wpdb->prefix . 'agend_profissionais';
    $tabela_servicos     = $wpdb->prefix . 'agend_servicos';

    // Consulta com joins para trazer nomes de cliente, profissional e serviço
    $agendamentos = $wpdb->get_results("
        SELECT a.id, a.data, a.horario, a.status,
               c.nome AS cliente_nome,
               p.nome AS profissional_nome,
               s.nome AS servico_nome
        FROM $tabela_agendamentos a
        LEFT JOIN $tabela_clientes c ON a.cliente_id = c.id
        LEFT JOIN $tabela_profissionais p ON a.profissional_id = p.id
        LEFT JOIN $tabela_servicos s ON a.servico_id = s.id
        ORDER BY a.data DESC, a.horario ASC
    ");

    echo '<div class="wrap">';
    echo '<h1>Agendamentos</h1>';

    if ($agendamentos) {
        echo '<table class="widefat fixed striped">';
        echo '<thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Cliente</th>
                    <th>Profissional</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
              </thead>';
        echo '<tbody>';

        foreach ($agendamentos as $ag) {
            echo '<tr>';
            echo '<td>' . esc_html($ag->id) . '</td>';
            echo '<td>' . esc_html(date_i18n('d/m/Y', strtotime($ag->data))) . '</td>';
            echo '<td>' . esc_html($ag->horario) . '</td>';
            echo '<td>' . esc_html($ag->cliente_nome) . '</td>';
            echo '<td>' . esc_html($ag->profissional_nome) . '</td>';
            echo '<td>' . esc_html($ag->servico_nome) . '</td>';
            echo '<td>' . esc_html(ucfirst($ag->status)) . '</td>';
            echo '<td>
                    <a href="#" class="button button-small">Editar</a>
                    <a href="#" class="button button-small">Excluir</a>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhum agendamento encontrado.</p>';
    }

    echo '</div>';
}
