<?php
session_start();
//verifica GET
if (isset($_GET['email']) && isset($_GET['senha'])) {
    $email = $_GET['email'];
    $senha = $_GET['senha'];

    include_once "conexao.php";

    $sql = "select * from pessoas where email = :email and senha = :senha";
    $stmt = $connPDO->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['logado'] = true;
        $file = "logado.php";
        // header("Location: logado.php");
    } else {
        // header("Location: index.php");
        $file = "index.php";
    }
}

?>

<script>window.location.href = '<?= $file ?>';</script>