<?php
/**
 * Template para a dashboard do cliente
 */

if (!defined('ABSPATH')) exit;

get_header();

echo do_shortcode('[agend_dashboard_cliente]');

get_footer();
