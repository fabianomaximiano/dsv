<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

function agend_clientes_pagina() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'agend_clientes';

    // Busca os clientes
    $clientes = $wpdb->get_results("SELECT * FROM $tabela ORDER BY nome ASC");

    echo '<div class="wrap">';
    echo '<h1>Clientes Cadastrados</h1>';

    if ($clientes) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>Nome</th>';
        echo '<th>Email</th>';
        echo '<th>Telefone</th>';
        echo '<th>CPF</th>';
        echo '<th>Ações</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        foreach ($clientes as $cliente) {
            echo '<tr>';
            echo '<td>' . esc_html($cliente->nome) . '</td>';
            echo '<td>' . esc_html($cliente->email) . '</td>';
            echo '<td>' . esc_html($cliente->telefone) . '</td>';
            echo '<td>' . esc_html($cliente->cpf) . '</td>';
            echo '<td>';
            echo '<a href="#">Editar</a> | ';
            echo '<a href="#">Excluir</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhum cliente cadastrado ainda.</p>';
    }

    echo '</div>';
}
