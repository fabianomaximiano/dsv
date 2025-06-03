<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Agendamento de Serviços</h2>

    <form id="form-agendamento" method="post">
        <div class="form-group">
            <label for="cliente_nome">Nome:</label>
            <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" required>
        </div>

        <div class="form-group">
            <label for="cliente_email">E-mail:</label>
            <input type="email" class="form-control" id="cliente_email" name="cliente_email" required>
        </div>

        <div class="form-group">
            <label for="cliente_telefone">Telefone:</label>
            <input type="text" class="form-control" id="cliente_telefone" name="cliente_telefone" required>
        </div>

        <div class="form-group">
            <label for="servicos">Serviços:</label>
            <select multiple class="form-control" id="servicos" name="servicos[]">
                <?php
                global $wpdb;
                $servicos = $wpdb->get_results("SELECT id, nome, duracao FROM {$wpdb->prefix}agend_servicos");
                foreach ($servicos as $servico) {
                    echo "<option value='{$servico->id}' data-duracao='{$servico->duracao}'>{$servico->nome} ({$servico->duracao} min)</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="profissional">Profissional:</label>
            <select class="form-control" id="profissional" name="profissional" required>
                <option value="">Selecione</option>
                <?php
                $profissionais = $wpdb->get_results("SELECT id, nome FROM {$wpdb->prefix}agend_profissionais");
                foreach ($profissionais as $prof) {
                    echo "<option value='{$prof->id}'>{$prof->nome}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="data">Data:</label>
            <input type="date" class="form-control" id="data" name="data" required>
        </div>

        <div class="form-group">
            <label for="horario">Horário Disponível:</label>
            <select class="form-control" id="horario" name="horario" required>
                <option value="">Selecione a data e os serviços primeiro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agendar</button>
    </form>
</div>

<?php get_footer(); ?>
