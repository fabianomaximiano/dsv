=== Meu Starter Dinâmico ===
Contributors: seu_nome
Tags: plugins, instalacao, automatica, wordpress, gerenciamento
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatiza a instalação e ativação de plugins padrão a partir de listas JSON gerenciáveis via painel administrativo do WordPress.

== Description ==

Meu Starter Dinâmico permite que você configure listas personalizadas de plugins para instalar e ativar automaticamente em seus projetos WordPress. Você pode escolher entre listas predefinidas ou fazer upload de suas próprias listas em formato JSON diretamente pelo painel.

Ideal para agilizar a configuração inicial de projetos e manter o padrão de plugins usados em clientes e sites próprios.

== Installation ==

1. Faça upload da pasta do plugin para o diretório `/wp-content/plugins/`.
2. Ative o plugin pelo menu "Plugins" no WordPress.
3. No menu lateral do WordPress, acesse "Starter Plugins".
4. Escolha a lista de plugins JSON ativa ou envie seu próprio arquivo JSON.
5. Clique em "Instalar Plugins" para baixar, instalar e ativar automaticamente os plugins da lista.

== Frequently Asked Questions ==

= Posso usar minhas próprias listas de plugins? =

Sim! Basta enviar arquivos JSON válidos no formato de array com os slugs dos plugins via o painel de controle do plugin.

= Quais formatos de arquivos são aceitos? =

Apenas arquivos JSON contendo um array simples de slugs de plugins.

= O plugin instala os plugins automaticamente? =

Sim, após clicar em "Instalar Plugins", ele baixa, instala e ativa os plugins listados.

== Screenshots ==

1. Tela principal do menu "Starter Plugins" com seleção e upload de listas JSON.
2. Botão para instalar os plugins da lista ativa.
3. Visualização do conteúdo do JSON ativo.

== Changelog ==

= 1.1 =
* Painel administrativo para selecionar e enviar listas JSON
* Função de instalação e ativação automática dos plugins da lista ativa
* Suporte a múltiplas listas JSON gerenciáveis

= 1.0 =
* Versão inicial com instalação estática de plugins a partir de arquivo JSON fixo

== License ==

Este plugin é licenciado sob a GPLv2 ou superior.

== Support ==

Para dúvidas e sugestões, entre em contato com [seu e-mail ou site].

