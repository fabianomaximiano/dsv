<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$tabela = $wpdb->prefix . 'mep_faixas_entrega';
$faixas = $wpdb->get_results("SELECT * FROM {$tabela} ORDER BY cep_inicial ASC");
?>

<div class="wrap">
    <h1><?php esc_html_e('Endereços Personalizados por Faixa de CEP', 'meu-endereco-personalizado'); ?></h1>

    <form id="mep-form">
        <?php wp_nonce_field('mep_admin_nonce', 'mep_nonce'); ?>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="bairro">Bairro</label></th>
                <td><input name="bairro" id="bairro" type="text" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="cep_inicial">CEP Inicial</label></th>
                <td><input name="cep_inicial" id="cep_inicial" type="text" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="cep_final">CEP Final</label></th>
                <td><input name="cep_final" id="cep_final" type="text" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="frete_gratis">Frete Grátis?</label></th>
                <td><input name="frete_gratis" id="frete_gratis" type="checkbox" value="1"></td>
            </tr>
            <tr>
                <th scope="row"><label for="valor_frete">Valor do Frete (R$)</label></th>
                <td><input name="valor_frete" id="valor_frete" type="number" step="0.01" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="mensagem">Mensagem Personalizada</label></th>
                <td><input name="mensagem" id="mensagem" type="text" class="regular-text"></td>
            </tr>
        </table>

        <p>
            <button type="submit" class="button button-primary"><?php esc_html_e('Salvar', 'meu-endereco-personalizado'); ?></button>
        </p>
    </form>

    <hr>

    <h2><?php esc_html_e('Faixas de CEP Cadastradas', 'meu-endereco-personalizado'); ?></h2>

    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th><?php esc_html_e('Bairro', 'meu-endereco-personalizado'); ?></th>
                <th><?php esc_html_e('CEP Inicial', 'meu-endereco-personalizado'); ?></th>
                <th><?php esc_html_e('CEP Final', 'meu-endereco-personalizado'); ?></th>
                <th><?php esc_html_e('Frete Grátis', 'meu-endereco-personalizado'); ?></th>
                <th><?php esc_html_e('Valor do Frete', 'meu-endereco-personalizado'); ?></th>
                <th><?php esc_html_e('Mensagem', 'meu-endereco-personalizado'); ?></th>
                <th><?php esc_html_e('Ações', 'meu-endereco-personalizado'); ?></th>
            </tr>
        </thead>
        <tbody id="mep-faixas-lista">
            <?php if ($faixas): ?>
                <?php foreach ($faixas as $faixa): ?>
                    <tr data-id="<?php echo esc_attr($faixa->id); ?>">
                        <td><?php echo esc_html($faixa->bairro); ?></td>
                        <td><?php echo esc_html($faixa->cep_inicial); ?></td>
                        <td><?php echo esc_html($faixa->cep_final); ?></td>
                        <td><?php echo $faixa->frete_gratis ? 'Sim' : 'Não'; ?></td>
                        <td><?php echo number_format($faixa->valor_frete, 2, ',', '.'); ?></td>
                        <td><?php echo esc_html($faixa->mensagem); ?></td>
                        <td>
                            <button type="button" class="button button-secondary mep-editar-faixa"><?php esc_html_e('Editar', 'meu-endereco-personalizado'); ?></button>
                            <button type="button" class="button button-link-delete mep-excluir-faixa"><?php esc_html_e('Excluir', 'meu-endereco-personalizado'); ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7"><?php esc_html_e('Nenhuma faixa cadastrada ainda.', 'meu-endereco-personalizado'); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
