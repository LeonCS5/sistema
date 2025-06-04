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
</head>
<body>
    <div class="main main-carrinho">
        
            <header class="headerClass">
                <a class="icone-container" href="index.php">
                    <img src="../images/icone_galopes.svg" alt="" class="icone">
                    <p class="text-logo"><span>LOPES</span></p>
                </a>
                <div class="login-buttons">
                    <a href="carrinho.php" class="btn">Carrinho</a>
                    <a href="catalogo.php" class="btn">Catalogo</a>
                    
                    <?php if (!is_logged_in()): ?>
                        <a href="login.php" class="btn">Login</a>
                        <a href="cadastro.php" class="btn ativo">Cadastrar</a>
                    <?php else: ?>        
                        <a href="../user/pedidos.php" class="btn">Meus Pedidos</a>

                        <a href="../logout.php"><img src="../uploads/<?= htmlspecialchars($_SESSION['imagem']) ?>" alt="" class="user-image"></a> 

                    <?php endif; ?>
                    

                    
                </div>
            </header>

            <div class="container container-carrinho">
                <h1>Carrinho</h1>
                
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
                    <div class="carrinho">

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
        
        
        <div>
            <footer>
                    <div class="footer-icones">
                        <a href="https://web.whatsapp.com/" target="_blank">
                            <img src="../images/whatsapp.svg" alt="" class="icone icone-footer">
                        </a>
                        <a href="https://www.facebook.com/" target="_blank">
                            <img src="../images/facebook.svg" alt="" class="icone icone-footer">
                        </a>
                        <a href="https://www.instagram.com/" target="_blank">
                            <img src="../images/instagram.svg" alt="" class="icone icone-footer">
                        </a>
                    </div>
                    <div class="flex">
                        <a href="#main" class="link-footer">Home</a>
                        <a href="#about" class="link-footer">Sobre nós</a>
                        <a href="carrinho.php" class="link-footer">Carrinho</a>
                        <a href="catalogo.php" class="link-footer">Catalogo</a>
                        <a href="https://web.whatsapp.com/" target="_blank" class="link-footer">Entre em Contato</a>
                    </div>
                </footer>
            <div class="footer-header">Copyright ©2025; Desenvolvido por <span id="dev">Matheus Paulo, Leonam, Lucas</span></div>
        </div>

    </div>
</body>
</html>
<?php $conn->close(); ?>