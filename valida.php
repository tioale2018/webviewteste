<?php
header("Location: logado.php");
/*
session_start();
include_once "conexao.php";

$destino = "index.php"; // padrão: volta pro login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $destino = "logado.php";
        }
    }
}


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redirecionando...</title>
    <script>
        // Redireciona de forma confiável via JavaScript
        window.onload = function () {
            window.location.href = "<?= $destino ?>";
        };
    </script>
</head>
<body>
    <p>Redirecionando... Se não for redirecionado para: <?= $destino ?>, <a href="<?= $destino ?>">clique aqui</a>.</p>
</body>
</html>

*/
?>