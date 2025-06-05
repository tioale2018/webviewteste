<?php
// header("Location: logado.php");

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
        } else {
            $destino = "index.php?erro=1";
        }
    }
}

//echo $destino;

// header("Location: $destino");
echo "<script>location.href='$destino';</script>";
?>
