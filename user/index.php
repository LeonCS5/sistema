<?php
// sistema/user/index.php
require '../config.php';

if (!is_logged_in() || is_admin()) {
    redirect('../public/index.php');
}

$conn = connect_db();
$sql = "SELECT id, nome, descricao, preco FROM produtos WHERE disponivel = 1";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .product-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; transition: transform 0.3s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-card h3 { margin-top: 0; }
        .product-card .price { font-weight: bold; color: #e74c3c; font-size: 1.2em; margin: 10px 0; }
        .btn { background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .user-actions { display: flex; gap: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['user_nome'] ?? 'Cliente') ?></h1>
            <div class="user-actions">
                <a href="pedidos.php" class="btn">Meus Pedidos</a>
                <a href="../logout.php" class="btn">Sair</a>
            </div>
        </header>
        
        <h2>Produtos Disponíveis</h2>
        <div class="product-list">
            <?php while($produto = $result->fetch_assoc()): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <p><?= htmlspecialchars($produto['descricao']) ?></p>
                <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                <a href="../public/produto.php?id=<?= $produto['id'] ?>" class="btn">Ver Detalhes</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>