<?php
add_action('wp_enqueue_scripts', 'astra_pizzaria_child_enqueue_styles');
function astra_pizzaria_child_enqueue_styles() {
    wp_enqueue_style('astra-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('astra-pizzaria-style', get_stylesheet_directory_uri() . '/style.css', array('astra-style'));
}
