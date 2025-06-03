public function carregar_assets($hook) {
    if (strpos($hook, 'meu-endereco-personalizado') === false) {
        return;
    }

    // Caminhos base
    $plugin_url  = plugin_dir_url(__DIR__);  // __DIR__ = /admin
    $plugin_path = plugin_dir_path(__DIR__);

    $versao = filemtime($plugin_path . 'assets/js/admin.js');

    // CSS
    wp_enqueue_style(
        'mep-admin-style',
        $plugin_url . 'assets/css/admin.css',
        [],
        $versao
    );

    // JS principal do admin
    wp_enqueue_script(
        'mep-admin-script',
        $plugin_url . 'assets/js/admin.js',
        ['jquery'],
        $versao,
        true
    );

    // Variáveis JS seguras
    wp_localize_script('mep-admin-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
