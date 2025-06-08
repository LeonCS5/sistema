<?php
// sistema/user/detalhes_pedido.php
require '../config.php';

if (!is_logged_in() || is_admin() || !isset($_GET['id'])) {
    redirect('../public/index.php');
}

$conn = connect_db();
$pedido_id = (int)$_GET['id'];

// Verificar se o pedido pertence ao usuário
$sql = "SELECT p.* FROM pedidos p 
        WHERE p.id = $pedido_id AND p.usuario_id = " . $_SESSION['user_id'];
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

// Historico de status
$sql_hist = "SELECT status, data_hora FROM pedido_status_historico WHERE pedido_id = ? ORDER BY data_hora ASC";
$stmt = $conn->prepare($sql_hist);
$stmt->bind_param('i', $pedido_id);
$stmt->execute();
$historico_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido</title>
    
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/detalhes_pedido.css">
</head>
<body>
    <div class="main">
        <?php include '../components/header.php';?>

        <div class="container">
            <div class="order-header">
                <h1>Detalhes do Pedido #<?= $pedido['id'] ?></h1>
                <a href="pedidos.php" class="btn">Voltar</a>
            </div>
            
            <div class="pedido-info">
                <p><strong>Data/Hora:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_hora'])) ?></p>
                <p><strong>Endereço de Entrega:</strong> <?= htmlspecialchars($pedido['endereco']) ?></p>
                <p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($pedido['forma_pagamento']) ?></p>
                <p><strong>Status:</strong> <span class="status status-<?= strtolower($pedido['status']) ?>"><?= $pedido['status'] ?></span></p>
            </div>
            
            <h2 class="detalhes-pedido">Itens do Pedido</h2>
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
            <h3 class="status-pedidos">Status do Pedido</h3>
            <?php if ($historico_result && $historico_result->num_rows > 0): ?>
                <ul>
                    <?php while($hist = $historico_result->fetch_assoc()): ?>
                        <li class="sts">
                            <strong><?= htmlspecialchars($hist['status']) ?></strong> - 
                            <?= date('d/m/Y H:i:s', strtotime($hist['data_hora'])) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="sem-status">O status do pedido ainda não foi atualizado.</p>
            <?php endif; ?>
        </div>

        <div>
            <?php include '../components/footer.php';?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>