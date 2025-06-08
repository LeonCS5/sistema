<script src="../js/popup.js"></script>
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
                popup_show('Cadastro realizado com sucesso! Faça login para continuar.', "popup-sucess");
                
            } else {
                popup_show('Erro ao cadastrar usuário', "popup-fail");
                
            }
        }
        
        $conn->close();
    } else {
        popup_show('Preencha todos os campos.', "popup-fail");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/cadastro.css">

</head>
<body>
    <div class="main">
        <?php include '../components/header.php';?>
        <div class="container">
            <h1>Cadastro</h1>
            
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
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                
                <div class="form-group options">
                    <label for="forma_pagamento">Forma de Pagamento:</label>
                    <select id="forma_pagamento" name="forma_pagamento" required>
                        <option value="">Selecione...</option>
                        <option value="Cartão">Cartão de Crédito</option>
                        <option value="Boleto">Boleto Bancário</option>
                        <option value="PIX">PIX</option>
                    </select>
                </div>
    
                <div class="form-group options">
                    <label >Imagem:</label>
                    <div id="drop-area">
                        <input type="file" id="imagem" name="imagem" accept="image/*">
                    </div>
                </div>
                <div class="btn-cadastro">
                    <button type="submit" class="btn ativo">Cadastrar</button>
                </div>
            </form>
            
            <div class="login-link">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    
        <?php include '../components/footer.php';?>

    </div>
</body>
</html>