<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Adiciona menus ao admin
add_action('admin_menu', 'agend_registrar_menus');

function agend_registrar_menus() {
    // Menu principal
    add_menu_page(
        'Agendamento Estética',
        'Agendamento',
        'manage_options',
        'agend-dashboard',
        'agend_dashboard_page',
        'dashicons-calendar-alt',
        25
    );

    // Submenus
    add_submenu_page(
        'agend-dashboard',
        'Clientes',
        'Clientes',
        'manage_options',
        'agend-clientes',
        'agend_admin_clientes_page'
    );

    add_submenu_page(
        'agend-dashboard',
        'Profissionais',
        'Profissionais',
        'manage_options',
        'agend-profissionais',
        'agend_admin_profissionais_page'
    );

    add_submenu_page(
        'agend-dashboard',
        'Serviços',
        'Serviços',
        'manage_options',
        'agend-servicos',
        'agend_admin_servicos_page'
    );

    add_submenu_page(
        'agend-dashboard',
        'Agendamentos',
        'Agendamentos',
        'manage_options',
        'agend-agendamentos',
        'agend_admin_agendamentos_page'
    );

    add_submenu_page(
        'agend-dashboard',
        'Relatórios',
        'Relatórios',
        'manage_options',
        'agend-relatorios',
        'agend_admin_relatorios_page'
    );
}

// Inclui os arquivos das páginas do admin
require_once AGEND_PLUGIN_PATH . 'includes/admin/clientes.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/profissionais.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/servicos.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/agendamentos.php';
require_once AGEND_PLUGIN_PATH . 'includes/admin/relatorios.php';

// Página principal do painel
function agend_dashboard_page() {
    echo '<div class="wrap"><h1>Dashboard do Sistema de Agendamento</h1><p>Bem-vindo ao painel administrativo.</p></div>';
}
