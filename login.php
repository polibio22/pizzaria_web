<?php
session_start();
// Se o usuário já estiver logado, redireciona para a área de pedidos/dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: pedidos.php');
    exit();
}

$mensagem = $_SESSION['login_msg'] ?? '';
unset($_SESSION['login_msg']); // Limpa a mensagem após exibir
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Pizzaria</title>
    <link rel="stylesheet" href="../assets/css/style_admin.css">
</head>

<body>

    <div class="login-container">
        <h2>Acesso Restrito</h2>

        <?php if ($mensagem): ?>
        <p class="mensagem-erro"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form action="processa_login.php" method="POST">
            <label for="login">Login (Usuário):</label>
            <input type="text" id="login" name="login" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" name="entrar">Entrar</button>
        </form>
    </div>

</body>

</html>