<?php
if (!defined('ABSPATH')) exit;

function agend_agenda_profissional_page() {
    global $wpdb;
    $tabela_agendamentos = $wpdb->prefix . 'agend_agendamentos';
    $tabela_servicos = $wpdb->prefix . 'agend_servicos';
    $profissional_id = get_current_user_id();

    echo '<div class="wrap"><h1>Minha Agenda</h1>';

    $agendamentos = $wpdb->get_results($wpdb->prepare("
        SELECT * FROM $tabela_agendamentos
        WHERE profissional_id = %d
        ORDER BY inicio ASC
    ", $profissional_id));

    if ($agendamentos) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Cliente</th><th>Serviços</th><th>Data</th><th>Horário</th><th>Status</th></tr></thead><tbody>';
        foreach ($agendamentos as $ag) {
            $cliente = get_userdata($ag->cliente_id);
            $servicos_nomes = [];

            if (!empty($ag->servicos_ids)) {
                $ids = explode(',', $ag->servicos_ids);
                foreach ($ids as $id) {
                    $id = intval($id);
                    if ($id > 0) {
                        $s = $wpdb->get_row($wpdb->prepare("SELECT nome FROM $tabela_servicos WHERE id = %d", $id));
                        if ($s) $servicos_nomes[] = $s->nome;
                    }
                }
            }

            echo '<tr>';
            echo '<td>' . esc_html($cliente ? $cliente->display_name : 'Desconhecido') . '</td>';
            echo '<td>' . esc_html(implode(', ', $servicos_nomes)) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($ag->inicio)) . '</td>';
            echo '<td>' . date('H:i', strtotime($ag->inicio)) . ' - ' . date('H:i', strtotime($ag->termino)) . '</td>';
            echo '<td>' . esc_html(ucfirst($ag->status)) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>Sem agendamentos registrados para este profissional.</p>';
    }

    echo '</div>';
}
