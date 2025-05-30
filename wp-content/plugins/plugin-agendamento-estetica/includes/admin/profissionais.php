<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

function agend_profissionais_pagina() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'agend_profissionais';

    // Busca os profissionais cadastrados
    $profissionais = $wpdb->get_results("SELECT * FROM $tabela ORDER BY nome ASC");

    echo '<div class="wrap">';
    echo '<h1>Profissionais Cadastrados</h1>';

    if ($profissionais) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>Nome</th>';
        echo '<th>Email</th>';
        echo '<th>Telefone</th>';
        echo '<th>Especialidade</th>';
        echo '<th>Ações</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        foreach ($profissionais as $prof) {
            echo '<tr>';
            echo '<td>' . esc_html($prof->nome) . '</td>';
            echo '<td>' . esc_html($prof->email) . '</td>';
            echo '<td>' . esc_html($prof->telefone) . '</td>';
            echo '<td>' . esc_html($prof->especialidade) . '</td>';
            echo '<td>';
            echo '<a href="#">Editar</a> | ';
            echo '<a href="#">Excluir</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhum profissional cadastrado ainda.</p>';
    }

    echo '</div>';
}
