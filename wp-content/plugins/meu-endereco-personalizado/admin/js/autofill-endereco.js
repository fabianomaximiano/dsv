document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("mep-add-form");
    const deleteButtons = document.querySelectorAll(".mep-delete");

    // Submissão do formulário de faixa de CEP
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const data = {
                action: "mep_salvar_faixa",
                nonce: mep_admin_vars.nonce,
                bairro: form.bairro.value,
                cep_inicial: form.cep_inicial.value,
                cep_final: form.cep_final.value,
                valor_frete: form.valor_frete.value,
                frete_gratis: form.frete_gratis.checked ? 1 : 0,
                mensagem: form.mensagem.value
            };

            fetch(mep_admin_vars.ajax_url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams(data)
            })
            .then((res) => res.json())
            .then((res) => {
                if (res.success) {
                    location.reload();
                } else {
                    alert("Erro ao salvar faixa.");
                }
            });
        });
    }

    // Exclusão de faixa de CEP
    deleteButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;

            if (confirm("Deseja realmente excluir esta faixa?")) {
                const data = {
                    action: "mep_excluir_faixa",
                    nonce: mep_admin_vars.nonce,
                    faixa_id: id
                };

                fetch(mep_admin_vars.ajax_url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams(data)
                })
                .then((res) => res.json())
                .then((res) => {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert("Erro ao excluir faixa.");
                    }
                });
            }
        });
    });
});
