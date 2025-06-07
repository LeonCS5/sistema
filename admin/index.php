<?php
// sistema/admin/index.php
require '../config.php';

//if (!is_admin()) {
//    redirect('../public/index.php');
//}

$conn = connect_db();

// Contar produtos
$sql = "SELECT COUNT(*) AS total FROM produtos";
$result = $conn->query($sql);
$total_produtos = $result->fetch_assoc()['total'];

// Contar pedidos
$sql = "SELECT COUNT(*) AS total FROM pedidos";
$result = $conn->query($sql);
$total_pedidos = $result->fetch_assoc()['total'];

// Últimos pedidos
$sql = "SELECT p.id, p.data_hora, p.status, u.nome AS cliente_nome 
        FROM pedidos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data_hora DESC
        LIMIT 5";
$ultimos_pedidos = $conn->query($sql);

// Produtos com baixo estoque
$sql = "SELECT id,nome, quantidade FROM produtos WHERE quantidade < 10 ORDER BY quantidade ASC LIMIT 5";
$baixo_estoque = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #f9f9f9; padding: 20px; border-radius: 5px; text-align: center; }
        .stat-value { font-size: 2em; font-weight: bold; margin: 10px 0; }
        .dashboard-section { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status { padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .status-recebido { background-color: #3498db; color: white; }
        .status-enviado { background-color: #f39c12; color: white; }
        .status-entregado { background-color: #2ecc71; color: white; }
        .btn { background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #2ecc71; }
        .btn-success:hover { background: #27ae60; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Painel Administrativo</h1>
            <div>
                <a href="produtos.php" class="btn">Gerenciar Produtos</a>
                <a href="pedidos.php" class="btn">Gerenciar Pedidos</a>
                <a href="../logout.php" class="btn btn-danger">Sair</a>
            </div>
        </header>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Produtos</h3>
                <div class="stat-value"><?= $total_produtos ?></div>
                <a href="produtos.php" class="btn">Ver Todos</a>
            </div>
            
            <div class="stat-card">
                <h3>Pedidos</h3>
                <div class="stat-value"><?= $total_pedidos ?></div>
                <a href="pedidos.php" class="btn">Ver Todos</a>
            </div>
        </div>
        
        <div class="dashboard-section">
            <h2>Últimos Pedidos</h2>
            <?php if ($ultimos_pedidos->num_rows > 0): ?>
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
                        <?php while($pedido = $ultimos_pedidos->fetch_assoc()): ?>
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
        
        <div class="dashboard-section">
            <h2>Produtos com Baixo Estoque</h2>
            <?php if ($baixo_estoque->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($produto = $baixo_estoque->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td><a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a></td>

                            
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum produto com estoque baixo.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>