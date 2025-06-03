<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cria páginas automaticamente no momento da ativação do plugin
 */
function agend_criar_paginas() {
    $paginas = [
        'cadastro-cliente'      => 'Cadastro de Cliente',
        'painel-cliente'        => 'Painel do Cliente',
        'painel-profissional'   => 'Painel do Profissional',
        'pagina-agendamento'    => 'Agendamento',
    ];

    foreach ($paginas as $slug => $titulo) {
        // Verifica se a página já existe
        $pagina = get_page_by_path($slug);

        if (!$pagina) {
            $nova_pagina = [
                'post_title'   => $titulo,
                'post_name'    => $slug,
                'post_content' => '<!-- Conteúdo gerado dinamicamente pelo plugin -->',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ];

            $id = wp_insert_post($nova_pagina);

            if ($id && !is_wp_error($id)) {
                update_option('agend_pagina_' . $slug, $id);
            }
        }
    }
}

/**
 * Remove as páginas criadas automaticamente na desativação
 */
function agend_remover_paginas() {
    $slugs = ['cadastro-cliente', 'painel-cliente', 'painel-profissional', 'pagina-agendamento'];

    foreach ($slugs as $slug) {
        $pagina_id = get_option('agend_pagina_' . $slug);
        if ($pagina_id) {
            wp_delete_post($pagina_id, true);
            delete_option('agend_pagina_' . $slug);
        }
    }
}
