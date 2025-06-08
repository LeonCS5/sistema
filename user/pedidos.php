<?php
// sistema/user/pedidos.php
require '../config.php';

if (!is_logged_in() || is_admin()) {
    redirect('../public/index.php');
}

$conn = connect_db();
$sql = "SELECT p.id, p.data_hora, p.status, SUM(pi.quantidade * pi.preco_unitario) AS total 
        FROM pedidos p
        JOIN pedido_itens pi ON p.id = pi.pedido_id
        WHERE p.usuario_id = " . $_SESSION['user_id'] . "
        GROUP BY p.id
        ORDER BY p.data_hora DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link rel="icon" href="../images/icone_galopes.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/pedidos.css">
</head>
<body>
    <div class="main">
        <?php include '../components/header.php';?>
        
        <div class="container">
            <h1 class="pedidos-title">Meus Pedidos</h1>
            
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Data/Hora</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pedido = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $pedido['id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($pedido['data_hora'])) ?></td>
                            <td><span class="status status-<?= strtolower($pedido['status']) ?>"><?= $pedido['status'] ?></span></td>
                            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                            <td><a href="detalhes_pedido.php?id=<?= $pedido['id'] ?>" class="btn">Detalhes</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum pedido encontrado.</p>
            <?php endif; ?>
        </div>

        <div>
            <?php include '../components/footer.php';?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>