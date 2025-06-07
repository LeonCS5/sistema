document.addEventListener('DOMContentLoaded', function () {

    const quantidade = document.getElementById('quantidade');
    const prod_id = document.getElementById('prod_id')
    document.getElementById('comprar').addEventListener('submit', function () {
        quantidade.setAttribute('form', 'comprar');
        prod_id.setAttribute('form', 'comprar');
    });
    document.getElementById('carrinho').addEventListener('submit', function () {
        quantidade.setAttribute('form', 'carrinho');
        prod_id.setAttribute('form', 'carrinho');
    });

});