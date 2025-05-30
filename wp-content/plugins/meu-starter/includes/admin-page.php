<?php

function meu_starter_admin_menu() {
    add_menu_page(
        'Starter Plugins',
        'Starter Plugins',
        'manage_options',
        'meu-starter',
        'meu_starter_admin_page',
        'dashicons-admin-plugins'
    );
}
add_action('admin_menu', 'meu_starter_admin_menu');

function meu_starter_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Acesso negado');
    }

    $notice = '';

    // Upload do JSON
    if (isset($_POST['upload_json']) && check_admin_referer('meu_starter_upload_json', 'meu_starter_nonce')) {
        if (!empty($_FILES['json_file']['tmp_name'])) {
            $allowed = ['plugins-basico.json', 'plugins-loja.json'];
            $dest = sanitize_file_name($_POST['destino'] ?? '');
            if (!in_array($dest, $allowed)) {
                $notice = '<div class="notice notice-error"><p>Destino inválido.</p></div>';
            } else {
                $json = file_get_contents($_FILES['json_file']['tmp_name']);
                if (json_decode($json)) {
                    $upload_path = MEU_STARTER_DIR . 'data/' . $dest;
                    file_put_contents($upload_path, $json);
                    $notice = '<div class="notice notice-success"><p>Arquivo atualizado!</p></div>';
                } else {
                    $notice = '<div class="notice notice-error"><p>JSON inválido.</p></div>';
                }
            }
        } else {
            $notice = '<div class="notice notice-error"><p>Nenhum arquivo enviado.</p></div>';
        }
    }

    // Alterar JSON ativo
    if (isset($_POST['select_json']) && check_admin_referer('meu_starter_select_json', 'meu_starter_nonce_select')) {
        $allowed = ['plugins-basico.json', 'plugins-loja.json'];
        $selected = sanitize_file_name($_POST['plugin_json'] ?? '');
        if (in_array($selected, $allowed)) {
            update_option('meu_starter_active_json', $selected);
            $notice = '<div class="notice notice-success"><p>Lista ativa atualizada!</p></div>';
        } else {
            $notice = '<div class="notice notice-error"><p>Seleção inválida.</p></div>';
        }
    }

    // Instalar plugins
    if (isset($_POST['install_plugins']) && check_admin_referer('meu_starter_install_plugins', 'meu_starter_nonce_install')) {
        meu_starter_install_plugins_from_list();
        $notice = '<div class="notice notice-success"><p>Plugins instalados e ativados!</p></div>';
    }

    $json_files = ['plugins-basico.json', 'plugins-loja.json'];
    $active_json = get_option('meu_starter_active_json', 'plugins-basico.json');

    ?>
    <div class="wrap">
        <h1>Meu Starter Dinâmico</h1>
        <?php echo $notice; ?>
        <h2>Selecionar lista de plugins ativa</h2>
        <form method="post">
            <?php wp_nonce_field('meu_starter_select_json', 'meu_starter_nonce_select'); ?>
            <select name="plugin_json">
                <?php foreach ($json_files as $json_file): ?>
                    <option value="<?php echo esc_attr($json_file); ?>" <?php selected($active_json, $json_file); ?>>
                        <?php echo esc_html($json_file); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="select_json" class="button button-primary">Atualizar lista ativa</button>
        </form>

        <h2>Upload de arquivo JSON para atualizar lista</h2>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('meu_starter_upload_json', 'meu_starter_nonce'); ?>
            <input type="file" name="json_file" accept=".json" required />
            <select name="destino">
                <option value="plugins-basico.json">plugins-basico.json</option>
                <option value="plugins-loja.json">plugins-loja.json</option>
            </select>
            <button type="submit" name="upload_json" class="button button-secondary">Enviar JSON</button>
        </form>

        <h2>Instalar plugins da lista ativa</h2>
        <form method="post">
            <?php wp_nonce_field('meu_starter_install_plugins', 'meu_starter_nonce_install'); ?>
            <button type="submit" name="install_plugins" class="button button-primary">Instalar Plugins</button>
        </form>

        <h2>Lista de plugins da lista ativa (<?php echo esc_html($active_json); ?>)</h2>
        <pre style="background:#f4f4f4; padding:10px; max-width:600px; overflow:auto;">
<?php
    $file_path = MEU_STARTER_DIR . 'data/' . $active_json;
    if (file_exists($file_path)) {
        echo esc_html(file_get_contents($file_path));
    } else {
        echo "Arquivo não encontrado.";
    }
?>
        </pre>
    </div>
    <?php
}
