<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conectar ao banco de dados
    $conn = new mysqli('localhost', 'vitorcaixeta', 'Vitor0723', 'fazenda');
    
    // Verifique a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verifique o token
    $sql = "SELECT * FROM reset_tokens WHERE token = '$token' AND expira >= NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Formulário para nova senha
        echo '<form action="atualizar_senha.php" method="POST">
                <input type="hidden" name="token" value="' . $token . '">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
                <button type="submit">Redefinir Senha</button>
              </form>';
    } else {
        echo "Token inválido ou expirado.";
    }

    $conn->close();
}
?>
