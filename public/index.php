<?php
// sistema/public/index.php
require '../config.php';

$conn = connect_db();
$sql = "SELECT id, nome, descricao, preco FROM produtos WHERE disponivel = 1";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">

</head>
<body>
    <header class="headerClass">
        <div class="icone-container">
            <img src="../images/icone_galopes.svg" alt="" class="icone">
            <p>Lopes</p>
        </div>
        <div class="login-buttons">
            <a href="login.php" class="btn">Login</a>
            <a href="cadastro.php" class="btn">Cadastrar</a>
        </div>
    </header>
    
    <div>
        <img src="../images/agua2.jpg" alt="">
    </div>



    <div class="container">
        
        
        <div class="product-list">
            <?php while($produto = $result->fetch_assoc()): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <p><?= htmlspecialchars($produto['descricao']) ?></p>
                <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                <a href="produto.php?id=<?= $produto['id'] ?>" class="btn">Ver Detalhes</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>