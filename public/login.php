<?php
// sistema/public/login.php
require '../config.php';

if (is_logged_in()) {
    redirect(is_admin() ? '../admin/index.php' : '../user/index.php');
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];
    
    if (!empty($login) && !empty($senha)) {
        $conn = connect_db();
        $sql = "SELECT id, senha,nome, is_admin, imagem FROM usuarios WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($usuario = $result->fetch_assoc()) {
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['imagem'] = $usuario['imagem'];
                $_SESSION['is_admin'] = (bool)$usuario['is_admin'];
                
                if ($usuario['is_admin']) {
                    redirect('../admin/index.php');
                } else {
                    redirect('index.php');
                }
            }
        }
        
        $erro = 'Login ou senha incorretos.';
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
    <title>Login</title>

    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="main">
        <?php include '../components/header.php';?>
        <div class="container">
            <h1>Login</h1>
            
            <?php if ($erro): ?>
                <div class="error"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="login">Login:</label>
                    <input type="text" id="login" name="login" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="btn-login">

                    <button type="submit" class="btn ativo">Entrar</button>
                </div>
            </form>
            
            <div class="register-link">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
        <div>

            <?php include '../components/footer.php';?>
        </div>
    </div>

</body>
</html>