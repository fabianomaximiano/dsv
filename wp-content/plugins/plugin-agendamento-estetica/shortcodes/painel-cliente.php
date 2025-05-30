<?php
function agend_shortcode_painel_cliente() {
    if (!is_user_logged_in()) {
        return '<p>Faça login para acessar seu painel.</p>';
    }

    $user = wp_get_current_user();
    return "<h2>Olá, {$user->display_name}</h2><p>Seus agendamentos aparecerão aqui em breve.</p>";
}
add_shortcode('painel_cliente', 'agend_shortcode_painel_cliente');
