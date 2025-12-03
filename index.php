<?php
session_start();
// Credenciais de Admin simples (SUBSTITUA por algo mais seguro)
$admin_usuario = 'admin';
$admin_senha = '12345'; 

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if ($usuario === $admin_usuario && $senha === $admin_senha) {
        $_SESSION['admin_logado'] = true;
        header("Location: pedidos.php"); // Redireciona para a página de pedidos
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

// Se não estiver logado, exibe o formulário
if (!isset($_SESSION['admin_logado'])):
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>

<body>
    <h2>Login do Painel Administrativo</h2>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <form method="POST">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required><br><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br><br>
        <button type="submit" name="login">Entrar</button>
    </form>
</body>

</html>
<?php 
exit;
endif;
?>

<h1>Bem-vindo(a) ao Painel Admin</h1>
<p>Navegue pelos pedidos:</p>
<ul>
    <li><a href="pedidos.php">Ver Todos os Pedidos</a></li>
    <li><a href="?logout=true">Sair</a></li>
</ul>
<?php
// Lógica de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>