<?php
// sistema/public/carrinho.php
require '../config.php';

$conn = connect_db();

// Adicionar produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
    $produto_id = (int)$_POST['produto_id'];
    $quantidade = (int)$_POST['quantidade'];
    
    // Verificar se o produto existe e está disponível
    $sql = "SELECT id, nome, preco, quantidade FROM produtos WHERE id = $produto_id AND disponivel = 1";
    $result = $conn->query($sql);
    $produto = $result->fetch_assoc();
    
    if ($produto && $quantidade > 0 && $quantidade <= $produto['quantidade']) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produto_id] = $quantidade;
        }
        
        $_SESSION['mensagem'] = 'Produto adicionado ao carrinho!';
    } else {
        $_SESSION['erro'] = 'Não foi possível adicionar o produto ao carrinho.';
    }
}

// Remover item do carrinho
if (isset($_GET['remover'])) {
    $produto_id = (int)$_GET['remover'];
    if (isset($_SESSION['carrinho'][$produto_id])) {
        unset($_SESSION['carrinho'][$produto_id]);
    }
    redirect('carrinho.php');
}

// Calcular total e obter detalhes dos produtos
$carrinho = [];
$total = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
        $sql = "SELECT id, nome, preco FROM produtos WHERE id = $produto_id";
        $result = $conn->query($sql);
        if ($produto = $result->fetch_assoc()) {
            $subtotal = $produto['preco'] * $quantidade;
            $total += $subtotal;
            $carrinho[] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => $quantidade,
                'subtotal' => $subtotal
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-size: 1.2em; font-weight: bold; margin: 20px 0; }
        .btn { background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #2ecc71; }
        .btn-success:hover { background: #27ae60; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .cart-actions { display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Carrinho de Compras</h1>
        
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="message success"><?= $_SESSION['mensagem'] ?></div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="message error"><?= $_SESSION['erro'] ?></div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        
        <?php if (empty($carrinho)): ?>
            <p>Seu carrinho está vazio.</p>
            <a href="index.php" class="btn">Continuar Comprando</a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrinho as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                        <td><a href="carrinho.php?remover=<?= $item['id'] ?>" class="btn btn-danger">Remover</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="total">
                Total: R$ <?= number_format($total, 2, ',', '.') ?>
            </div>
            
            <div class="cart-actions">
                <a href="index.php" class="btn">Continuar Comprando</a>
                <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>