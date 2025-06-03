<?php
if (!defined('ABSPATH')) {
    exit;
}

class MEP_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'adicionar_menu']);
        add_action('admin_enqueue_scripts', [$this, 'carregar_assets']);
    }

    public function adicionar_menu() {
        add_menu_page(
            'Endereços Personalizados',
            'Endereço Personalizado',
            'manage_options',
            'meu-endereco-personalizado',
            [$this, 'renderizar_pagina_configuracoes'],
            'dashicons-location-alt',
            56
        );
    }

    public function renderizar_pagina_configuracoes() {
        include plugin_dir_path(__FILE__) . 'settings-page.php';
    }

    public function carregar_assets($hook) {
        // Verifica se estamos na página correta
        if (strpos($hook, 'meu-endereco-personalizado') === false) {
            return;
        }

        $versao = filemtime(plugin_dir_path(__FILE__) . '/../assets/js/admin.js');

        // CSS
        wp_enqueue_style(
            'mep-admin-style',
            plugin_dir_url(__FILE__) . '../assets/css/admin.css',
            [],
            $versao
        );

        // JS
        wp_enqueue_script(
            'mep-admin-script',
            plugin_dir_url(__FILE__) . '../assets/js/admin.js',
            ['jquery'],
            $versao,
            true
        );

        // Localiza AJAX nonce
        wp_localize_script('mep-admin-script', 'mep_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('mep_admin_nonce')
        ]);

        wp_enqueue_script(
            'mep-admin-js',
            plugin_dir_url(__FILE__) . '../admin/js/autofill-endereco.js',
            ['jquery'],
            '1.0.0',
            true
        );

    }
}
