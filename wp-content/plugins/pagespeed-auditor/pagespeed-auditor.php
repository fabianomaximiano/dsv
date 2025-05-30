<?php
/*
Plugin Name: PageSpeed Auditor
Description: Gera relatórios automáticos do PageSpeed, armazena leads e envia PDF personalizado por e-mail.
Version: 1.1
Author: Seu Nome
*/

if (!defined('ABSPATH')) exit;

// Ativação do plugin
register_activation_hook(__FILE__, 'psa_ativar_plugin');
function psa_ativar_plugin() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'pagespeed_leads';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $tabela (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nome varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        url varchar(255) NOT NULL,
        data datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $pagina_existente = get_page_by_path('relatorio-de-site');
    if (!$pagina_existente) {
        wp_insert_post([
            'post_title'   => 'Relatório de Site',
            'post_name'    => 'relatorio-de-site',
            'post_content' => '<div id="pagespeed-form"></div>',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);
    }
}

// Admin Menu
add_action('admin_menu', 'psa_menu_configuracoes');
function psa_menu_configuracoes() {
    add_options_page('Configurações PageSpeed Auditor', 'PageSpeed Auditor', 'manage_options', 'psa-config', 'psa_pagina_configuracoes');
}

function psa_pagina_configuracoes() {
    ?>
    <div class="wrap">
        <h1>Configurações do PageSpeed Auditor</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('psa_config_group');
            do_settings_sections('psa-config');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'psa_registrar_configuracoes');
function psa_registrar_configuracoes() {
    register_setting('psa_config_group', 'psa_google_api_key');

    add_settings_section('psa_secao', 'Chave da API do Google PageSpeed', null, 'psa-config');

    add_settings_field('psa_google_api_key', 'Chave da API', 'psa_input_api_key', 'psa-config', 'psa_secao');
}

function psa_input_api_key() {
    $valor = esc_attr(get_option('psa_google_api_key'));
    echo "<input type='text' name='psa_google_api_key' value='$valor' class='regular-text'>";
}

// Carregar Bootstrap e JS
add_action('wp_enqueue_scripts', 'psa_carregar_estilos_scripts');
function psa_carregar_estilos_scripts() {
    if (is_page('relatorio-de-site')) {
        wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', ['jquery'], null, true);
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery', 'popper-js'], null, true);

        wp_enqueue_script('psa-js', plugin_dir_url(__FILE__) . 'assets/js/form.js', ['jquery'], null, true);
        wp_localize_script('psa-js', 'psa_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
    }
}

// Injetar formulário
add_filter('the_content', 'psa_injetar_formulario');
function psa_injetar_formulario($content) {
    if (is_page('relatorio-de-site') && strpos($content, 'pagespeed-form') !== false) {
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/form-template.php';
        $form_html = ob_get_clean();
        return str_replace('<div id="pagespeed-form"></div>', $form_html, $content);
    }
    return $content;
}

// Processa formulário
add_action('wp_ajax_psa_enviar_formulario', 'psa_enviar_formulario');
add_action('wp_ajax_nopriv_psa_enviar_formulario', 'psa_enviar_formulario');
function psa_enviar_formulario() {
    global $wpdb;

    $nome  = sanitize_text_field($_POST['nome']);
    $email = sanitize_email($_POST['email']);
    $url   = esc_url_raw($_POST['url']);

    $wpdb->insert(
        $wpdb->prefix . 'pagespeed_leads',
        ['nome' => $nome, 'email' => $email, 'url' => $url],
        ['%s', '%s', '%s']
    );

    $relatorio = psa_consultar_pagespeed($url);

    if (!$relatorio) {
        wp_send_json_error('Erro ao consultar o PageSpeed.');
    }

    $pdf_path = psa_gerar_pdf_relatorio($nome, $url, $relatorio);

    if ($pdf_path) {
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        wp_mail($email, 'Seu Relatório de Desempenho de Site', 'Segue em anexo o seu relatório.', $headers, [$pdf_path]);

        wp_mail(get_option('admin_email'), 'Novo lead do PageSpeed Auditor', "Lead: $nome\nEmail: $email\nURL: $url", $headers, [$pdf_path]);
    }

    wp_send_json_success('Formulário enviado com sucesso. Relatório gerado e enviado.');
}

// Consulta API PageSpeed
function psa_consultar_pagespeed($url) {
    $api_key = get_option('psa_google_api_key');
    if (!$api_key) return false;

    $endpoint = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" . urlencode($url) . "&key=$api_key&strategy=mobile";
    $response = wp_remote_get($endpoint);

    if (is_wp_error($response)) return false;

    $dados = json_decode(wp_remote_retrieve_body($response), true);

    if (!isset($dados['lighthouseResult'])) return false;

    return $dados['lighthouseResult'];
}

// Gera PDF com TCPDF
function psa_gerar_pdf_relatorio($nome, $url, $dados) {
    if (!class_exists('TCPDF')) {
        require_once plugin_dir_path(__FILE__) . 'libs/tcpdf/tcpdf.php';
    }

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $pontuacao = $dados['categories']['performance']['score'] * 100;
    $mensagem = "Olá $nome!\n\nSeu site $url teve uma pontuação de $pontuacao no teste do Google PageSpeed. Isso significa que:";

    if ($pontuacao >= 90) {
        $mensagem .= "\n✔️ Está muito rápido! Ótimo trabalho!";
    } elseif ($pontuacao >= 50) {
        $mensagem .= "\n⚠️ Está razoável, mas pode melhorar.";
    } else {
        $mensagem .= "\n❌ Está lento e precisa de melhorias!";
    }

    $mensagem .= "\n\nPodemos conversar e melhorar isso juntos. 😉";

    $pdf->Write(0, $mensagem);

    $upload_dir = wp_upload_dir();
    $pdf_path = $upload_dir['basedir'] . "/relatorio-" . time() . ".pdf";
    $pdf->Output($pdf_path, 'F');

    return $pdf_path;
}
