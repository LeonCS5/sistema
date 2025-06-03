<?php
// sistema/public/produto.php
require '../config.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$conn = connect_db();
$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT id, nome, descricao, preco, imagem FROM produtos WHERE id = $id AND disponivel = 1";
$result = $conn->query($sql);
$produto = $result->fetch_assoc();

if (!$produto) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .product-detail { display: flex; gap: 30px; flex-wrap: wrap; }
        .product-image { flex: 1; background: #f9f9f9; min-height: 300px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; padding: 10px; }
        .product-image img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-info { flex: 2; }
        .price { font-size: 1.5em; color: #e74c3c; font-weight: bold; margin: 20px 0; }
        .btn { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .back-link { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">&larr; Voltar</a>
        <div class="product-detail">
            <div class="product-image">
                <?php if (!empty($produto['imagem'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem de <?= htmlspecialchars($produto['nome']) ?>">
                <?php else: ?>
                    <span>Imagem indisponível</span>
                <?php endif; ?>
            </div>
            <div class="product-info">
                <h1><?= htmlspecialchars($produto['nome']) ?></h1>
                <p><?= htmlspecialchars($produto['descricao']) ?></p>
                <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                
                <form action="carrinho.php" method="post">
                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" name="quantidade" min="1" value="1" required>
                    <button type="submit" class="btn">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
