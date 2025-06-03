<?php
// sistema/admin/excluir_produto.php
require '../config.php';

if (!is_admin() || !isset($_GET['id'])) {
    redirect('../public/index.php');
}

$conn = connect_db();
$id = (int)$_GET['id'];

// Verificar se o produto existe
$sql = "SELECT id FROM produtos WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sql = "DELETE FROM produtos WHERE id = $id";
    if ($conn->query($sql)) {
        $_SESSION['mensagem'] = 'Produto excluído com sucesso!';
    } else {
        $_SESSION['erro'] = 'Erro ao excluir produto: ' . $conn->error;
    }
} else {
    $_SESSION['erro'] = 'Produto não encontrado.';
}

$conn->close();
redirect('produtos.php');