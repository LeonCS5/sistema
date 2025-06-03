<?php
// sistema/public/checkout.php
require '../config.php';

if (empty($_SESSION['carrinho'])) {
    redirect('carrinho.php');
}

$conn = connect_db();

// Se o usuário estiver logado, preencher automaticamente
$usuario = null;
if (is_logged_in() && !is_admin()) {
    $sql = "SELECT nome, endereco, forma_pagamento FROM usuarios WHERE id = " . $_SESSION['user_id'];
    $result = $conn->query($sql);
    $usuario = $result->fetch_assoc();
}

// Processar o pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $forma_pagamento = trim($_POST['forma_pagamento']);
    $usuario_id = is_logged_in() ? $_SESSION['user_id'] : null;
    
    // Validar dados
    if (empty($nome) || empty($endereco) || empty($forma_pagamento)) {
        $_SESSION['erro'] = 'Preencha todos os campos obrigatórios.';
    } else {
        // Iniciar transação
        $conn->begin_transaction();
        
        try {
            // Criar pedido
            $sql = "INSERT INTO pedidos (usuario_id, endereco, forma_pagamento) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iss', $usuario_id, $endereco, $forma_pagamento);
            $stmt->execute();
            $pedido_id = $conn->insert_id;
            
            // Adicionar itens do pedido
            foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
                // Obter informações do produto
                $sql = "SELECT preco, quantidade FROM produtos WHERE id = $produto_id FOR UPDATE";
                $result = $conn->query($sql);
                $produto = $result->fetch_assoc();
                
                // Verificar disponibilidade
                if (!$produto || $produto['quantidade'] < $quantidade) {
                    throw new Exception("Produto ID $produto_id não disponível na quantidade solicitada.");
                }
                
                // Atualizar estoque
                $nova_quantidade = $produto['quantidade'] - $quantidade;
                $sql = "UPDATE produtos SET quantidade = $nova_quantidade WHERE id = $produto_id";
                $conn->query($sql);
                
                // Adicionar item ao pedido
                $sql = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $preco = $produto['preco'];
                $stmt->bind_param('iiid', $pedido_id, $produto_id, $quantidade, $preco);
                $stmt->execute();
            }
            
            $conn->commit();
            unset($_SESSION['carrinho']);
            $_SESSION['mensagem'] = 'Pedido realizado com sucesso!';
            
            // Salvar dados em cookies para próximas compras
            if (!is_logged_in()) {
                $dados_cliente = [
                    'nome' => $nome,
                    'endereco' => $endereco,
                    'forma_pagamento' => $forma_pagamento
                ];
                setcookie('dados_cliente', json_encode($dados_cliente), time() + (86400 * 30), "/");
            }
            
            // Redirecionar conforme tipo de usuário
            if (is_logged_in()) {
                redirect('../user/pedidos.php');
            } else {
                redirect('index.php');
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['erro'] = 'Erro ao processar pedido: ' . $e->getMessage();
        }
    }
}

// Se houver cookie, preencher com dados do cookie
if (!$usuario && isset($_COOKIE['dados_cliente'])) {
    $dados_cliente = json_decode($_COOKIE['dados_cliente'], true);
    $usuario = [
        'nome' => $dados_cliente['nome'] ?? '',
        'endereco' => $dados_cliente['endereco'] ?? '',
        'forma_pagamento' => $dados_cliente['forma_pagamento'] ?? ''
    ];
} elseif (!$usuario) {
    $usuario = [
        'nome' => '',
        'endereco' => '',
        'forma_pagamento' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        form { display: grid; gap: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .btn { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn-success { background: #2ecc71; }
        .btn-success:hover { background: #27ae60; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 3px; }
        .error { background: #f8d7da; color: #721c24; }
        .cart-summary { background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .cart-item { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #eee; }
        .cart-total { font-weight: bold; font-size: 1.2em; text-align: right; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Finalizar Pedido</h1>
        
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="message error"><?= $_SESSION['erro'] ?></div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        
        <div class="cart-summary">
            <h3>Resumo do Pedido</h3>
            <?php 
            $total = 0;
            foreach ($_SESSION['carrinho'] as $produto_id => $quantidade):
                $sql = "SELECT nome, preco FROM produtos WHERE id = $produto_id";
                $result = $conn->query($sql);
                if ($produto = $result->fetch_assoc()):
                    $subtotal = $produto['preco'] * $quantidade;
                    $total += $subtotal;
            ?>
                <div class="cart-item">
                    <span><?= htmlspecialchars($produto['nome']) ?> (x<?= $quantidade ?>)</span>
                    <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
            <div class="cart-total">Total: R$ <?= number_format($total, 2, ',', '.') ?></div>
        </div>
        
        <form method="post">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>
            
            <div>
                <label for="endereco">Endereço de Entrega:</label>
                <textarea id="endereco" name="endereco" required><?= htmlspecialchars($usuario['endereco']) ?></textarea>
            </div>
            
            <div>
                <label for="forma_pagamento">Forma de Pagamento:</label>
                <select id="forma_pagamento" name="forma_pagamento" required>
                    <option value="">Selecione...</option>
                    <option value="Cartão" <?= $usuario['forma_pagamento'] == 'Cartão' ? 'selected' : '' ?>>Cartão de Crédito</option>
                    <option value="Boleto" <?= $usuario['forma_pagamento'] == 'Boleto' ? 'selected' : '' ?>>Boleto Bancário</option>
                    <option value="PIX" <?= $usuario['forma_pagamento'] == 'PIX' ? 'selected' : '' ?>>PIX</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Concluir Pedido</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>