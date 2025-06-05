<?php
// sistema/admin/detalhes_pedido.php
require '../config.php';

if (!is_admin() || !isset($_GET['id'])) {
    redirect('../public/index.php');
}

$conn = connect_db();
$pedido_id = (int)$_GET['id'];

// Obter detalhes do pedido
$sql = "SELECT p.*, u.nome AS cliente_nome 
        FROM pedidos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = $pedido_id";
$result = $conn->query($sql);
$pedido = $result->fetch_assoc();

if (!$pedido) {
    redirect('pedidos.php');
}

// Obter itens do pedido
$sql = "SELECT pi.*, pr.nome AS produto_nome 
        FROM pedido_itens pi 
        JOIN produtos pr ON pi.produto_id = pr.id 
        WHERE pi.pedido_id = $pedido_id";
$itens_result = $conn->query($sql);

$erro = '';
$sucesso = '';

// Atualizar status do pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $novo_status = $_POST['status'];
    $agora = date('Y-m-d H:i:s');
    $sql = "UPDATE pedidos SET status = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $novo_status, $agora, $pedido_id);
    
    if ($stmt->execute()) {
        $sucesso = 'Status do pedido atualizado com sucesso!';
        $pedido['status'] = $novo_status;
        $pedido['status_atualizado_em'] = $agora;
    } else {
        $erro = 'Erro ao atualizar status: ' . $conn->error;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $novo_status = $_POST['status'];

    // Atualiza o status do pedido
    $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $novo_status, $pedido_id);

    if ($stmt->execute()) {
        // Insere o registro no histórico
        $sql_hist = "INSERT INTO pedido_status_historico (pedido_id, status) VALUES (?, ?)";
        $stmt_hist = $conn->prepare($sql_hist);
        $stmt_hist->bind_param('is', $pedido_id, $novo_status);
        $stmt_hist->execute();
        $stmt_hist->close();
    
        $sucesso = 'Status do pedido atualizado com sucesso!';
        $pedido['status'] = $novo_status;
    } else {
        $erro = 'Erro ao atualizar status: ' . $conn->error;
    }
}
// Obter histórico de status
$sql_hist = "SELECT status, data_hora FROM pedido_status_historico WHERE pedido_id = $pedido_id ORDER BY data_hora DESC";
$historico_result = $conn->query($sql_hist);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .order-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status { padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .status-recebido { background-color: #3498db; color: white; }
        .status-enviado { background-color: #f39c12; color: white; }
        .status-entregado { background-color: #2ecc71; color: white; }
        .btn { background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .error { color: #e74c3c; margin-bottom: 15px; }
        .success { color: #2ecc71; margin-bottom: 15px; }
        .status-form { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-header">
            <h1>Detalhes do Pedido #<?= $pedido['id'] ?></h1>
            <a href="pedidos.php" class="btn">&larr; Voltar</a>
        </div>
        
        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="success"><?= $sucesso ?></div>
        <?php endif; ?>
        
        <div>
            <p><strong>Data/Hora:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_hora'])) ?></p>
            <p><strong>Cliente:</strong> <?= $pedido['cliente_nome'] ? htmlspecialchars($pedido['cliente_nome']) : 'Cliente não cadastrado' ?></p>
            <p><strong>Endereço de Entrega:</strong> <?= htmlspecialchars($pedido['endereco']) ?></p>
            <p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($pedido['forma_pagamento']) ?></p>
            <p><strong>Status:</strong> <span class="status status-<?= strtolower($pedido['status']) ?>"><?= $pedido['status'] ?></span></p>
        </div>
        
        <h2>Itens do Pedido</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                while($item = $itens_result->fetch_assoc()): 
                    $subtotal = $item['preco_unitario'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['produto_nome']) ?></td>
                    <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <div class="status-form">
            <h2>Alterar Status do Pedido</h2>
            <form method="post">
                <select name="status">
                    <option value="Recebido" <?= $pedido['status'] == 'Recebido' ? 'selected' : '' ?>>Recebido</option>
                    <option value="Enviado" <?= $pedido['status'] == 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                    <option value="Entregado" <?= $pedido['status'] == 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                </select>
                <button type="submit" class="btn">Atualizar Status</button>
            </form>            

            <?php if (!empty($pedido['status_atualizado_em'])): ?>
            <p><small>Atualizado em: <?= date('d/m/Y H:i', strtotime($pedido['status_atualizado_em'])) ?></small></p>
            <?php endif; ?>
        </div>
        <div class="status-historico">
            <h2>Histórico de Atualizações</h2>
            <?php if ($historico_result && $historico_result->num_rows > 0): ?>
                <ul>
                    <?php while($hist = $historico_result->fetch_assoc()): ?>
                        <li>
                            <strong><?= htmlspecialchars($hist['status']) ?></strong> - 
                            <?= date('d/m/Y H:i:s', strtotime($hist['data_hora'])) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma atualização de status registrada.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>