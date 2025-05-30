<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$tabela = $wpdb->prefix . 'pagespeed_leads';

// Deleta a tabela criada pelo plugin
$wpdb->query("DROP TABLE IF EXISTS $tabela");

// Remove opções do plugin
delete_option('psa_google_api_key');
