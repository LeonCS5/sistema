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
    <link rel="stylesheet" href="../css/effecs.css">

    

</head>
<body>

    <div class="main">
        <div>
            <?php include '../components/header.php';?>
            
            <div class="image-main" id="main">
                <h1><span>Água</span> pura, atendimento transparente, direto do Lopes pra sua casa.</h1>
                <img src="../images/back.png" alt="" class="page-image">
            </div>
        
        
        
            <div class="container">
                
    
                <div class="product-list">
                    <?php while($produto = $result->fetch_assoc()): ?>
                    <div class="product-card" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>"  class="produto-image">
                        <div class="product-info">
                            <h3 class="name-product"><?= htmlspecialchars($produto['nome']) ?></h3>
                            <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                            <a href="produto.php?id=<?= $produto['id'] ?>" class="btn pd ativo">
                                <p class="text-product">PEDIR</p>
                                <img src="../images/cart.svg" alt="carrinho" class="cart-icon">
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
                    <p>Água do Lopes nasceu com um propósito simples: levar até você água mineral de qualidade com confiança, agilidade e um atendimento que faz a diferença. Somos uma empresa com raízes firmes e comprometida em oferecer o melhor para a sua saúde e bem-estar.
                        Atuando no mercado com responsabilidade e transparência, nos orgulhamos de entregar água mineral natural das melhores marcas, sempre dentro dos mais altos padrões de qualidade e segurança. Nosso diferencial está no cuidado com cada detalhe — da seleção das fontes à entrega no seu lar ou empresa.</p>
                </div>
            </div>
        </section>


        <?php include '../components/footer.php';?>


    </div>

</body>

</html>
<?php $conn->close(); ?>