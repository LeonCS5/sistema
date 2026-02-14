<?php
// sistema/public/produto.php
require '../config.php';



$conn = connect_db();

// Adicionar produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
    $produto_id_r = (int)$_POST['produto_id'];
    $quantidade_r = (int)$_POST['quantidade'];
    
    // Verificar se o produto existe e está disponível
    $sql_r = "SELECT id, nome, preco, quantidade FROM produtos WHERE id = $produto_id_r AND disponivel = 1";
    $result_r = $conn->query($sql_r);
    $produto_r = $result_r->fetch_assoc();
    
    if ($produto_r && $quantidade_r > 0 && $quantidade_r <= $produto_r['quantidade']) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        if (isset($_SESSION['carrinho'][$produto_id_r])) {
            $_SESSION['carrinho'][$produto_id_r] += $quantidade_r;
        } else {
            $_SESSION['carrinho'][$produto_id_r] = $quantidade_r;
        }
        
        $_SESSION['mensagem'] = 'Produto adicionado ao carrinho!';
        redirect('../public/checkout.php');
    } else {
        $_SESSION['erro'] = 'Não foi possível adicionar o produto ao carrinho.';
        redirect('../public/index.php');
    }

}

?>