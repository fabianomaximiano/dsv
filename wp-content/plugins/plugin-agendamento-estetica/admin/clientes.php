<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

function agend_admin_clientes_page() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'agend_clientes';

    $clientes = $wpdb->get_results("SELECT * FROM $tabela ORDER BY criado_em DESC");

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Clientes</h1>';
    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>';
    echo '<tbody>';

    if ($clientes) {
        foreach ($clientes as $c) {
            echo '<tr>';
            echo '<td>' . esc_html($c->nome) . '</td>';
            echo '<td>' . esc_html($c->email) . '</td>';
            echo '<td>' . esc_html($c->telefone) . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($c->criado_em)) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4">Nenhum cliente cadastrado ainda.</td></tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}
