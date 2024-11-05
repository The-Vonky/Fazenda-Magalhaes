<?php
// Inclui meu arquivo de conexão do banco
include 'conexao.php';

// Validação dos dados do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    $confirmar_senha = isset($_POST['confirmar_senha']) ? trim($_POST['confirmar_senha']) : '';
    $tipo_usuario = isset($_POST['tipo_usuario']) ? trim($_POST['tipo_usuario']) : '';

    $erros = [];

    // Validações
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Um email válido é obrigatório.";
    }

    if (empty($senha)) {
        $erros[] = "A senha é obrigatória.";
    }

    if ($senha !== $confirmar_senha) {
        $erros[] = "As senhas não correspondem.";
    }

    // Verifica se o email já está cadastrado
    if (empty($erros)) {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erros[] = "O email já está cadastrado.";
        }
        $stmt->close();
    }

    // Se houver erros
    if (!empty($erros)) {
        foreach ($erros as $erro) {
            echo "<p style='color:red;'>$erro</p>";
        }
    } else {
        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Prepara e executa a inserção
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo_usuario);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Cadastro realizado com sucesso!</p>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.html';
                    }, 2000); // Redireciona após 2 segundos
                </script>";
        } else {
            echo "<p style='color:red;'>Erro ao cadastrar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
    
    
} else {
    echo "<p style='color:red;'>Método de requisição inválido.</p>";
}

$conn->close();
?>
