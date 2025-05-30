<?php
// Impede acesso direto
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_agend_horarios_disponiveis', 'agend_ajax_horarios_disponiveis');
add_action('wp_ajax_nopriv_agend_horarios_disponiveis', 'agend_ajax_horarios_disponiveis');

function agend_ajax_horarios_disponiveis() {
    global $wpdb;

    $profissional_id = intval($_POST['profissional']);
    $data = sanitize_text_field($_POST['data']);
    $servicos_ids = json_decode(stripslashes($_POST['servicos']), true);

    if (!$profissional_id || !$data || empty($servicos_ids)) {
        wp_send_json([]);
    }

    // Obtém duração total dos serviços
    $placeholders = implode(',', array_fill(0, count($servicos_ids), '%d'));
    $sql = "SELECT SUM(duracao) FROM {$wpdb->prefix}agend_servicos WHERE id IN ($placeholders)";
    $duracao_total = (int) $wpdb->get_var($wpdb->prepare($sql, $servicos_ids));

    if ($duracao_total <= 0) {
        wp_send_json([]);
    }

    // Horários de funcionamento (pode ser ajustado para ser dinâmico por profissional)
    $inicio = strtotime("$data 09:00");
    $fim    = strtotime("$data 18:00");
    $intervalo = 15 * 60; // 15 minutos
    $duracao_segundos = $duracao_total * 60;

    $horarios_possiveis = [];
    for ($h = $inicio; $h + $duracao_segundos <= $fim; $h += $intervalo) {
        $horarios_possiveis[] = date('H:i', $h);
    }

    // Busca agendamentos existentes do profissional nesta data
    $agendamentos = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT horario, duracao FROM {$wpdb->prefix}agend_agendamentos WHERE profissional_id = %d AND data = %s",
            $profissional_id,
            $data
        )
    );

    // Marca os horários ocupados
    $horarios_ocupados = [];
    foreach ($agendamentos as $a) {
        $inicio_agend = strtotime("$data {$a->horario}");
        $fim_agend = $inicio_agend + ($a->duracao * 60);
        $horarios_ocupados[] = [$inicio_agend, $fim_agend];
    }

    // Filtra horários disponíveis
    $disponiveis = [];
    foreach ($horarios_possiveis as $hora) {
        $inicio_tentativa = strtotime("$data $hora");
        $fim_tentativa = $inicio_tentativa + $duracao_segundos;

        $conflito = false;
        foreach ($horarios_ocupados as [$ini, $fim]) {
            if (
                ($inicio_tentativa >= $ini && $inicio_tentativa < $fim) ||
                ($fim_tentativa > $ini && $fim_tentativa <= $fim) ||
                ($inicio_tentativa <= $ini && $fim_tentativa >= $fim)
            ) {
                $conflito = true;
                break;
            }
        }

        if (!$conflito) {
            $disponiveis[] = $hora;
        }
    }

    wp_send_json($disponiveis);
}
