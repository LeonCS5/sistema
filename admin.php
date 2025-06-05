<?php
// sistema/reset_admin.php
require 'config.php';

$conn = connect_db();

// Nova senha (admin123)
$nova_senha = 'admin123';
$hash = password_hash($nova_senha, PASSWORD_DEFAULT);

$sql = "UPDATE usuarios SET senha = ? WHERE login = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $hash);

if ($stmt->execute()) {
    echo "Senha do admin resetada para 'admin123'!";
} else {
    echo "Erro ao resetar senha: " . $conn->error;
}

$conn->close();
?>