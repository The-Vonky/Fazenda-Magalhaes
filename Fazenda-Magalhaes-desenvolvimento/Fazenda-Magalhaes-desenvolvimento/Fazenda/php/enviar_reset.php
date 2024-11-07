<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $conn = new mysqli('localhost', 'vitorcaixeta', 'Vitor0723', 'fazenda');

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        $conn->query("INSERT INTO reset_tokens (email, token, expira) VALUES ('$email', '$token', '$expira')");

        $link = "http://seusite.com/redefinir_senha.php?token=$token";
        $mensagem = "Clique aqui para redefinir sua senha: $link";
        mail($email, "Redefinição de Senha", $mensagem);
        
        echo "Um link de redefinição foi enviado para seu e-mail.";
    } else {
        echo "E-mail não encontrado.";
    }

    $conn->close();
}
?>
