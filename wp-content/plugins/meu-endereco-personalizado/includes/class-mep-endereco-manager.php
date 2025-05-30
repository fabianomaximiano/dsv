<?php
if (!defined('ABSPATH')) {
    exit;
}

class MEP_Endereco_Manager {

    public function __construct() {
        // Admin AJAX
        add_action('wp_ajax_mep_salvar_faixa', [$this, 'salvar_faixa']);
        add_action('wp_ajax_mep_excluir_faixa', [$this, 'excluir_faixa']);

        // Public AJAX
        add_action('wp_ajax_nopriv_mep_obter_faixa_por_cep', [$this, 'obter_faixa_por_cep']);
        add_action('wp_ajax_mep_obter_faixa_por_cep', [$this, 'obter_faixa_por_cep']);
    }

    public function salvar_faixa() {
        check_ajax_referer('mep_admin_nonce', 'nonce');

        global $wpdb;
        $tabela = $wpdb->prefix . 'mep_faixas_entrega';

        $bairro        = sanitize_text_field($_POST['bairro'] ?? '');
        $cep_inicial   = sanitize_text_field($_POST['cep_inicial'] ?? '');
        $cep_final     = sanitize_text_field($_POST['cep_final'] ?? '');
        $frete_gratis  = isset($_POST['frete_gratis']) && $_POST['frete_gratis'] === 'true' ? 1 : 0;
        $valor_frete   = floatval($_POST['valor_frete'] ?? 0);
        $mensagem      = sanitize_text_field($_POST['mensagem'] ?? '');

        if (empty($bairro) || empty($cep_inicial) || empty($cep_final)) {
            wp_send_json_error(['message' => 'Dados obrigatórios ausentes.']);
        }

        $resultado = $wpdb->insert($tabela, [
            'bairro'        => $bairro,
            'cep_inicial'   => $cep_inicial,
            'cep_final'     => $cep_final,
            'frete_gratis'  => $frete_gratis,
            'valor_frete'   => $valor_frete,
            'mensagem'      => $mensagem,
        ]);

        if ($resultado) {
            wp_send_json_success(['message' => 'Faixa salva com sucesso.']);
        } else {
            wp_send_json_error(['message' => 'Erro ao salvar faixa.']);
        }
    }

    public function excluir_faixa() {
        check_ajax_referer('mep_admin_nonce', 'nonce');

        global $wpdb;
        $tabela = $wpdb->prefix . 'mep_faixas_entrega';

        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            wp_send_json_error(['message' => 'ID inválido.']);
        }

        $resultado = $wpdb->delete($tabela, ['id' => $id]);

        if ($resultado) {
            wp_send_json_success(['message' => 'Faixa excluída com sucesso.']);
        } else {
            wp_send_json_error(['message' => 'Erro ao excluir faixa.']);
        }
    }

    public function obter_faixa_por_cep() {
        global $wpdb;

        $cep = sanitize_text_field($_REQUEST['cep'] ?? '');

        if (empty($cep)) {
            wp_send_json_error(['message' => 'CEP não informado.']);
        }

        $cep_num = preg_replace('/[^0-9]/', '', $cep); // remove traços e caracteres

        if (strlen($cep_num) !== 8) {
            wp_send_json_error(['message' => 'CEP inválido.']);
        }

        $tabela = $wpdb->prefix . 'mep_faixas_entrega';

        $faixa = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $tabela WHERE %d BETWEEN cep_inicial AND cep_final",
            $cep_num
        ));

        if ($faixa) {
            wp_send_json_success([
                'bairro'        => $faixa->bairro,
                'frete_gratis'  => (bool) $faixa->frete_gratis,
                'valor_frete'   => floatval($faixa->valor_frete),
                'mensagem'      => $faixa->mensagem
            ]);
        } else {
            wp_send_json_error(['message' => 'Nenhuma faixa encontrada para este CEP.']);
        }
    }
}
