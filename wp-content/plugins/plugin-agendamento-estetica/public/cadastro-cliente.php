<?php
if (!defined('ABSPATH')) exit;

function agend_formulario_cadastro_cliente() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agend_cadastrar_cliente'])) {
        $nome     = sanitize_text_field($_POST['nome'] ?? '');
        $email    = sanitize_email($_POST['email'] ?? '');
        $telefone = sanitize_text_field($_POST['telefone'] ?? '');
        $senha    = $_POST['senha'] ?? '';

        // Verificações básicas
        if (empty($nome) || empty($email) || empty($telefone) || empty($senha)) {
            echo '<div class="notice notice-error"><p>Preencha todos os campos obrigatórios.</p></div>';
        } elseif (!is_email($email)) {
            echo '<div class="notice notice-error"><p>Email inválido.</p></div>';
        } elseif (email_exists($email)) {
            echo '<div class="notice notice-error"><p>E-mail já cadastrado.</p></div>';
        } else {
            // Criação do usuário
            $user_id = wp_insert_user([
                'user_login'    => $email,
                'user_email'    => $email,
                'user_pass'     => $senha,
                'display_name'  => $nome,
                'role'          => 'subscriber'
            ]);

            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, 'telefone', $telefone);

                // Autentica e redireciona
                wp_set_auth_cookie($user_id);
                echo '<div class="notice notice-success"><p>Cadastro realizado com sucesso!</p></div>';
                wp_redirect(home_url('/painel-cliente'));
                exit;
            } else {
                echo '<div class="notice notice-error"><p>Erro ao cadastrar: ' . esc_html($user_id->get_error_message()) . '</p></div>';
            }
        }
    }

    ?>

    <div class="wrap">
        <h2>Cadastro de Cliente</h2>
        <form method="post">
            <p><label>Nome completo:<br><input type="text" name="nome" required></label></p>
            <p><label>E-mail:<br><input type="email" name="email" required></label></p>
            <p><label>Telefone:<br><input type="text" name="telefone" required pattern="[0-9]{10,11}" title="Digite apenas números com DDD" placeholder="Ex: 11999999999"></label></p>
            <p><label>Senha:<br><input type="password" name="senha" required></label></p>
            <p><input type="submit" name="agend_cadastrar_cliente" class="button button-primary" value="Cadastrar"></p>
        </form>
    </div>

    <?php
}

// ✅ Só exibe se estiver na página 'cadastro-cliente'
add_action('template_redirect', function () {
    if (is_page('cadastro-cliente')) {
        add_action('wp_head', 'agend_formulario_cadastro_cliente');
    }
});
