<?php
// Inclui meu arquivo de conexão do banco
include 'conexao.php';

// Validação dos dados do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

    $erros = [];

    // Validações
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Um email válido é obrigatório.";
    }

    if (empty($senha)) {
        $erros[] = "A senha é obrigatória.";
    }

    // Se houver erros
    if (!empty($erros)) {
        foreach ($erros as $erro) {
            echo "<p style='color:red;'>$erro</p>";
        }
    } else {
        // Prepara e executa a busca
        $stmt = $conn->prepare("SELECT senha, tipo_usuario FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Verifica se o email existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($senha_hash, $tipo_usuario);
            $stmt->fetch();

            // Verifica a senha
            if (password_verify($senha, $senha_hash)) {
                // Inicia a sessão e armazena os dados do usuário
                session_start();
                $_SESSION['id'] = $email; // ou outro identificador único, como ID
                $_SESSION['tipo_usuario'] = $tipo_usuario;

                echo "<p style='color:green;'>Login realizado com sucesso!</p>";

                // Redireciona com base no tipo de usuário
                if ($tipo_usuario === 'admin') {
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'dashboard.html'; // Redirecione para o painel do administrador
                            }, 2000); // Redireciona após 2 segundos
                          </script>";
                } else {
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'home.html'; // Redirecione para a página do usuário comum
                            }, 2000); // Redireciona após 2 segundos
                          </script>";
                }
            } else {
                echo "<p style='color:red;'>Senha incorreta.</p>";
            }
        } else {
            echo "<p style='color:red;'>Email não encontrado.</p>";
        }

        $stmt->close();
    }
} else {
    echo "<p style='color:red;'>Método de requisição inválido.</p>";
}

$conn->close();
?>
