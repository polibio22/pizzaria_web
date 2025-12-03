<?php
session_start();
include 'includes/db_conn.php';

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$sql = "SELECT id, nome, descricao, preco FROM pizzas WHERE disponivel = TRUE";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cardápio da LaBelle Pizza</title>
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body>
    <header>
        <h1>Cardápio 📜</h1>
        <nav><a href="carrinho.php">Ver Carrinho (<?php echo count($_SESSION['carrinho']); ?>)</a></nav>
    </header>
    
    <div class="cardapio-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="pizza-item">
                    <h2><?php echo htmlspecialchars($row["nome"]); ?></h2>
                    <p><?php echo htmlspecialchars($row["descricao"]); ?></p>
                    <p class="preco">R$ <?php echo number_format($row["preco"], 2, ',', '.'); ?></p>
                    
                    <form action="carrinho.php" method="POST">
                        <input type="hidden" name="acao" value="adicionar">
                        <input type="hidden" name="pizza_id" value="<?php echo $row["id"]; ?>">
                        <input type="hidden" name="pizza_nome" value="<?php echo htmlspecialchars($row["nome"]); ?>">
                        <input type="hidden" name="pizza_preco" value="<?php echo $row["preco"]; ?>">
                        
                        <label for="qtd_<?php echo $row["id"]; ?>">Qtd:</label>
                        <input type="number" id="qtd_<?php echo $row["id"]; ?>" name="quantidade" value="1" min="1" required>
                        <button type="submit">Adicionar ao Pedido</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<p>Nenhuma pizza disponível no momento.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>