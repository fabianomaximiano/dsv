<?php
if (!defined('ABSPATH')) exit;

get_header(); ?>

<div class="container my-5">
    <h2>Agende seu horário</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="cliente_nome">Seu nome</label>
            <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" required>
        </div>

        <div class="form-group">
            <label>Serviços</label><br>
            <?php
            global $wpdb;
            $servicos = $wpdb->get_results("SELECT id, nome, duracao FROM {$wpdb->prefix}agend_servicos");
            foreach ($servicos as $servico) {
                echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='checkbox' name='servicos[]' value='{$servico->id}' id='servico_{$servico->id}'>
                        <label class='form-check-label' for='servico_{$servico->id}'>{$servico->nome} ({$servico->duracao} min)</label>
                      </div>";
            }
            ?>
        </div>

        <div class="form-group">
            <label for="profissional">Profissional</label>
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
            <label for="horario">Horário disponível</label>
            <select class="form-control" id="horario" name="horario" required>
                <option value="">Selecione os serviços e profissional</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Confirmar Agendamento</button>
    </form>
</div>

<?php get_footer(); ?>
