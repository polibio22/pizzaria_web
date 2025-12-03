<?php
include 'includes/db_conn.php';

// Lógica para atualizar o status do pedido
if (isset($_POST['update_status']) && isset($_POST['pedido_id']) && isset($_POST['novo_status'])) {
    $id = intval($_POST['pedido_id']);
    $status = $conn->real_escape_string($_POST['novo_status']);
    
    $sql_update = "UPDATE pedidos SET status = '$status' WHERE id = $id";
    $conn->query($sql_update);
    // Redireciona para evitar reenvio do formulário
    header('Location: admin.php?status_updated=true'); 
    exit;
}

// Busca todos os pedidos, ordenados do mais recente (NOVO) para o mais antigo
$sql = "SELECT * FROM pedidos ORDER BY data_pedido DESC, status ASC";
$result = $conn->query($sql);

// Opções de status
$status_options = ['Novo', 'Em Preparação', 'A Caminho', 'Entregue', 'Cancelado'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Painel Admin - Pedidos</title>
    <link rel="stylesheet" href="includes/styles.css">
</head>

<body>
    <header>
        <h1>Painel de Administração - Pedidos Recebidos 👩‍💼</h1>
    </header>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Endereço</th>
                <th>Detalhes do Pedido</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo (new DateTime($row['data_pedido']))->format('d/m H:i'); ?></td>
                <td><?php echo htmlspecialchars($row['cliente_nome']); ?></td>
                <td><?php echo htmlspecialchars($row['cliente_endereco']); ?></td>
                <td class="detalhes-pedido"><?php echo nl2br(htmlspecialchars($row['detalhes'])); ?></td>
                <td>R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>

                <form action="admin.php" method="POST">
                    <input type="hidden" name="pedido_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="update_status" value="1">
                    <td>
                        <select name="novo_status"
                            class="status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                            <?php foreach ($status_options as $status): ?>
                            <option value="<?php echo $status; ?>"
                                <?php echo ($row['status'] == $status) ? 'selected' : ''; ?>>
                                <?php echo $status; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><button type="submit">Atualizar</button></td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Nenhum pedido novo no momento. 😴</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>

</html>