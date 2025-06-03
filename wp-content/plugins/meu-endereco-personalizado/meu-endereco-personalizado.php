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
require_once MEP_PLUGIN_DIR . 'admin/class-mep-admin.php';

// Inicializa funcionalidades principais
add_action('plugins_loaded', function () {
    if (class_exists('MEP_Bairro_Validator')) {
        MEP_Bairro_Validator::init();
    }

    if (class_exists('MEP_Admin')) {
        new MEP_Admin(); // Responsável por menu e assets no admin
    }
});

// Enfileira assets do frontend
function mep_enqueue_public_assets() {
    wp_enqueue_script(
        'mep-frontend-script',
        MEP_PLUGIN_URL . 'assets/js/autofill-endereco.js',
        ['jquery'],
        null,
        true
    );

    wp_localize_script('mep-frontend-script', 'mepFrontend', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mep_frontend_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'mep_enqueue_public_assets');

// Cria a tabela ao ativar o plugin
function mep_criar_tabela_faixas_entrega() {
    global $wpdb;

    $tabela = $wpdb->prefix . 'mep_faixas_entrega';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $tabela (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        bairro varchar(191) NOT NULL,
        cep_inicial varchar(8) NOT NULL,
        cep_final varchar(8) NOT NULL,
        frete_gratis tinyint(1) DEFAULT 0 NOT NULL,
        valor_frete decimal(10,2) DEFAULT 0.00 NOT NULL,
        mensagem varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'mep_criar_tabela_faixas_entrega');
