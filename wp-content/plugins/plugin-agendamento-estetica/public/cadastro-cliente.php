<?php
if (!defined('ABSPATH')) exit;

function agend_formulario_cadastro_cliente() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agend_cadastrar_cliente'])) {
        $nome      = sanitize_text_field($_POST['nome']);
        $email     = sanitize_email($_POST['email']);
        $telefone  = sanitize_text_field($_POST['telefone']);
        $senha     = $_POST['senha'];

        if (email_exists($email)) {
            echo '<div class="notice notice-error">E-mail já cadastrado.</div>';
        } else {
            $user_id = wp_insert_user([
                'user_login' => $email,
                'user_email' => $email,
                'user_pass'  => $senha,
                'display_name' => $nome,
                'role' => 'subscriber'
            ]);

            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, 'telefone', $telefone);
                wp_set_auth_cookie($user_id);
                wp_redirect(home_url('/painel-cliente'));
                exit;
            } else {
                echo '<div class="notice notice-error">Erro ao cadastrar. Tente novamente.</div>';
            }
        }
    }

    ?>
    <div class="wrap">
        <h2>Cadastro de Cliente</h2>
        <form method="post">
            <p><label>Nome completo:<br><input type="text" name="nome" required></label></p>
            <p><label>E-mail:<br><input type="email" name="email" required></label></p>
            <p><label>Telefone:<br><input type="text" name="telefone" required></label></p>
            <p><label>Senha:<br><input type="password" name="senha" required></label></p>
            <p><input type="submit" name="agend_cadastrar_cliente" class="button button-primary" value="Cadastrar"></p>
        </form>
    </div>
    <?php
}
agend_formulario_cadastro_cliente();
