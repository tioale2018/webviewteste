<?php
session_start();

// if (isset($_SESSION['logado'])) {
//     header("Location: logado.php");
// }
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

<!-- tela de login com bootstrap -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <form action="valida.php" method="GET">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                        <a href="cadastro.php" class="btn btn-primary cadastrar">Cadastrar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="./bootstrap/js/bootstrap.min.js"></script>
<script>
    document.querySelector('.cadastrar').addEventListener('click', function(e) {
        e.preventDefault();
        alert('teste');
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // impede o envio padrão

        const email = document.getElementById("email").value;
        const senha = document.getElementById("senha").value;

        // codifica os dados para inclusão segura na URL
        const query = new URLSearchParams({
            email: email,
            senha: senha
        }).toString();

        // redireciona manualmente para o script PHP
        window.location.href = form.action + "?" + query;
    });
});
</script>

</body>
</html>