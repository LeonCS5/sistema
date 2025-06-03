<?php
// sistema/admin/adicionar_produto.php
require '../config.php';

if (!is_admin()) {
    redirect('../public/index.php');
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = (float)$_POST['preco'];
    $quantidade = (int)$_POST['quantidade'];
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;

    $imagem_nome = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . "." . $extensao;
        $destino = "../uploads/" . $imagem_nome;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);
    }

    if (!empty($nome) && $preco > 0 && $quantidade >= 0) {
        $conn = connect_db();
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade, disponivel, imagem) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdiss', $nome, $descricao, $preco, $quantidade, $disponivel, $imagem_nome);

        if ($stmt->execute()) {
            $sucesso = 'Produto adicionado com sucesso!';
            $nome = $descricao = '';
            $preco = $quantidade = 0;
            $disponivel = 1;
        } else {
            $erro = 'Erro ao adicionar produto: ' . $conn->error;
        }

        $conn->close();
    } else {
        $erro = 'Preencha todos os campos obrigatórios corretamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 80%; max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .error { color: #e74c3c; margin-bottom: 15px; }
        .success { color: #2ecc71; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .checkbox-group { display: flex; align-items: center; }
        .checkbox-group input { width: auto; margin-right: 10px; }
        .btn { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        #drop-area {
            border: 2px dashed #ccc; padding: 10px; text-align: center;
            cursor: pointer; background-color: #fafafa; margin-bottom: 10px;
        }
        #drop-area:hover { background: #eee; }
        #preview { max-width: 100%; margin-top: 10px; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adicionar Novo Produto</h1>
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
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao"><?= htmlspecialchars($descricao ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="preco">Preço (R$):</label>
                <input type="number" id="preco" name="preco" step="0.01" min="0.01" value="<?= $preco ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade em Estoque:</label>
                <input type="number" id="quantidade" name="quantidade" min="0" value="<?= $quantidade ?? '' ?>" required>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="disponivel" name="disponivel" value="1" <?= ($disponivel ?? 1) ? 'checked' : '' ?>>
                <label for="disponivel">Disponível para venda</label>
            </div>

            <div class="form-group">
                <label>Imagem do Produto:</label>
                <div id="drop-area">
                    Arraste a imagem aqui ou clique para escolher
                    <input type="file" id="imagem" name="imagem" accept="image/*" style="display: none;">
                    <img id="preview" src="#" alt="Prévia da imagem">
                </div>
            </div>

            <button type="submit" class="btn">Adicionar Produto</button>
        </form>
    </div>

    <script>
        const dropArea = document.getElementById('drop-area');
        const inputFile = document.getElementById('imagem');
        const preview = document.getElementById('preview');

        dropArea.addEventListener('click', () => inputFile.click());

        inputFile.addEventListener('change', () => {
            const file = inputFile.files[0];
            previewFile(file);
        });

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.style.background = '#eee';
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.style.background = '';
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.style.background = '';
            const file = e.dataTransfer.files[0];
            inputFile.files = e.dataTransfer.files;
            previewFile(file);
        });

        function previewFile(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
