<?php
$host = 'localhost';
$usuario = 'root'; // Seu usuário do MySQL
$senha = ''; // Sua senha do MySQL
$banco = 'pizzaria'; // O nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
// Define o charset para evitar problemas com acentuação
$conn->set_charset("utf8");
?>