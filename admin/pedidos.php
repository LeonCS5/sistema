<?php
// sistema/admin/pedidos.php
require '../config.php';

if (!is_admin()) {
    redirect('../public/index.php');
}

$conn = connect_db();
$sql = "SELECT p.id, p.data_hora, p.status, u.nome AS cliente_nome 
        FROM pedidos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data_hora DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pedidos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status { padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .status-recebido { background-color: #3498db; color: white; }
        .status-enviado { background-color: #f39c12; color: white; }
        .status-entregado { background-color: #2ecc71; color: white; }
        .btn { background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciar Pedidos</h1>
        <a href="index.php" class="btn">&larr; Voltar</a>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Data/Hora</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($pedido = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['data_hora'])) ?></td>
                        <td><?= $pedido['cliente_nome'] ? htmlspecialchars($pedido['cliente_nome']) : 'Cliente não cadastrado' ?></td>
                        <td><span class="status status-<?= strtolower($pedido['status']) ?>"><?= $pedido['status'] ?></span></td>
                        <td><a href="detalhes_pedido.php?id=<?= $pedido['id'] ?>" class="btn">Detalhes</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum pedido encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>