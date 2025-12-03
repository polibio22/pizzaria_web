<?php
session_start();
// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: index.php"); // Redireciona para o login
    exit;
}

// Inclui a conexão (assumindo que a conexão do admin está na pasta 'admin' e o db_conn está fora dela)
include '../includes/db_conn.php'; 

// Consulta para buscar todos os pedidos
$sql = "SELECT id, cliente_nome, cliente_endereco, detalhes, valor_total, status, data_pedido FROM pedidos ORDER BY data_pedido DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Pedidos | Painel Admin</title>
</head>

<body>
    <h1>📋 Pedidos Recebidos</h1>
    <p><a href="index.php">Voltar para o Painel</a> | <a href="index.php?logout=true">Sair</a></p>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Endereço</th>
                <th>Detalhes do Pedido</th>
                <th>Total</th>
                <th>Status</th>
                <th>Data/Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['cliente_nome']); ?></td>
                <td><?php echo htmlspecialchars($row['cliente_endereco']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['detalhes'])); ?></td>
                <td>R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>
                <td>
                    **<?php echo htmlspecialchars($row['status']); ?>**
                </td>
                <td><?php echo $row['data_pedido']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>

</html>