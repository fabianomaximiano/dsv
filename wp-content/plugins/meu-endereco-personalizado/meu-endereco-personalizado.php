<?php
/**
 * Plugin Name: Meu Endereço Personalizado
 * Description: Plugin para gerenciar endereços com faixas de CEP personalizadas no WooCommerce.
 * Version: 1.0.0
 * Author: Seu Nome
 * Text Domain: meu-endereco-personalizado
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Evita acesso direto
}

// Define caminhos úteis
define('MEP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MEP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Carrega traduções
add_action('init', function () {
    load_plugin_textdomain(
        'meu-endereco-personalizado',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
});

// Inclui arquivos necessários
require_once MEP_PLUGIN_DIR . 'includes/class-mep-helper.php';
require_once MEP_PLUGIN_DIR . 'includes/class-mep-endereco-manager.php';
require_once MEP_PLUGIN_DIR . 'includes/class-mep-bairro-validator.php';

// Inicializa validações no frontend
add_action('plugins_loaded', function () {
    if (class_exists('MEP_Bairro_Validator')) {
        MEP_Bairro_Validator::init();
    }
});

// Scripts e estilos do admin
function mep_enqueue_admin_assets($hook) {
    if ($hook !== 'toplevel_page_mep_enderecos') {
        return;
    }

    wp_enqueue_style('mep-admin-style', MEP_PLUGIN_URL . 'assets/css/admin.css');
    wp_enqueue_script('mep-admin-script', MEP_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], null, true);
    wp_localize_script('mep-admin-script', 'mepAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mep_admin_nonce')
    ]);
}
add_action('admin_enqueue_scripts', 'mep_enqueue_admin_assets');

// Cria menu no admin
function mep_admin_menu() {
    add_menu_page(
        __('Endereços Personalizados', 'meu-endereco-personalizado'),
        __('End. Personalizados', 'meu-endereco-personalizado'),
        'manage_options',
        'mep_enderecos',
        'mep_render_settings_page',
        'dashicons-location-alt',
        56
    );
}
add_action('admin_menu', 'mep_admin_menu');

// Callback da tela de configurações
function mep_render_settings_page() {
    include MEP_PLUGIN_DIR . 'admin/settings-page.php';
}
