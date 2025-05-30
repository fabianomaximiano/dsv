<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Página de listagem de profissionais no admin
 */
function agend_admin_profissionais_page() {
    global $wpdb;
    $tabela_profissionais = $wpdb->prefix . 'agend_profissionais';

    $profissionais = $wpdb->get_results("SELECT * FROM $tabela_profissionais");

    echo '<div class="wrap">';
    echo '<h1>Profissionais Cadastrados</h1>';

    if ($profissionais) {
        echo '<table class="widefat fixed striped">';
        echo '<thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Ações</th>
                </tr>
              </thead>';
        echo '<tbody>';

        foreach ($profissionais as $prof) {
            echo '<tr>';
            echo '<td>' . esc_html($prof->id) . '</td>';
            echo '<td>' . esc_html($prof->nome) . '</td>';
            echo '<td>' . esc_html($prof->email) . '</td>';
            echo '<td>' . esc_html($prof->telefone) . '</td>';
            echo '<td>' . esc_html($prof->cpf) . '</td>';
            echo '<td>
                    <a href="#" class="button button-small">Editar</a>
                    <a href="#" class="button button-small">Excluir</a>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhum profissional cadastrado ainda.</p>';
    }

    echo '</div>';
}
