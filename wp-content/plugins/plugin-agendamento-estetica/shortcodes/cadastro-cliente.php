<?php
// Arquivo: shortcodes/cadastro-cliente.php

function agend_shortcode_cadastro_cliente() {
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agend_cadastrar_cliente'])) {
        $nome       = sanitize_text_field($_POST['nome']);
        $email      = sanitize_email($_POST['email']);
        $telefone   = sanitize_text_field($_POST['telefone']);
        $cpf        = sanitize_text_field($_POST['cpf']);
        $nascimento = sanitize_text_field($_POST['nascimento']);

        global $wpdb;

        if (!is_email($email)) {
            echo '<p style="color:red">E-mail inválido.</p>';
        } elseif (!agend_valida_cpf($cpf)) {
            echo '<p style="color:red">CPF inválido.</p>';
        } elseif (email_exists($email)) {
            echo '<p style="color:red">Este e-mail já está em uso.</p>';
        } elseif ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}agend_clientes WHERE cpf = %s", $cpf)) > 0) {
            echo '<p style="color:red">Este CPF já está cadastrado.</p>';
        } else {
            $user_id = wp_create_user($email, wp_generate_password(), $email);

            if (is_wp_error($user_id)) {
                echo '<p style="color:red">Erro ao criar usuário.</p>';
            } else {
                $wpdb->insert(
                    $wpdb->prefix . 'agend_clientes',
                    [
                        'nome'            => $nome,
                        'email'           => $email,
                        'telefone'        => $telefone,
                        'cpf'             => $cpf,
                        'data_nascimento' => $nascimento,
                        'user_id'         => $user_id
                    ],
                    ['%s', '%s', '%s', '%s', '%s', '%d']
                );

                echo '<p style="color:green">Cadastro realizado com sucesso!</p>';
                echo '<script>setTimeout(function() { window.location.reload(); }, 2000);</script>';
            }
        }
    }
    ?>

    <form method="post">
        <label for="nome">Nome completo:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="telefone">Telefone:</label><br>
        <input type="text" id="telefone" name="telefone" required><br><br>

        <label for="cpf">CPF:</label><br>
        <input type="text" id="cpf" name="cpf" required><br><br>

        <label for="nascimento">Data de nascimento:</label><br>
        <input type="date" id="nascimento" name="nascimento" required><br><br>

        <input type="submit" name="agend_cadastrar_cliente" value="Cadastrar">
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('agend_cadastro_cliente', 'agend_shortcode_cadastro_cliente');
