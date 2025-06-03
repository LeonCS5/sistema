<?php
// sistema/admin/produtos.php
require '../config.php';

if (!is_admin()) {
    redirect('../public/index.php');
}

$conn = connect_db();
$sql = "SELECT * FROM produtos ORDER BY nome";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions { display: flex; gap: 5px; }
        .btn { background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn-success { background: #2ecc71; }
        .btn-success:hover { background: #27ae60; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .disponivel { color: #2ecc71; font-weight: bold; }
        .indisponivel { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gerenciar Produtos</h1>
            <a href="adicionar_produto.php" class="btn btn-success">Adicionar Produto</a>
        </div>
        
        <a href="index.php" class="btn">&larr; Voltar</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Disponibilidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($produto = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $produto['id'] ?></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= $produto['quantidade'] ?></td>
                    <td>
                        <?php if ($produto['disponivel']): ?>
                            <span class="disponivel">Disponível</span>
                        <?php else: ?>
                            <span class="indisponivel">Indisponível</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="editar_produto.php?id=<?= $produto['id'] ?>" class="btn">Editar</a>
                        <a href="excluir_produto.php?id=<?= $produto['id'] ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>