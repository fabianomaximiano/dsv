<?php
/**
 * Plugin Name: Sistema de Agendamento Estética
 * Description: Plugin de agendamento para salões de beleza, cabeleireiros, podólogas e similares.
 * Version: 1.0.0
 * Author: Seu Nome
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constantes
define('AGEND_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AGEND_PLUGIN_URL', plugin_dir_url(__FILE__));

// Includes
require_once AGEND_PLUGIN_PATH . 'includes/ativador.php';
require_once AGEND_PLUGIN_PATH . 'includes/install.php';
require_once AGEND_PLUGIN_PATH . 'includes/pages.php';
require_once AGEND_PLUGIN_PATH . 'includes/functions.php';
require_once AGEND_PLUGIN_PATH . 'includes/menus.php';

// public
require_once AGEND_PLUGIN_PATH . 'public/cadastro-cliente.php';
// A linha abaixo foi comentada para evitar conflito de exibição dupla
// require_once AGEND_PLUGIN_PATH . 'public/painel-cliente.php';

// Shortcodes ainda são carregados para compatibilidade e reaproveitamento
require_once AGEND_PLUGIN_PATH . 'shortcodes/cadastro-cliente.php';
// require_once AGEND_PLUGIN_PATH . 'shortcodes/agendamento.php';
require_once AGEND_PLUGIN_PATH . 'shortcodes/cadastro-profissional.php';
require_once AGEND_PLUGIN_PATH . 'shortcodes/cadastro-servico.php';
require_once AGEND_PLUGIN_PATH . 'shortcodes/dashboard-cliente.php';
require_once AGEND_PLUGIN_PATH . 'shortcodes/dashboard-profissional.php';
require_once AGEND_PLUGIN_PATH . 'shortcodes/dashboard-salao.php';
require_once AGEND_PLUGIN_PATH . 'shortcodes/painel-cliente.php';

// Hooks de ativação/desativação
register_activation_hook(__FILE__, 'agend_plugin_ativar');
register_deactivation_hook(__FILE__, 'agend_plugin_desativar');

// Inicialização do plugin
add_action('plugins_loaded', 'agend_plugin_iniciar');

function agend_plugin_iniciar() {
    // Carregamentos adicionais podem ser colocados aqui
}

// Redireciona dinamicamente para os templates públicos criados automaticamente
add_filter('template_include', 'agend_incluir_template_publico');

function agend_incluir_template_publico($template) {
    if (is_page('cadastro-cliente')) {
        return AGEND_PLUGIN_PATH . 'templates-publicos/cadastro-cliente.php';
    }

    if (is_page('painel-cliente')) {
        return AGEND_PLUGIN_PATH . 'templates-publicos/painel-cliente.php';
    }

    return $template;
}
