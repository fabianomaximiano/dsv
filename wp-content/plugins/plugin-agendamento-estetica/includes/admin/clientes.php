<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// function agend_clientes_pagina() {
//     global $wpdb;
//     $tabela = $wpdb->prefix . 'agend_clientes';

//     // Verifica se a tabela existe
//     if ($wpdb->get_var("SHOW TABLES LIKE '$tabela'") != $tabela) {
//         echo '<div class="notice notice-error"><p>A tabela de clientes não existe. Verifique a instalação do plugin.</p></div>';
//         return;
//     }

//     // Busca os clientes
//     $clientes = $wpdb->get_results("SELECT * FROM $tabela ORDER BY nome ASC");

//     echo '<pre>';
//         var_dump($clientes);
//     echo '</pre>';


//     echo '<div class="wrap">';
//     echo '<h1>Clientes Cadastrados</h1>';

//     if ($clientes) {
//         echo '<table class="widefat fixed striped">';
//         echo '<thead><tr>';
//         echo '<th>Nome</th>';
//         echo '<th>Email</th>';
//         echo '<th>Telefone</th>';
//         echo '<th>CPF</th>';
//         echo '<th>Ações</th>';
//         echo '</tr></thead>';
//         echo '<tbody>';

//         foreach ($clientes as $cliente) {
//             echo '<tr>';
//             echo '<td>' . esc_html($cliente->nome) . '</td>';
//             echo '<td>' . esc_html($cliente->email) . '</td>';
//             echo '<td>' . esc_html($cliente->telefone) . '</td>';
//             echo '<td>' . esc_html($cliente->cpf) . '</td>';
//             echo '<td>';
//             echo '<a href="#">Editar</a> | ';
//             echo '<a href="#">Excluir</a>';
//             echo '</td>';
//             echo '</tr>';
//         }

//         echo '</tbody>';
//         echo '</table>';
//     } else {
//         echo '<p>Nenhum cliente cadastrado ainda.</p>';
//     }

//     echo '</div>';
// }


// function agend_clientes_pagina() {
//     global $wpdb;
//     $tabela = $wpdb->prefix . 'agend_clientes';

//     echo '<div class="wrap"><h1>Debug Clientes</h1>';

//     // Verifica se a tabela existe
//     if ($wpdb->get_var("SHOW TABLES LIKE '$tabela'") != $tabela) {
//         echo '<div class="notice notice-error"><p>Tabela de clientes não existe.</p></div>';
//         return;
//     }

//     // Tenta buscar os dados
//     try {
//         $clientes = $wpdb->get_results("SELECT * FROM $tabela");
//         if (is_wp_error($clientes)) {
//             echo '<p>Erro ao buscar clientes: ' . esc_html($clientes->get_error_message()) . '</p>';
//             return;
//         }

//         if (empty($clientes)) {
//             echo '<p>Nenhum cliente encontrado.</p>';
//         } else {
//             echo '<p>Clientes carregados com sucesso. Mostrando campos disponíveis do primeiro cliente:</p>';
//             echo '<pre>';
//             print_r($clientes[0]); // Mostra os campos do primeiro cliente
//             echo '</pre>';
//         }
//     } catch (Exception $e) {
//         echo '<p><strong>Erro crítico:</strong> ' . esc_html($e->getMessage()) . '</p>';
//     }

//     echo '</div>';
// }


function agend_clientes_pagina() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'agend_clientes';

    echo '<div class="wrap"><h1>Clientes - Diagnóstico</h1>';

    if ($wpdb->get_var("SHOW TABLES LIKE '$tabela'") != $tabela) {
        echo '<div class="notice notice-error"><p>A tabela de clientes não existe.</p></div>';
        return;
    }

    try {
        $clientes = $wpdb->get_results("SELECT * FROM $tabela");

        if (empty($clientes)) {
            echo '<p>Nenhum cliente encontrado.</p>';
        } else {
            echo '<p>Total de clientes: ' . count($clientes) . '</p>';
            echo '<h3>Exibindo estrutura do primeiro cliente:</h3>';
            echo '<pre>';
            print_r($clientes[0]); // <- Aqui veremos os campos reais retornados
            echo '</pre>';
        }
    } catch (Exception $e) {
        echo '<div class="notice notice-error"><p>Erro: ' . esc_html($e->getMessage()) . '</p></div>';
    }

    echo '</div>';
}
