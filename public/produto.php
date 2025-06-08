<script src="../js/popup.js"></script>
<?php
// sistema/public/produto.php
require '../config.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$conn = connect_db();


// Adicionar produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
    $produto_id_r = (int)$_POST['produto_id'];
    $quantidade_r = (int)$_POST['quantidade'];
    
    // Verificar se o produto existe e está disponível
    $sql_r = "SELECT id, nome, preco, quantidade FROM produtos WHERE id = $produto_id_r AND disponivel = 1";
    $result_r = $conn->query($sql_r);
    $produto_r = $result_r->fetch_assoc();
    
    if ($produto_r && $quantidade_r > 0 && $quantidade_r <= $produto_r['quantidade']) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        if (isset($_SESSION['carrinho'][$produto_id_r])) {
            $_SESSION['carrinho'][$produto_id_r] += $quantidade_r;
        } else {
            $_SESSION['carrinho'][$produto_id_r] = $quantidade_r;
        }
        
        $_SESSION['mensagem'] = 'Produto adicionado ao carrinho!';
        popup_show('Produto adicionado ao carrinho!', "popup-sucess");
    } else {
        popup_show('Não foi possível adicionar o produto ao carrinho.', "popup-fail");
        $_SESSION['erro'] = 'Não foi possível adicionar o produto ao carrinho.';
    }

}
    
$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT id, nome, descricao, preco, imagem, quantidade FROM produtos WHERE id = $id AND disponivel = 1";
$result = $conn->query($sql);
$produto = $result->fetch_assoc();
    
$sql = "SELECT id, nome, descricao, preco, imagem, quantidade FROM produtos WHERE disponivel = 1";
$result_pd = $conn->query($sql);
    
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
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/produtos.css">
</head>
<body>

    <div class="main">

        <?php include '../components/header.php';?>

        <div class="container">
            <div class="product-detail">
                <div class="product-image">
                    <?php if (!empty($produto['imagem'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem de <?= htmlspecialchars($produto['nome']) ?>">
                    <?php else: ?>
                        <span>Imagem indisponível</span>
                    <?php endif; ?>
                </div>
                <div class="prod-info">
                    
                    <div class="produto-menu">                        
                        <div class="name-desc">
                            <h1><?= htmlspecialchars($produto['nome']) ?></h1>
                        </div>
                        <div class="price"><h3>R$<?= number_format($produto['preco'], 2, ',', '.') ?></h3></div>
                        <div class="prod-desc">
                            <p><?= htmlspecialchars($produto['descricao']) ?></p>
                        </div>
                        <?php if ($produto['quantidade'] >= 5): ?>
                            <p class="estoque">Em estoque - <?= $produto['quantidade'] ?></p>
                        <?php elseif($produto['quantidade'] > 0 and $produto['quantidade'] < 5): ?>
                            <p class="estoque">Baixo Estoque - <?= $produto['quantidade'] ?></p>
                        <?php endif; ?>
                        <input type="hidden" name="produto_id" id="prod_id" value="<?= $produto['id'] ?>">
                        <select name="quantidade" id="quantidade">
                            <option value="1">Quantidade</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        
                        <div class="check-menu">
                            
                            <form action="" method="post" id="carrinho">
                                <button type="submit" class="btn ativo btn-produto">Adicionar ao Carrinho</button>
                            </form>
        
                            <form action="comprar.php" method="post" id="comprar">
                                <button type="submit" class="btn ativo btn-produto">Comprar Agora</button>
                            </form>    
                        </div>

                    </div>


                </div>
            </div>
            <hr class="hr-produtos">
            <div class="sugest"><h2>Sugestões</h2></div>
            <div class="product-list">
                    
                    <?php while($produto = $result_pd->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="" class="produto-image">
                        <div class="product-info">
                            <h3 class="name-product"><?= htmlspecialchars($produto['nome']) ?></h3>
                            <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                            <a href="produto.php?id=<?= $produto['id'] ?>" class="btn pd ativo">
                                <p class="text-product">PEDIR</p>
                                <img src="../images/cart.svg" alt="" class="cart-icon">
                            </a>
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

<script src="../js/checkout.js"></script>

</html>
<?php $conn->close(); ?>
