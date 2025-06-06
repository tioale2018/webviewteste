<?php
session_start();

if (!isset($_SESSION['logado'])) {
    // header("Location: index.php");
    echo "<script>location.href='./index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
</head>
<body>

<?php 
include_once "conexao.php";
include_once "menu.php"; 

$sql = "select * from pessoas";

$result = $connPDO->query($sql);
//fetch all
$row = $result->fetchAll();


?>
<div class="container-fluid">
 
    <div class="row">
        <div class="col-12">
            <h1>Teste conteúdo HTML</h1>
            <p><?= $menu ?></p>
        </div>
        <div class="col-12">
            <a href="sair.php" class="btn btn-danger logout">Sair</a>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Telefone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row as $linha) { ?>
                    <tr>
                        <th scope="row"><?= $linha['id'] ?></th>
                        <td><?= $linha['nome'] ?></td>
                        <td><?= $linha['telefone'] ?></td>
                    </tr>
                    <?php } ?>
        </div>
    </div>
</div>


<script src="./bootstrap/js/bootstrap.min.js"></script>
<script src="./js/script.js"></script>
</body>
</html>