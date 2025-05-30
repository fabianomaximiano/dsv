<?php
global $wpdb;
$tabela = $wpdb->prefix . 'mep_faixas_entrega';
$faixas = $wpdb->get_results("SELECT * FROM {$tabela}");
?>

<div class="wrap">
    <h1><?php esc_html_e('Gerenciar Faixas de Entrega', 'meu-endereco-personalizado'); ?></h1>

    <h2><?php esc_html_e('Nova Faixa', 'meu-endereco-personalizado'); ?></h2>
    <form id="mep-form">
        <?php wp_nonce_field('mep_admin_nonce', 'mep_nonce'); ?>
        <input type="hidden" name="id" id="mep-id">
        <table class="form-table">
            <tr>
                <th><label for="bairro">Bairro</label></th>
                <td><input type="text" name="bairro" id="bairro" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="cep_inicial">CEP Inicial</label></th>
                <td><input type="text" name="cep_inicial" id="cep_inicial" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="cep_final">CEP Final</label></th>
                <td><input type="text" name="cep_final" id="cep_final" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="frete_gratis">Frete Grátis</label></th>
                <td><input type="checkbox" name="frete_gratis" id="frete_gratis" value="1"></td>
            </tr>
            <tr>
                <th><label for="valor_frete">Valor do Frete</label></th>
                <td><input type="number" name="valor_frete" id="valor_frete" class="regular-text" step="0.01"></td>
            </tr>
            <tr>
                <th><label for="mensagem">Mensagem</label></th>
                <td><input type="text" name="mensagem" id="mensagem" class="regular-text"></td>
            </tr>
        </table>
        <p><button type="submit" class="button button-primary">Salvar Faixa</button></p>
    </form>

    <hr>

    <h2><?php esc_html_e('Faixas Cadastradas', 'meu-endereco-personalizado'); ?></h2>
    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th>Bairro</th>
                <th>CEP Inicial</th>
                <th>CEP Final</th>
                <th>Frete Grátis</th>
                <th>Valor Frete</th>
                <th>Mensagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="mep-faixas-lista">
            <?php if (!empty($faixas)) : ?>
                <?php foreach ($faixas as $faixa) : ?>
                    <tr data-id="<?php echo esc_attr($faixa->id); ?>">
                        <td><?php echo esc_html($faixa->bairro); ?></td>
                        <td><?php echo esc_html($faixa->cep_inicial); ?></td>
                        <td><?php echo esc_html($faixa->cep_final); ?></td>
                        <td><?php echo $faixa->frete_gratis ? 'Sim' : 'Não'; ?></td>
                        <td><?php echo number_format($faixa->valor_frete, 2, ',', '.'); ?></td>
                        <td><?php echo esc_html($faixa->mensagem); ?></td>
                        <td>
                            <button class="button mep-editar-faixa"><?php _e('Editar', 'meu-endereco-personalizado'); ?></button>
                            <button class="button mep-excluir-faixa"><?php _e('Excluir', 'meu-endereco-personalizado'); ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="7"><?php _e('Nenhuma faixa cadastrada.', 'meu-endereco-personalizado'); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
