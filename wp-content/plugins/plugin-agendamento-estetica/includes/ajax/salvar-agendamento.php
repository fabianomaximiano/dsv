<?php
// Impede acesso direto
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_agend_salvar_agendamento', 'agend_ajax_salvar_agendamento');
add_action('wp_ajax_nopriv_agend_salvar_agendamento', 'agend_ajax_salvar_agendamento');

function agend_ajax_salvar_agendamento() {
    global $wpdb;

    $cliente_id = intval($_POST['cliente_id']);
    $profissional_id = intval($_POST['profissional_id']);
    $data = sanitize_text_field($_POST['data']);
    $hora = sanitize_text_field($_POST['hora']);
    $servicos_ids = json_decode(stripslashes($_POST['servicos']), true);

    if (!$cliente_id || !$profissional_id || !$data || !$hora || empty($servicos_ids)) {
        wp_send_json_error(['mensagem' => 'Dados incompletos.']);
    }

    // Calcula duração total dos serviços
    $placeholders = implode(',', array_fill(0, count($servicos_ids), '%d'));
    $sql = "SELECT SUM(duracao) FROM {$wpdb->prefix}agend_servicos WHERE id IN ($placeholders)";
    $duracao_total = (int) $wpdb->get_var($wpdb->prepare($sql, $servicos_ids));
    if ($duracao_total <= 0) {
        wp_send_json_error(['mensagem' => 'Serviços inválidos.']);
    }

    $inicio_novo = strtotime("$data $hora");
    $fim_novo = $inicio_novo + ($duracao_total * 60);

    // Verifica conflitos com agendamentos existentes
    $agendamentos = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT horario, duracao FROM {$wpdb->prefix}agend_agendamentos WHERE profissional_id = %d AND data = %s",
            $profissional_id,
            $data
        )
    );

    foreach ($agendamentos as $ag) {
        $inicio_existente = strtotime("$data {$ag->horario}");
        $fim_existente = $inicio_existente + ($ag->duracao * 60);

        if (
            ($inicio_novo >= $inicio_existente && $inicio_novo < $fim_existente) ||
            ($fim_novo > $inicio_existente && $fim_novo <= $fim_existente) ||
            ($inicio_novo <= $inicio_existente && $fim_novo >= $fim_existente)
        ) {
            wp_send_json_error(['mensagem' => 'Conflito com outro agendamento.']);
        }
    }

    // Salva agendamento
    $result = $wpdb->insert(
        "{$wpdb->prefix}agend_agendamentos",
        [
            'cliente_id'      => $cliente_id,
            'profissional_id' => $profissional_id,
            'data'            => $data,
            'horario'         => $hora,
            'duracao'         => $duracao_total,
            'status'          => 'pendente',
            'criado_em'       => current_time('mysql')
        ],
        ['%d', '%d', '%s', '%s', '%d', '%s', '%s']
    );

    if ($result) {
        wp_send_json_success(['mensagem' => 'Agendamento realizado com sucesso.']);
    } else {
        wp_send_json_error(['mensagem' => 'Erro ao salvar o agendamento.']);
    }
}
