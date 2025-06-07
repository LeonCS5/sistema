const seletor = document.getElementById('seletorCor');
seletor.addEventListener('input', function () {
    // Atualiza a variável CSS com a cor escolhida
        document.documentElement.style.setProperty('--neutral-100', this.value);
    });







