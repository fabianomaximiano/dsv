<?php
// Arquivo: shortcodes/cadastro-profissional.php

function agend_cadastro_profissional_shortcode() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para cadastrar um profissional.</div>';
    }

    ob_start();
    global $wpdb;

    $user_id = get_current_user_id();
    $mensagem = '';

    // Verifica se já é um profissional cadastrado
    $existe = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}agend_profissionais WHERE user_id = %d",
        $user_id
    ));

    if ($existe > 0) {
        return '<div class="alert alert-info">Você já está cadastrado como profissional.</div>';
    }

    // Processamento do formulário
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agend_cadastrar_profissional'])) {
        $nome = sanitize_text_field($_POST['nome']);
        $servicos = isset($_POST['servicos']) ? array_map('intval', $_POST['servicos']) : [];

        if (empty($nome)) {
            $mensagem = '<div class="alert alert-danger">O nome do profissional é obrigatório.</div>';
        } elseif (empty($servicos)) {
            $mensagem = '<div class="alert alert-danger">Selecione pelo menos um serviço.</div>';
        } else {
            $insertado = $wpdb->insert(
                $wpdb->prefix . 'agend_profissionais',
                [
                    'nome'    => $nome,
                    'user_id' => $user_id
                ],
                ['%s', '%d']
            );

            if ($insertado) {
                $prof_id = $wpdb->insert_id;

                foreach ($servicos as $servico_id) {
                    $wpdb->insert(
                        $wpdb->prefix . 'agend_profissionais_servicos',
                        [
                            'profissional_id' => $prof_id,
                            'servico_id'      => $servico_id
                        ],
                        ['%d', '%d']
                    );
                }

                $mensagem = '<div class="alert alert-success">Profissional cadastrado com sucesso!</div>';
            } else {
                $mensagem = '<div class="alert alert-danger">Erro ao cadastrar profissional. Tente novamente.</div>';
            }
        }
    }

    // Listar serviços disponíveis
    $servicos = $wpdb->get_results("SELECT id, nome FROM {$wpdb->prefix}agend_servicos ORDER BY nome ASC");
    ?>

    <div class="container mt-4">
        <h2>Cadastro de Profissional</h2>
        <?php echo $mensagem; ?>

        <form method="post" class="border p-3 rounded bg-light">
            <div class="form-group">
                <label for="nome">Nome do Profissional:</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Serviços que atende:</label><br>
                <?php if ($servicos): ?>
                    <?php foreach ($servicos as $s): ?>
                        <div class="form-check">
                            <input type="checkbox" name="servicos[]" value="<?php echo esc_attr($s->id); ?>" class="form-check-input" id="servico-<?php echo $s->id; ?>">
                            <label class="form-check-label" for="servico-<?php echo $s->id; ?>"><?php echo esc_html($s->nome); ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <em>Nenhum serviço cadastrado ainda.</em>
                <?php endif; ?>
            </div>

            <button type="submit" name="agend_cadastrar_profissional" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('agend_cadastro_profissional', 'agend_cadastro_profissional_shortcode');
