
<header class="headerClass">
    <a class="icone-container" href="index.php">
    <img src="../images/icone_galopes.svg" alt="" class="icone">
    <p class="text-logo"><span>LOPES</span></p>
    </a>
    <div class="login-buttons">
        <a href="catalogo.php" class="btn">Catálogo</a>
        <?php if (!is_logged_in()): ?>
            <a href="carrinho.php" class="btn btn-carrinho"><img src="../images/cart.svg" alt="" class="header-icon"></a>
            <a href="login.php" class="btn">Login</a>
            <a href="cadastro.php" class="btn ativo">Cadastrar</a>
        <?php else: ?>        
            <a href="../user/pedidos.php" class="btn">Meus Pedidos</a>
            <a href="carrinho.php" class="btn btn-carrinho"><img src="../images/cart.svg" alt="" class="header-icon"></a>
            <a href="../logout.php"><img src="../uploads/<?= htmlspecialchars($_SESSION['imagem']) ?>" alt="" class="user-image"></a> 
        <?php endif; ?>  
    </div>
</header>