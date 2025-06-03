<?php
// sistema/admin/editar_produto.php
require '../config.php';

if (!is_admin() || !isset($_GET['id'])) {
    redirect('../public/index.php');
}

$conn = connect_db();
$id = (int)$_GET['id'];

// Obter dados do produto
$sql = "SELECT * FROM produtos WHERE id = $id";
$result = $conn->query($sql);
$produto = $result->fetch_assoc();

if (!$produto) {
    redirect('produtos.php');
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = (float)$_POST['preco'];
    $quantidade = (int)$_POST['quantidade'];
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;
    $imagem = $produto['imagem']; // imagem antiga

    if (!empty($_FILES['imagem']['name'])) {
        $nomeImagem = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $caminhoImagem = '../uploads/' . $nomeImagem;
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
            $imagem = $nomeImagem;
        } else {
            $erro = 'Falha ao enviar a imagem.';
        }
    }

    if (!empty($nome) && $preco > 0 && $quantidade >= 0 && !$erro) {
        $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, quantidade = ?, disponivel = ?, imagem = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdissi', $nome, $descricao, $preco, $quantidade, $disponivel, $imagem, $id);

        if ($stmt->execute()) {
            $sucesso = 'Produto atualizado com sucesso!';
            $produto['nome'] = $nome;
            $produto['descricao'] = $descricao;
            $produto['preco'] = $preco;
            $produto['quantidade'] = $quantidade;
            $produto['disponivel'] = $disponivel;
            $produto['imagem'] = $imagem;
        } else {
            $erro = 'Erro ao atualizar produto: ' . $conn->error;
        }
    } else if (!$erro) {
        $erro = 'Preencha todos os campos obrigatórios corretamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .error { color: #e74c3c; margin-bottom: 15px; }
        .success { color: #2ecc71; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .checkbox-group { display: flex; align-items: center; }
        .checkbox-group input { width: auto; margin-right: 10px; }
        .btn { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Produto</h1>
        <a href="produtos.php" class="btn">&larr; Voltar</a>
        
        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="success"><?= $sucesso ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="preco">Preço (R$):</label>
                <input type="number" id="preco" name="preco" step="0.01" min="0.01" value="<?= $produto['preco'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="quantidade">Quantidade em Estoque:</label>
                <input type="number" id="quantidade" name="quantidade" min="0" value="<?= $produto['quantidade'] ?>" required>
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="disponivel" name="disponivel" value="1" <?= $produto['disponivel'] ? 'checked' : '' ?>>
                <label for="disponivel">Disponível para venda</label>
            </div>

            <div class="form-group">
                <label for="imagem">Imagem do Produto:</label>
                <?php if (!empty($produto['imagem'])): ?>
                    <p><img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do Produto" style="max-width: 200px;"></p>
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>
            
            <button type="submit" class="btn">Atualizar Produto</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
