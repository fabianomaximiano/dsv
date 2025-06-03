<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}


add_action('admin_menu', 'agend_menu_admin');

function agend_menu_admin() {
   // Menu principal
add_menu_page(
    'Agendamento',
    'Agendamento',
    'manage_options',
    'agendamentos',
    'agend_render_lista_agendamentos',
    'dashicons-calendar-alt',
    25
);

// Submenus corretos:
add_submenu_page(
    'agendamentos',
    'Clientes',
    'Clientes',
    'manage_options',
    'agend-clientes',
    'agend_render_clientes'
);

add_submenu_page(
    'agendamentos',
    'Profissionais',
    'Profissionais',
    'manage_options',
    'agend-profissionais',
    'agend_render_profissionais'
);

add_submenu_page(
    'agendamentos',
    'Serviços',
    'Serviços',
    'manage_options',
    'agend-servicos',
    'agend_render_servicos'
);


}


// Inclui os arquivos das páginas do admin
require_once AGEND_PLUGIN_PATH . 'includes/admin/clientes.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/profissionais.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/servicos.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/agendamentos.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/relatorios.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/agenda-profissional.php';

// Página principal do painel
function agend_dashboard_page() {
    echo '<div class="wrap"><h1>Dashboard do Sistema de Agendamento</h1><p>Bem-vindo ao painel administrativo.</p></div>';
}
