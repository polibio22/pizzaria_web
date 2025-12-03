<?php
session_start();
include 'includes/db_conn.php';

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// ------------------------------------
// AÇÃO: ADICIONAR ITEM
// ------------------------------------
if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $id = intval($_POST['pizza_id']);
    $item = [
        'nome' => $_POST['pizza_nome'],
        'preco' => floatval($_POST['pizza_preco']),
        'quantidade' => intval($_POST['quantidade'])
    ];

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] += $item['quantidade'];
    } else {
        $_SESSION['carrinho'][$id] = $item;
    }
    header('Location: cardapio.php'); // Volta para o cardápio após adicionar
    exit;
}

// ------------------------------------
// AÇÃO: REMOVER ITEM
// ------------------------------------
if (isset($_GET['acao']) && $_GET['acao'] === 'remover' && isset($_GET['id'])) {
    $id_remover = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id_remover])) {
        unset($_SESSION['carrinho'][$id_remover]);
    }
    header('Location: carrinho.php');
    exit;
}

// ------------------------------------
// AÇÃO: FINALIZAR PEDIDO (Salva no DB)
// ------------------------------------
if (isset($_POST['acao']) && $_POST['acao'] === 'finalizar' && !empty($_SESSION['carrinho'])) {
    
    $cliente_nome = $conn->real_escape_string($_POST['cliente_nome']);
    $cliente_endereco = $conn->real_escape_string($_POST['cliente_endereco']);
    
    $detalhes_pedido = "";
    $valor_total = 0;

    foreach ($_SESSION['carrinho'] as $item) {
        $subtotal = $item['preco'] * $item['quantidade'];
        $valor_total += $subtotal;
        $detalhes_pedido .= $item['quantidade'] . "x " . $item['nome'] . " (R$ " . number_format($item['preco'], 2, ',', '.') . ")\n";
    }

    // Insere na tabela 'pedidos'
    $sql = "INSERT INTO pedidos (cliente_nome, cliente_endereco, detalhes, valor_total) 
            VALUES ('$cliente_nome', '$cliente_endereco', '$detalhes_pedido', $valor_total)";

    if ($conn->query($sql) === TRUE) {
        // Sucesso: Limpa o carrinho e redireciona
        unset($_SESSION['carrinho']);
        $mensagem = "Obrigado, seu pedido foi realizado com sucesso! Total: R$ " . number_format($valor_total, 2, ',', '.');
        header('Location: cardapio.php?msg=' . urlencode($mensagem));
        exit;
    } else {
        $erro = "Erro ao registrar o pedido: " . $conn->error;
        header('Location: carrinho.php?erro=' . urlencode($erro));
        exit;
    }
}
// ------------------------------------
// VISUALIZAÇÃO DO CARRINHO (HTML)
// ------------------------------------
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link rel="stylesheet" href="includes/styles.css">
</head>

<body>
    <header>
        <h1>Seu Pedido 🛒</h1>
        <nav><a href="cardapio.php">Voltar ao Cardápio</a></nav>
    </header>

    <?php if (empty($_SESSION['carrinho'])): ?>
    <p>Seu carrinho está vazio.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Pizza</th>
                <th>Qtd</th>
                <th>Preço Unit.</th>
                <th>Subtotal</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_geral = 0;
            foreach ($_SESSION['carrinho'] as $id => $item) {
                $subtotal = $item['preco'] * $item['quantidade'];
                $total_geral += $subtotal;
                ?>
            <tr>
                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                <td><?php echo $item['quantidade']; ?></td>
                <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                <td><a href="carrinho.php?acao=remover&id=<?php echo $id; ?>">Remover</a></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <h2>Total a Pagar: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?></h2>

    <hr>

    <h3>Detalhes para Entrega</h3>
    <form action="carrinho.php" method="POST">
        <input type="hidden" name="acao" value="finalizar">
        <label>Nome: <input type="text" name="cliente_nome" required></label><br>
        <label>Endereço: <input type="text" name="cliente_endereco" required></label><br><br>
        <button type="submit">Confirmar e Fazer Pedido</button>
    </form>

    <?php endif; ?>
</body>

</html>