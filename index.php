<?php
// session_start();

if (isset($_SESSION['logado'])) {
    // header("Location: logado.php");
    echo "<script>location.href='./logado.php';</script>";
    exit;
}
/*
// protect.php – inclua no início das suas páginas
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (strpos($ua, 'SophxApp/1.0') === false) {
    // header('Location: ./erro.php');
    echo "<script>location.href='./erro.php';</script>";
    exit;
}
*/
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <style>
        .area-login {
            /* height: 100vh!important; */
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<!-- tela de login com bootstrap -->

<div class="container-fluid vh-100 d-flex justify-content-center align-items-center area-login">
    <div class="row">
        <div class="col-md-12">
            <div class="card vw-100">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <form action="dashboard.php" method="POST">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" required>
                        </div>
                        <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
                            <div class="alert alert-danger" role="alert">
                                E-mail ou senha inválidos!
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./bootstrap/js/bootstrap.min.js"></script>
<script>
    // document.querySelector('.cadastrar').addEventListener('click', function(e) {
    //     e.preventDefault();
    //     location.href = 'logado.php';
    // });
</script>



</body>
</html>