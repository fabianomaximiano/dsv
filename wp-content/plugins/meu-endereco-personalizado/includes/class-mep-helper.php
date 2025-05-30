<?php
if (!defined('ABSPATH')) exit;

class MEP_Helper {

    /**
     * Retorna todos os bairros cadastrados dinamicamente.
     *
     * @return array Lista de bairros únicos.
     */
    public static function bairros_entregues() {
        global $wpdb;
        $tabela = $wpdb->prefix . 'mep_faixas_entrega';
        $resultados = $wpdb->get_col("SELECT DISTINCT bairro FROM $tabela ORDER BY bairro ASC");

        return $resultados ?: [];
    }

    /**
     * Verifica se um bairro está presente na lista cadastrada, com normalização.
     *
     * @param string $bairro Nome do bairro a verificar.
     * @return bool True se o bairro estiver cadastrado, false caso contrário.
     */
    public static function bairro_esta_na_lista($bairro) {
        $bairro_normalizado = self::normalizar($bairro);
        $bairros_cadastrados = array_map(['self', 'normalizar'], self::bairros_entregues());

        return in_array($bairro_normalizado, $bairros_cadastrados, true);
    }

    /**
     * Normaliza strings removendo acentos, espaços e convertendo para minúsculas.
     *
     * @param string $str
     * @return string
     */
    public static function normalizar($str) {
        $str = remove_accents($str); // Função nativa do WP
        return strtolower(trim($str));
    }
}
