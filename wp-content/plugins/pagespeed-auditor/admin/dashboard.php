<?php
if (!defined('ABSPATH')) exit;

function psa_dashboard_page() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'pagespeed_leads';
    $leads = $wpdb->get_results("SELECT * FROM $tabela ORDER BY data DESC");

    echo '<div class="wrap">';
    echo '<h1>Leads do PageSpeed Auditor</h1>';
    if ($leads) {
        echo '<table class="widefat striped">';
        echo '<thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>URL</th><th>Data</th></tr></thead>';
        echo '<tbody>';
        foreach ($leads as $lead) {
            echo '<tr>';
            echo '<td>' . esc_html($lead->id) . '</td>';
            echo '<td>' . esc_html($lead->nome) . '</td>';
            echo '<td>' . esc_html($lead->email) . '</td>';
            echo '<td>' . esc_html($lead->url) . '</td>';
            echo '<td>' . esc_html($lead->data) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>Nenhum lead encontrado.</p>';
    }
    echo '</div>';
}
?>
