<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Retorna horários disponíveis via AJAX
 */
function agend_obter_horarios_disponiveis() {
    $profissional_id = intval($_POST['profissional_id'] ?? 0);
    $data = sanitize_text_field($_POST['data'] ?? '');
    $duracao = intval($_POST['duracao'] ?? 0); // em minutos

    if (!$profissional_id || !$data || !$duracao) {
        wp_send_json([]);
    }

    // Horário de funcionamento (poderia vir do banco futuramente)
    $inicio_expediente = '08:00';
    $fim_expediente = '18:00';
    $intervalo = 15; // minutos

    // Horários ocupados já agendados
    global $wpdb;
    $tabela = $wpdb->prefix . 'agendamentos'; // Ajuste para seu nome real

    $ocupados = $wpdb->get_results(
        $wpdb->prepare("
            SELECT horario, duracao FROM $tabela
            WHERE profissional_id = %d AND data_agendamento = %s
        ", $profissional_id, $data),
        ARRAY_A
    );

    // Monta lista de horários ocupados com início e fim
    $intervalos_ocupados = [];
    foreach ($ocupados as $item) {
        $ini = strtotime($item['horario']);
        $fim = $ini + ($item['duracao'] * 60);
        $intervalos_ocupados[] = [$ini, $fim];
    }

    // Gera todos os horários possíveis respeitando a duração solicitada
    $horarios_disponiveis = [];
    $inicio = strtotime($data . ' ' . $inicio_expediente);
    $fim = strtotime($data . ' ' . $fim_expediente);
    $bloco = $duracao * 60;

    for ($h = $inicio; $h + $bloco <= $fim; $h += $intervalo * 60) {
        $bloco_ini = $h;
        $bloco_fim = $h + $bloco;

        $conflito = false;
        foreach ($intervalos_ocupados as [$ini, $fim]) {
            if (($bloco_ini < $fim) && ($bloco_fim > $ini)) {
                $conflito = true;
                break;
            }
        }

        if (!$conflito) {
            $horarios_disponiveis[] = date('H:i', $bloco_ini);
        }
    }

    wp_send_json($horarios_disponiveis);
}

add_action('wp_ajax_agend_obter_horarios_disponiveis', 'agend_obter_horarios_disponiveis');
add_action('wp_ajax_nopriv_agend_obter_horarios_disponiveis', 'agend_obter_horarios_disponiveis');
