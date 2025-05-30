<?php
// Segurança: impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Roteia slugs de páginas para arquivos PHP no diretório shortcodes/
 */
function agend_interceptar_paginas() {
    if (is_page()) {
        global $post;

        $slugs_permitidos = [
            'cadastro-cliente',
            'painel-cliente',
            'cadastro-profissional',
            'dashboard-cliente',
            'dashboard-profissional',
            'dashboard-salao',
            'cadastro-servico',
            'agendamento',
        ];

        $slug = $post->post_name;

        if (in_array($slug, $slugs_permitidos)) {
            $arquivo = plugin_dir_path(__FILE__) . '../shortcodes/' . $slug . '.php';

            if (file_exists($arquivo)) {
                include $arquivo;
                exit;
            }
        }
    }
}
add_action('template_redirect', 'agend_interceptar_paginas');
