<?php
/**
 * Plugin Name: Meu Starter Dinâmico
 * Description: Instala plugins padrão a partir de um JSON via painel de controle.
 * Version: 1.0
 * Author: Seu Nome
 */

defined('ABSPATH') || exit;

define('MEU_STARTER_DIR', plugin_dir_path(__FILE__));

require_once MEU_STARTER_DIR . 'includes/installer.php';
require_once MEU_STARTER_DIR . 'includes/admin-page.php';
