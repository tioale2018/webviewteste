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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<!-- tela de login com bootstrap -->

<div class="container-fluid vh-100 d-flex justify-content-center align-items-center bg-light">
  <div class="card shadow-sm p-4 w-100" style="max-width: 400px;">
    <div class="text-center mb-3">
      <img src="src/logo.svg" alt="Logo" class="img-fluid" style="height: 100px;">
    </div>
    <h5 class="text-center mb-3 fw-semibold">Acesso ao Sistema</h5>
    <form action="dashboard.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <div class="input-group">
          <span class="input-group-text bg-white">
            <i class="bi bi-envelope"></i>
          </span>
          <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
          <span class="input-group-text bg-white">
            <i class="bi bi-lock"></i>
          </span>
          <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
        </div>
      </div>
      <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
        <div class="alert alert-danger" role="alert">
          E-mail ou senha inválidos!
        </div>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
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