jQuery(document).ready(function($) {
    $('#pagespeed-auditor-form').on('submit', function(e) {
        e.preventDefault();
        const dados = {
            action: 'psa_enviar_formulario',
            nome: $('#nome').val(),
            email: $('#email').val(),
            url: $('#url').val()
        };

        $('#psa-mensagem').text('Enviando...');

        $.post(psa_ajax.ajax_url, dados, function(resposta) {
            if (resposta.success) {
                $('#psa-mensagem').text('Relatório enviado com sucesso!');
                $('#pagespeed-auditor-form')[0].reset();
            } else {
                $('#psa-mensagem').text('Erro: ' + resposta.data);
            }
        });
    });
});
