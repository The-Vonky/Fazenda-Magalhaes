<?php
// Configurações de conexão com o banco de dados
$servername = "localhost"; 
$username = "vitorcaixeta"; 
$password = "Vitor0723";
$dbname = "fazenda"; 

// Habilita relatórios de erro
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

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
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Verifica se o email existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($senha_hash);
            $stmt->fetch();

            // Verifica a senha
            if (password_verify($senha, $senha_hash)) {
                echo "<p style='color:green;'>Login realizado com sucesso!</p>";
                
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'home.html'; // Redirecione para a página desejada
                        }, 2000); // Redireciona após 2 segundos
                      </script>";
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
