<?php


$conn = connect_db();
// Calcular total e obter detalhes dos produtos
$total = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
        $total += 1;
    }


}
?>

<header class="headerClass">
    <a class="icone-container" href="index.php">
    <img src="../images/icone_galopes.svg" alt="" class="icone">
    <p class="text-logo"><span>LOPES</span></p>
    </a>
    <div class="login-buttons">
        
        <?php if (!is_logged_in()): ?>
            <a href="carrinho.php" class="btn-carrinho">
                <div class="cart-container">
                    <img src="../images/cart.svg" alt="" class="header-icon">
                    <?php if ($total > 0): ?>
                        <span class="cart-quantity"><?= htmlspecialchars($total) ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <a href="login.php" class="btn">Login</a>
            <a href="cadastro.php" class="btn ativo">Cadastrar</a>
        <?php else: ?>        
            <a href="../user/pedidos.php" class="btn">Meus Pedidos</a>
            <a href="carrinho.php" class="btn-carrinho">
                <div class="cart-container">
                    <img src="../images/cart.svg" alt="" class="header-icon">
                    <?php if ($total > 0): ?>
                        <span class="cart-quantity"><?= htmlspecialchars($total) ?></span>
                    <?php endif; ?>
                </div>
            
            </a>
            <a href="../logout.php"><img src="../uploads/<?= htmlspecialchars($_SESSION['imagem']) ?>" alt="" class="user-image"></a> 
        <?php endif; ?>  
    </div>
</header>