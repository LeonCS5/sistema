<?php
// sistema/public/carrinho.php
require '../config.php';

$conn = connect_db();


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
        $sql = "SELECT id, nome, preco,imagem FROM produtos WHERE id = $produto_id";
        $result = $conn->query($sql);
        if ($produto = $result->fetch_assoc()) {
            $subtotal = $produto['preco'] * $quantidade;
            $total += $subtotal;
            $carrinho[] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'imagem' => $produto['imagem'],
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
    <title>Carrinho</title>
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/carrinho.css">
</head>
<body>
    <div class="main main-carrinho">
        
            <?php include '../components/header.php';?>

            <div class="container container-carrinho">
                <div class="produtos-carrinho">
                    
                    <?php if (isset($_SESSION['mensagem'])): ?>
                        <!-- <div class="message success"><?= $_SESSION['mensagem'] ?></div> -->
                        <?php unset($_SESSION['mensagem']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['erro'])): ?>
                        <h1>Carrinho</h1>
                        <div class="message error"><?= $_SESSION['erro'] ?></div>
                        <?php unset($_SESSION['erro']); ?>
                    <?php endif; ?>
                    
                    <?php if (empty($carrinho)): ?>
                        <p>Seu carrinho está vazio.</p>
                        <a href="index.php" class="btn">Continuar Comprando</a>
                    <?php else: ?>
                        <div class="carrinho">
                                <h1>Carrinho</h1>
                                <?php foreach ($carrinho as $item): ?>
                                <div class="items-container">
                                    <div class="info-container">
                                        <div><img src="../uploads/<?= htmlspecialchars($item['imagem']) ?>" alt="" class="carrinho-imagem"></div>
                                        <div class="item-info name-sp"><h3>Nome</h3><p><?= htmlspecialchars($item['nome']) ?></p></div>
                                        <div class="item-info"><h3>Preço</h3><p>R$ <?= number_format($item['preco'], 2, ',', '.') ?></p></div>
                                        <div class="item-info"><h3>Quantidade</h3><p><?= $item['quantidade'] ?></p></div>
                                        <div class="item-info"><h3>Total</h3><p>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></p></div>
                                    </div>
                                    <div ><a href="carrinho.php?remover=<?= $item['id'] ?>" class="btn btn-excluir">Remover</a></div>
                                </div>
                                <?php endforeach; ?>
                            
                                <div class="total">
                                    Total: R$ <?= number_format($total, 2, ',', '.') ?>
                                </div>
                        </div>
                        
                        
                        <div>
                            <div class="cart-actions">
                                <a href="index.php" class="btn">Continuar Comprando</a>
                                <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
        

                
                
            </div>
        
        
        <div>
            <?php include '../components/footer.php';?>
        </div>

    </div>
</body>
</html>
<?php $conn->close(); ?>