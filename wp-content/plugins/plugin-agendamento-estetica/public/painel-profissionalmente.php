<?php
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(home_url('/cadastro-cliente'));
    exit;
}

$user = wp_get_current_user();
$role = $user->roles[0] ?? '';

if ($role !== 'profissional') {
    wp_redirect(home_url('/'));
    exit;
}

$telefone = get_user_meta($user->ID, 'telefone', true);
?>

<div class="wrap">
    <h2>Bem-vindo(a), <?php echo esc_html($user->display_name); ?></h2>
    <p><strong>Email:</strong> <?php echo esc_html($user->user_email); ?></p>
    <p><strong>Telefone:</strong> <?php echo esc_html($telefone); ?></p>
    <p><a href="<?php echo esc_url(wp_logout_url(home_url('/cadastro-cliente'))); ?>">Sair</a></p>
</div>
