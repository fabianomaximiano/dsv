# Meu Endereço Personalizado

Plugin para WooCommerce que preenche automaticamente o endereço com base no CEP, exibe mensagens personalizadas para bairros atendidos e permite gerenciar até 3 tipos de endereços por cliente.

---

## Funcionalidades

- Autopreenchimento de endereço com base no CEP.
- Mensagens dinâmicas sobre entrega, frete grátis e cobertura por bairro.
- Armazenamento do CEP informado no carrinho para uso no checkout.
- Gerenciamento de até três endereços personalizados por cliente: residencial, comercial e presente.
- Painel administrativo para cadastrar bairros e faixas de CEP.
- Validação de CEPs não atendidos com aviso direto no carrinho e checkout.

---

## Instalação

1. Faça o upload da pasta `meu-endereco-personalizado` para o diretório `/wp-content/plugins/`.
2. Ative o plugin no menu "Plugins" do WordPress.
3. Acesse o novo menu "Endereço Personalizado" no painel administrativo.
4. Cadastre os bairros e faixas de CEP desejados.
5. O plugin estará ativo no carrinho e no checkout do WooCommerce.

---

## Como Funciona

1. O cliente informa o CEP no carrinho de compras.
2. O plugin utiliza a API do ViaCEP para buscar o endereço correspondente.
3. Se o bairro estiver na lista de cobertura, o endereço é salvo na sessão e exibido no checkout.
4. Caso contrário, o cliente será informado de que o bairro não é atendido.
5. Os campos do formulário de finalização são preenchidos automaticamente com os dados recuperados.

---

## Estrutura do Plugin

