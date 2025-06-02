<?php
if (!defined('ABSPATH')) exit;

$current_user = wp_get_current_user();
$roles = (array) $current_user->roles;

echo '<div class="wrap">';

if (in_array('profissional', $roles)) {
    // Mostra painel do profissional
    agend_agenda_profissional();
} else {
    // Mostra painel do cliente
    echo do_shortcode('[agend_dashboard_cliente]');
}

echo '</div>';
