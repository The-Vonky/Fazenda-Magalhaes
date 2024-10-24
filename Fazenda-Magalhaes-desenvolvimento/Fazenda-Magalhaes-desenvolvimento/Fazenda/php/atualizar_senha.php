<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    // Conectar ao banco de dados
    $conn = new mysqli('localhost', 'vitorcaixeta', 'Vitor0723', 'fazenda');
    
    // Verifique a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verifique o token
    $sql = "SELECT email FROM reset_tokens WHERE token = '$token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];
        
        // Atualizar a senha do usuário
        $conn->query("UPDATE usuarios SET senha = '$nova_senha' WHERE email = '$email'");
        
        // Remover o token
        $conn->query("DELETE FROM reset_tokens WHERE token = '$token'");
        
        echo "Senha atualizada com sucesso!";
    } else {
        echo "Token inválido.";
    }

    $conn->close();
}
?>
