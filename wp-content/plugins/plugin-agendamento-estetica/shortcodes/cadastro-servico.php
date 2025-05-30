<?php
// Arquivo: shortcodes/cadastro-servico.php

function agend_cadastro_servico_shortcode() {
    if (!current_user_can('manage_options')) {
        return '<p>Você não tem permissão para cadastrar serviços.</p>';
    }

    ob_start();
    global $wpdb;

    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agend_cadastrar_servico'])) {
        $nome = sanitize_text_field($_POST['nome']);
        $duracao = intval($_POST['duracao']);
        $preco = floatval(str_replace(',', '.', $_POST['preco']));

        $wpdb->insert(
            $wpdb->prefix . 'agend_servicos',
            [
                'nome' => $nome,
                'duracao' => $duracao,
                'preco' => $preco
            ],
            ['%s', '%d', '%f']
        );

        $mensagem = '<p>Serviço cadastrado com sucesso!</p>';
    }

    ?>
    <h2>Cadastro de Serviço</h2>
    <?php echo $mensagem; ?>
    <form method="post">
        <p>
            <label>Nome do Serviço:</label><br>
            <input type="text" name="nome" required>
        </p>
        <p>
            <label>Duração (em minutos):</label><br>
            <input type="number" name="duracao" required>
        </p>
        <p>
            <label>Preço (R$):</label><br>
            <input type="text" name="preco" required placeholder="Ex: 75.00">
        </p>
        <p>
            <button type="submit" name="agend_cadastrar_servico">Cadastrar Serviço</button>
        </p>
    </form>

    <hr>

    <h3>Serviços Cadastrados</h3>
    <?php
    $servicos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}agend_servicos ORDER BY nome ASC");

    if ($servicos) {
        echo '<ul>';
        foreach ($servicos as $servico) {
            echo '<li><strong>' . esc_html($servico->nome) . '</strong> - ' . $servico->duracao . 'min - R$' . number_format($servico->preco, 2, ',', '.') . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Nenhum serviço cadastrado ainda.</p>';
    }

    return ob_get_clean();
}

add_shortcode('agend_cadastro_servico', 'agend_cadastro_servico_shortcode');
