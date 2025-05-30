<?php
// Impede acesso direto
if (!defined('ABSPATH')) exit;

add_shortcode('agendamento', 'agend_formulario_agendamento');

function agend_formulario_agendamento() {
    ob_start();

    if (!is_user_logged_in()) {
        echo '<p>Você precisa estar logado para agendar.</p>';
        return ob_get_clean();
    }

    $user_id = get_current_user_id();
    global $wpdb;

    $profissionais = $wpdb->get_results("SELECT id, nome FROM {$wpdb->prefix}agend_profissionais ORDER BY nome ASC");
    $servicos = $wpdb->get_results("SELECT id, nome, duracao FROM {$wpdb->prefix}agend_servicos ORDER BY nome ASC");

    ?>
    <div id="agendamento-form" class="agendamento-form">
        <h2>Agendar Atendimento</h2>

        <form id="form-agendamento">
            <input type="hidden" name="cliente_id" value="<?php echo esc_attr($user_id); ?>">

            <div>
                <label for="profissional_id">Profissional:</label>
                <select name="profissional_id" id="profissional_id" required>
                    <option value="">Selecione</option>
                    <?php foreach ($profissionais as $prof): ?>
                        <option value="<?php echo esc_attr($prof->id); ?>"><?php echo esc_html($prof->nome); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="servicos">Serviços:</label>
                <?php foreach ($servicos as $serv): ?>
                    <div>
                        <input type="checkbox" name="servicos[]" value="<?php echo esc_attr($serv->id); ?>">
                        <?php echo esc_html("{$serv->nome} ({$serv->duracao} min)"); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div>
                <label for="data">Data:</label>
                <input type="date" name="data" id="data" required>
            </div>

            <div>
                <label for="hora">Horário:</label>
                <select name="hora" id="hora" required>
                    <option value="">Selecione um profissional e uma data</option>
                </select>
            </div>

            <button type="submit">Agendar</button>
        </form>

        <div id="agendamento-mensagem"></div>
    </div>

    <script>
    jQuery(function($) {
        // Atualiza horários disponíveis
        function carregarHorarios() {
            var profissional_id = $('#profissional_id').val();
            var data = $('#data').val();
            var servicos = $('input[name="servicos[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (profissional_id && data && servicos.length) {
                $.post(ajaxurl, {
                    action: 'agend_horarios_disponiveis',
                    profissional_id: profissional_id,
                    data: data,
                    servicos: JSON.stringify(servicos)
                }, function(response) {
                    if (response.success) {
                        var options = '<option value="">Selecione</option>';
                        response.data.forEach(function(hora) {
                            options += '<option value="' + hora + '">' + hora + '</option>';
                        });
                        $('#hora').html(options);
                    } else {
                        $('#hora').html('<option value="">' + response.data.mensagem + '</option>');
                    }
                });
            }
        }

        $('#profissional_id, #data').on('change', carregarHorarios);
        $('input[name="servicos[]"]').on('change', carregarHorarios);

        // Envia agendamento
        $('#form-agendamento').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var dados = {
                action: 'agend_salvar_agendamento',
                cliente_id: $('input[name="cliente_id"]').val(),
                profissional_id: $('#profissional_id').val(),
                data: $('#data').val(),
                hora: $('#hora').val(),
                servicos: JSON.stringify(
                    $('input[name="servicos[]"]:checked').map(function() {
                        return this.value;
                    }).get()
                )
            };

            $.post(ajaxurl, dados, function(response) {
                $('#agendamento-mensagem').html(
                    '<p>' + response.data.mensagem + '</p>'
                );
                if (response.success) {
                    form.trigger('reset');
                    $('#hora').html('<option value="">Selecione</option>');
                }
            });
        });
    });
    </script>
    <?php

    return ob_get_clean();
}
