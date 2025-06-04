<?php
// sistema/public/index.php
require '../config.php';


$conn = connect_db();
$sql = "SELECT id, nome, descricao, preco, imagem FROM produtos WHERE disponivel = 1";
$result = $conn->query($sql);



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lopes</title>
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">

</head>
<body>

    <div class="main">
        <div>
            <header class="headerClass">
                <a class="icone-container" href="index.php">
                    <img src="../images/icone_galopes.svg" alt="" class="icone">
                    <p class="text-logo"><span>LOPES</span></p>
                </a>
                <div class="login-buttons">
                    <a href="catalogo.php" class="btn">Catálogo</a>
                    
                    <?php if (!is_logged_in()): ?>
                        <a href="carrinho.php" class="btn">Carrinho</a>
                        <a href="login.php" class="btn">Login</a>
                        <a href="cadastro.php" class="btn ativo">Cadastrar</a>
                    <?php else: ?>        
                        <a href="../user/pedidos.php" class="btn">Meus Pedidos</a>
                        <a href="carrinho.php" class="btn">Carrinho</a>
                        <a href="../logout.php"><img src="../uploads/<?= htmlspecialchars($_SESSION['imagem']) ?>" alt="" class="user-image"></a> 

                    <?php endif; ?>
                    

                    
                </div>
            </header>
            
            <div class="image-main" id="main">
                <h1>Agua <span>foda</span>, Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                bottom text</h1>
                <img src="../images/back.png" alt="" class="page-image">
            </div>
        
        
        
            <div class="container">
                
    
                <div class="product-list">
                    <?php while($produto = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="" class="produto-image">
                        <div class="product-info">
                            <p class="name-product"><?= htmlspecialchars($produto['nome']) ?></h3>
                            <h3><?= htmlspecialchars($produto['descricao']) ?></p>
                            <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                            <a href="produto.php?id=<?= $produto['id'] ?>" class="btn pd ativo">
                                <p>PEDIR</p>
                                <img src="../images/cart.svg" alt="" class="cart-icon">
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <section class="about container" id="about">
            <div class="about-text">
                <img src="../images/back.png" alt="" class="page-image">
                <div>
                    <h1>Sobre nós</h1>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Placeat dolorum voluptatem omnis fugiat rerum! Nam odit aliquid deserunt architecto accusamus suscipit perspiciatis. Nemo velit recusandae cumque molestiae officiis amet magnam?</p>
                </div>
            </div>
        </section>


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

</body>
</html>
<?php $conn->close(); ?>