<?php
session_start();

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'vitorcaixeta', 'Vitor0723', 'fazenda');

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Prevenir SQL Injection
$email = $conn->real_escape_string($email);

// Consultar o usuário
$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Verificar a senha
    if (password_verify($senha, $usuario['senha'])) {
        // Senha correta
        $_SESSION['usuario_id'] = $usuario['id'];
        echo "Login bem-sucedido! Bem-vindo, " . $usuario['email'];
        // Redirecionar ou mostrar outra página
    } else {
        // Senha incorreta
        echo "Senha incorreta.";
    }
} else {
    // Usuário não encontrado
    echo "E-mail não encontrado.";
}

$conn->close();
?>
