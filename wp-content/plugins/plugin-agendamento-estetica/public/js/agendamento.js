document.addEventListener('DOMContentLoaded', function () {
    const servicos = document.querySelectorAll('input[name="servicos[]"]');
    const horarioSelect = document.getElementById('horario');
    const profissionalSelect = document.getElementById('profissional');

    function calcularHorarioDisponivel() {
        const servicoIds = Array.from(servicos)
            .filter(s => s.checked)
            .map(s => s.value);
        const profissionalId = profissionalSelect.value;

        if (servicoIds.length && profissionalId) {
            fetch(`${agend_ajax.ajax_url}?action=agend_horarios_disponiveis`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    servicos: servicoIds.join(','),
                    profissional: profissionalId
                })
            })
            .then(response => response.json())
            .then(data => {
                horarioSelect.innerHTML = '';
                if (data.length) {
                    data.forEach(horario => {
                        const option = document.createElement('option');
                        option.value = horario;
                        option.textContent = horario;
                        horarioSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Nenhum horário disponível';
                    horarioSelect.appendChild(option);
                }
            });
        }
    }

    servicos.forEach(s => s.addEventListener('change', calcularHorarioDisponivel));
    profissionalSelect.addEventListener('change', calcularHorarioDisponivel);
});
