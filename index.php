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
                    <form action="https://webview.sophx.com.br/valida.php" method="POST">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" required>
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
        location.href = 'logado.php';
    });
</script>

<script>
    /*
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const senha = document.getElementById("senha").value;

        const formData = new FormData();
        formData.append("email", email);
        formData.append("senha", senha);

        // Cria um novo formulário e submete numa nova aba/janela (funciona melhor em WebViews)
        const tempForm = document.createElement("form");
        tempForm.method = "POST";
        tempForm.action = "https://webview.sophx.com.br/valida.php";
        tempForm.style.display = "none";

        for (const [key, value] of formData.entries()) {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = key;
            input.value = value;
            tempForm.appendChild(input);
        }

        document.body.appendChild(tempForm);

        // Simula um clique real
        setTimeout(() => {
            tempForm.submit();
        }, 100); // atraso mínimo para renderização
    });
});
*/
</script>


</body>
</html>