<?php
// sistema/user/index.php
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
    <title>Catálogo</title>
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/catalogo.css">

</head>
<body>
    <div class="main main-carrinho">

        <?php include '../components/header.php';?>


        <div class="container container-catalogo">
            <h1>Catálogo</h1>
            <p>Os nossos melhores produtos.</p>
            <div class="products-catalogo">
                <?php while($produto = $result->fetch_assoc()): ?>
                <div class="item-card">
                    <div class="imagem-div"><img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="" class="catalogo-imagem"></div>
                    <div class="info-card">
                        <p class="name-product"><?= htmlspecialchars($produto['nome']) ?></p>
                    
                        <div class="price">R$<?= number_format($produto['preco'], 2, ',', '.') ?></div>
                        <a href="../public/produto.php?id=<?= $produto['id'] ?>" class="btn pd ativo">Ver Detalhes</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>


        <div>
            <?php include '../components/footer.php';?>
        </div>


    </div>
</body>
</html>
<?php $conn->close(); ?>