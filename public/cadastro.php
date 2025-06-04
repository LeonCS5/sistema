<?php
// sistema/public/cadastro.php
require '../config.php';



if (is_logged_in()) {
    redirect(is_admin() ? '../admin/index.php' : '../user/index.php');
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $forma_pagamento = trim($_POST['forma_pagamento']);

    $imagem_nome = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . "." . $extensao;
        $destino = "../uploads/" . $imagem_nome;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);
    }
    
    
    if (!empty($login) && !empty($senha) && !empty($nome) && !empty($endereco) && !empty($forma_pagamento)) {
        $conn = connect_db();
        
        // Verificar se o login já existe
        $sql = "SELECT id FROM usuarios WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $erro = 'Este login já está em uso.';
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (login, senha, nome, endereco, forma_pagamento, imagem) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $login, $senha_hash, $nome, $endereco, $forma_pagamento, $imagem_nome);
            
            if ($stmt->execute()) {
                $_SESSION['mensagem'] = 'Cadastro realizado com sucesso! Faça login para continuar.';
                redirect('login.php');
            } else {
                $erro = 'Erro ao cadastrar usuário.';
            }
        }
        
        $conn->close();
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .btn { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .error { color: #e74c3c; margin-bottom: 15px; }
        .login-link { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Cliente</h1>
        
        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <textarea id="endereco" name="endereco" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="forma_pagamento">Forma de Pagamento Preferida:</label>
                <select id="forma_pagamento" name="forma_pagamento" required>
                    <option value="">Selecione...</option>
                    <option value="Cartão">Cartão de Crédito</option>
                    <option value="Boleto">Boleto Bancário</option>
                    <option value="PIX">PIX</option>
                </select>
            </div>

            <div class="form-group">
                <label>Imagem:</label>
                <div id="drop-area">
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                </div>
            </div>
            
            <button type="submit" class="btn">Cadastrar</button>
        </form>
        
        <div class="login-link">
            <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
    </div>
</body>
</html>