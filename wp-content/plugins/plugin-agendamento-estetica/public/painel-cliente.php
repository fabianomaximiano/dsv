<?php
if (!defined('ABSPATH')) exit;

function agend_painel_cliente() {
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/cadastro-cliente'));
        exit;
    }

    $user = wp_get_current_user();
    $telefone = get_user_meta($user->ID, 'telefone', true);

    echo '<div class="wrap">';
    echo '<h2>Olá, ' . esc_html($user->display_name) . '</h2>';
    echo '<p><strong>Email:</strong> ' . esc_html($user->user_email) . '</p>';
    echo '<p><strong>Telefone:</strong> ' . esc_html($telefone) . '</p>';
    echo '<p><a href="' . esc_url(wp_logout_url(home_url('/cadastro-cliente'))) . '">Sair</a></p>';
    echo '<hr>';
    echo '<p><a class="button button-primary" href="' . esc_url(home_url('/pagina-agendamento')) . '">Agendar Serviço</a></p>';
    echo '</div>';
}
agend_painel_cliente();
