<?php
// session_start();
require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';

if ($server_name == 'webview.sophx.com.br') {
  $dotenv = Dotenv::createImmutable('/home/comsophxadm');
  $dotenv->load();
} else {
  $dotenv = Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

if (isset($_SESSION['loggedin'])) {
  echo "<script>location.href='./lista_editais.php';</script>";
  exit;
}

include_once "conexao.php";
include_once "funcoes.php";
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
  <div class="container-fluid vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="card shadow-sm p-4 w-100" style="max-width: 400px;">
      <div class="text-center mb-3">
        <img src="src/logo.svg" alt="Logo" class="img-fluid" style="height: 100px;">
      </div>
      <h5 class="text-center mb-3 fw-semibold">Acesso ao Sistema</h5>

      <!-- MOSTRAR TOKEN RECEBIDO AQUI -->
      <div id="token-recebido" class="alert alert-info" role="alert"></div>

      <form action="login.php" method="POST">
        <div class="mb-3">
          <label for="documento" class="form-label">CNPJ/CPF</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
            <input type="number" name="documento" id="documento" class="form-control" placeholder="Digite seu CNPJ/CPF" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
          </div>
        </div>
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
          <div class="alert alert-danger" role="alert">
            Documento ou senha inválidos!
          </div>
        <?php endif; ?>
        <input type="hidden" name="login" value="1">
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
  </div>

  <script>
    if (navigator.userAgent.includes('Desenvolve-Mobile')) {
         let tokenDiv = document.getElementById('token-recebido');
            tokenDiv.textContent = "Token não recebido antes do listener message: " + token;
            tokenDiv.classList.remove('d-none');
      document.addEventListener('message', function(event) {
        let tokenDiv = document.getElementById('token-recebido');
            tokenDiv.textContent = "Token não recebido depois do listener message: " + token;
            tokenDiv.classList.remove('d-none');
        try {
          const data = JSON.parse(event.data);

          if (data.tipo === 'token') {
            const token = data.token;

            // Exibir token visualmente
            let tokenDiv = document.getElementById('token-recebido');
            tokenDiv.textContent = "Token recebido: " + token;
            tokenDiv.classList.remove('d-none');

            // Buscar CPF com esse token
            fetch('buscar-cpf.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ token })
            })
            .then(response => response.json())
            .then(res => {
              if (res.status === 'sucesso' && res.cpf) {
                document.getElementById('documento').value = res.cpf;
              } else {
                tokenDiv.textContent += "\nCPF não encontrado: " + res.mensagem;
              }
            })
            .catch(err => {
              tokenDiv.textContent += "\nErro ao buscar CPF: " + err;
            });
          }
        } catch (e) {
          const tokenDiv = document.getElementById('token-recebido');
          tokenDiv.textContent = "Erro ao interpretar mensagem: " + e;
          tokenDiv.classList.remove('d-none');
        }
      });
    }
  </script>

  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
