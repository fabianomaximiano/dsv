<?php
/**
 * Template para a dashboard do salão
 */

if (!defined('ABSPATH')) exit;

get_header();

echo do_shortcode('[agend_dashboard_salao]');

get_footer();
