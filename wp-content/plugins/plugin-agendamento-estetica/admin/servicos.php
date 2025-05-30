<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Página de listagem de serviços no admin
 */
function agend_servicos_pagina() {
    global $wpdb;
    $tabela_servicos = $wpdb->prefix . 'agend_servicos';

    $servicos = $wpdb->get_results("SELECT * FROM $tabela_servicos");

    echo '<div class="wrap">';
    echo '<h1>Serviços Cadastrados</h1>';

    if ($servicos) {
        echo '<table class="widefat fixed striped">';
        echo '<thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Duração (min)</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
              </thead>';
        echo '<tbody>';

        foreach ($servicos as $servico) {
            echo '<tr>';
            echo '<td>' . esc_html($servico->id) . '</td>';
            echo '<td>' . esc_html($servico->nome) . '</td>';
            echo '<td>' . esc_html($servico->duracao) . '</td>';
            echo '<td>R$ ' . number_format($servico->preco, 2, ',', '.') . '</td>';
            echo '<td>
                    <a href="#" class="button button-small">Editar</a>
                    <a href="#" class="button button-small">Excluir</a>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhum serviço cadastrado ainda.</p>';
    }

    echo '</div>';
}
