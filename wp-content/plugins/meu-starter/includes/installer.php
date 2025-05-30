<?php

function meu_starter_get_active_json() {
    $option = get_option('meu_starter_active_json', 'plugins-basico.json');
    $path = MEU_STARTER_DIR . 'data/' . sanitize_file_name($option);
    if (!file_exists($path)) {
        $path = MEU_STARTER_DIR . 'data/plugins-basico.json';
    }
    return $path;
}


function meu_starter_install_plugins_from_list() {
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    $file = meu_starter_get_active_json();
    if (!file_exists($file)) return;

    $slugs = json_decode(file_get_contents($file), true);

    foreach ($slugs as $slug) {
        // Verifica se o plugin já está instalado (mesmo desativado)
        $all_plugins = get_plugins();
        $plugin_file = '';

        // Procura o arquivo principal do plugin pelo slug
        foreach ($all_plugins as $path => $details) {
            if (strpos($path, $slug) === 0) {
                $plugin_file = $path;
                break;
            }
        }

        if ($plugin_file) {
            // Plugin já está instalado
            if (!is_plugin_active($plugin_file)) {
                activate_plugin($plugin_file);
            }
            continue; // pula pra próximo plugin da lista
        }

        // Plugin não instalado, instala agora
        $api = plugins_api('plugin_information', ['slug' => $slug]);
        if (is_wp_error($api)) continue;

        $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
        $installed = $upgrader->install($api->download_link);

        // Ativa se a instalação foi ok
        if ($installed) {
            activate_plugin("$slug/$slug.php");
        }
    }
}

