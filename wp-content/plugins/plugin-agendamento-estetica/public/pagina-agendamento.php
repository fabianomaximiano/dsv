<?php
if (!defined('ABSPATH')) exit;

function agend_formulario_agendamento() {
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/cadastro-cliente'));
        exit;
    }

    global $wpdb;
    $user_id = get_current_user_id();

    // Buscar serviços ativos
    $servicos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}agend_servicos ORDER BY nome");

    // Processar agendamento
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar_servico'])) {
        $servicos_selecionados = $_POST['servicos'] ?? [];
        $data = sanitize_text_field($_POST['data']);
        $hora = sanitize_text_field($_POST['hora']);

        if (empty($servicos_selecionados) || empty($data) || empty($hora)) {
            echo '<div class="notice notice-error"><p>Preencha todos os campos.</p></div>';
        } else {
            $data_hora = $data . ' ' . $hora . ':00';

            $wpdb->insert("{$wpdb->prefix}agend_agendamentos", [
                'cliente_id' => $user_id,
                'servicos'   => implode(',', $servicos_selecionados),
                'data_hora'  => $data_hora,
                'status'     => 'pendente'
            ]);

            echo '<div class="notice notice-success"><p>Agendamento realizado com sucesso!</p></div>';
        }
    }

    // Formulário
    ?>
    <div class="wrap">
        <h2>Agendar Serviço</h2>
        <form method="post">
            <p><label>Selecione os serviços:</label><br>
                <?php foreach ($servicos as $servico): ?>
                    <label>
                        <input type="checkbox" name="servicos[]" value="<?= esc_attr($servico->id) ?>"> <?= esc_html($servico->nome) ?> (<?= esc_html($servico->duracao) ?> min)
                    </label><br>
                <?php endforeach; ?>
            </p>
            <p>
                <label>Data:<br><input type="date" name="data" required></label>
            </p>
            <p>
                <label>Hora:<br><input type="time" name="hora" required></label>
            </p>
            <p><input type="submit" name="agendar_servico" class="button button-primary" value="Agendar"></p>
        </form>
    </div>
    <?php
}
agend_formulario_agendamento();
