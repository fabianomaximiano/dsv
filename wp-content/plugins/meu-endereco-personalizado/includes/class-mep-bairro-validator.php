<?php

class MEP_Bairro_Validator {

    public static function init() {
        add_action('woocommerce_cart_calculate_fees', [__CLASS__, 'validar_cep_no_carrinho'], 20);
        add_action('woocommerce_cart_calculate_fees', [__CLASS__, 'aplicar_valor_frete_personalizado'], 25);
        add_action('woocommerce_before_cart_totals', [__CLASS__, 'mostrar_mensagem_bairro']);
        add_filter('woocommerce_package_rates', [__CLASS__, 'aplicar_frete_gratis_personalizado'], 100, 2);
    }

    public static function validar_cep_no_carrinho() {
        if (!isset($_POST['calc_shipping']) || empty($_POST['calc_shipping_postcode'])) {
            return;
        }

        $cep = sanitize_text_field($_POST['calc_shipping_postcode']);
        $cep_numerico = preg_replace('/\D/', '', $cep);

        if (strlen($cep_numerico) !== 8) {
            WC()->session->__unset('mep_bairro_dados');
            return;
        }

        global $wpdb;
        $tabela = $wpdb->prefix . 'mep_faixas_entrega';

        $faixa = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $tabela WHERE %d BETWEEN cep_inicial AND cep_final",
            $cep_numerico
        ));

        if ($faixa) {
            WC()->session->set('mep_bairro_dados', [
                'bairro'        => $faixa->bairro,
                'cep'           => $cep,
                'mensagem'      => $faixa->mensagem,
                'frete_gratis'  => (bool) $faixa->frete_gratis,
                'valor_frete'   => floatval($faixa->valor_frete),
            ]);
        } else {
            WC()->session->__unset('mep_bairro_dados');
        }
    }

    public static function aplicar_valor_frete_personalizado() {
        $dados = WC()->session->get('mep_bairro_dados');

        if (!$dados || !empty($dados['frete_gratis'])) {
            return;
        }

        $valor = floatval($dados['valor_frete']);
        if ($valor > 0) {
            WC()->cart->add_fee('Frete personalizado', $valor, true);
        }
    }

    public static function mostrar_mensagem_bairro() {
        $dados = WC()->session->get('mep_bairro_dados');

        if ($dados) {
            echo '<div class="woocommerce-message">';
            echo esc_html($dados['mensagem']) . '<br>';
            echo 'Endereço identificado: Bairro ' . esc_html($dados['bairro']) . ' – CEP ' . esc_html($dados['cep']);
            echo '</div>';
        }
    }

    public static function aplicar_frete_gratis_personalizado($rates, $package) {
        $dados = WC()->session->get('mep_bairro_dados');

        if (!empty($dados['frete_gratis'])) {
            foreach ($rates as $key => $rate) {
                if (strpos($rate->method_id, 'free_shipping') === false) {
                    unset($rates[$key]);
                }
            }
        }

        return $rates;
    }
}
