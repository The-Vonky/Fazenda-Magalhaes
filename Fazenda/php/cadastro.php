<?php
// Inicia a sessão
session_start(); 

// Conexão com o banco de dados
$host = 'localhost';
$db = 'fazenda';
$user = 'vitorcaixeta';
$pass = 'Vitor0723';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['senha'];
    $user_type = $_POST['tipo_usuario'];

    // Validação simples
    $errors = [];
    
    // Verifica se o email é válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }

    // Verifica se a senha tem pelo menos 6 caracteres
    if (strlen($password) < 6) {
        $errors[] = "A senha deve ter pelo menos 6 caracteres.";
    }

    // Verifica se o tipo de usuário é válido
    if (!in_array($user_type, ['admin', 'user'])) {
        $errors[] = "Tipo de usuário inválido.";
    }

    // Verifica se já existe um usuário com o mesmo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = "Já existe um usuário cadastrado com este email.";
    }

    // Se não houver erros, prossegue com o cadastro
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password, user_type) VALUES (:email, :password, :user_type)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':tipo_usuario', $user_type);
        $stmt->execute();

        // Redireciona ou exibe mensagem de sucesso
        echo "Cadastro realizado com sucesso!";
        header('Location: login.html'); // Redirecionar para a página de login
        exit();
    }
}
?>