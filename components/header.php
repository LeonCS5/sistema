<?php


$conn = connect_db();
// Calcular total e obter detalhes dos produtos
$total_items = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
        $total_items += 1;
    }


}
?>

<header class="headerClass">
    <a class="icone-container" href="../public/index.php">

        
        <?php echo add_svg("../images/calopes.svg", "icone", TRUE); ?>

        <p class="text-logo"><span>LOPES</span></p>
    </a>
    <div class="login-buttons">
        
        <input type="color" id="seletorCor" value="#ff0000">
        <?php if (!is_logged_in()): ?>
            <a href="../public/catalogo.php" class="btn">Catalogo</a>
            <a href="../public/carrinho.php" class="btn-carrinho">
                <div class="cart-container">
                    <?php echo add_svg("../images/cart1.svg", "header-icon", FALSE); ?>
                    <?php if ($total_items > 0): ?>
                        <span class="cart-quantity"><?= htmlspecialchars($total_items) ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <a href="../public/login.php" class="btn">Login</a>
            <a href="../public/cadastro.php" class="btn ativo">Cadastrar</a>
        <?php else: ?>
            <a href="../public/catalogo.php" class="btn">Catalogo</a>        
            <a href="../user/pedidos.php" class="btn">Meus Pedidos</a>
            <a href="../public/carrinho.php" class="btn-carrinho">
                <div class="cart-container">
                    <!-- <img src="../images/cart.svg" alt="" class="header-icon"> -->
                    <?php echo add_svg("../images/cart1.svg", "header-icon", FALSE); ?>

                    <?php if ($total_items > 0): ?>
                        <span class="cart-quantity"><?= htmlspecialchars($total_items) ?></span>
                    <?php endif; ?>
                </div>
            
            </a>
            <a href="../logout.php"><img src="../uploads/<?= htmlspecialchars($_SESSION['imagem']) ?>" alt="" class="user-image"></a> 
        <?php endif; ?>  
    </div>


</header>