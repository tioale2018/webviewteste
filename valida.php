<?php
session_start();
//verifica POST
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

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


echo "vai para $file";
?>


<!-- <script>window.location.href = '<?= $file ?>';</script> -->