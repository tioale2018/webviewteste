<?php
session_start();
include_once "conexao.php";

// Evita erros se não houver envio de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valida os dados recebidos
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $sql = "SELECT * FROM pessoas WHERE email = :email AND senha = :senha";
        $stmt = $connPDO->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['logado'] = true;
            header("Location: logado.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    } else {
        // dados inválidos
        header("Location: index.php");
        exit;
    }
} else {
    // acesso direto via GET
    header("Location: index.php");
    exit;
}
?>
