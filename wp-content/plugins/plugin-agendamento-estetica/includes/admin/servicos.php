<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

function agend_servicos_pagina() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'agend_servicos';

    // Busca os serviços cadastrados
    $servicos = $wpdb->get_results("SELECT * FROM $tabela ORDER BY nome ASC");

    echo '<div class="wrap">';
    echo '<h1>Serviços Cadastrados</h1>';

    if ($servicos) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>Nome</th>';
        echo '<th>Duração</th>';
        echo '<th>Preço</th>';
        echo '<th>Status</th>';
        echo '<th>Ações</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        foreach ($servicos as $servico) {
            echo '<tr>';
            echo '<td>' . esc_html($servico->nome) . '</td>';
            echo '<td>' . esc_html($servico->duracao) . ' min</td>';
            echo '<td>R$ ' . number_format($servico->preco, 2, ',', '.') . '</td>';
            echo '<td>' . ($servico->status ? 'Ativo' : 'Inativo') . '</td>';
            echo '<td>';
            echo '<a href="#">Editar</a> | ';
            echo '<a href="#">Excluir</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhum serviço cadastrado ainda.</p>';
    }

    echo '</div>';
}
