<?php
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

$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

/*
if (strpos($ua, 'Desenvolve-Mobile') === false) {
  echo "<script>location.href='./erro.php';</script>";
  exit;
}
*/


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
  <title>Login - Sistema de Editais</title>
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
      <!-- <div id="error"></div> -->
       <input type="text" id="token"  hidden>
      <form action="login.php" method="POST">
        <div class="mb-3">
          <label for="documento" class="form-label">CNPJ/CPF</label>
          <div class="input-group">
            <span class="input-group-text bg-white">
              <i class="bi bi-envelope"></i>
            </span>
            <input type="text" name="documento" id="documento" class="form-control" placeholder="Digite seu CNPJ/CPF" required inputmode="numeric" pattern="[0-9]*">
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
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
          <div class="alert alert-danger" role="alert">
            Documento ou senha inválidos!
          </div>
        <?php endif; ?>
        <input type="hidden" name="login" value="1">
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
      <button type="button" class="btn text-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
      Desvincular CNPJ/CPF do aplicativo
      <i class="bi bi-question-circle"></i>
    </button>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Ao confirmar esta operação você deixará de receber notificações do sistema de editais referente ao ultimo CNPJ/CPF vinculado.</p>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="button" id="desvincular" class="btn btn-primary">Confirmar</button>
        
        </div>
      </div>
    </div>
  </div>

  <!-- Script de comunicação com WebView -->

  <script>
    window.receberTokenDoApp = function(token) {
      // alert("📥 Token recebido do app: " + token);

      fetch('buscar-cpf.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            token
          })
        })
        .then(response => response.json())
        .then(res => {
          if (res.status === 'sucesso' && res.cpf) {
            // alert('CPF encontrado: ' + res.cpf);
            document.getElementById('documento').value = res.cpf;
            document.getElementById('token').value = token;
          } else {
            // alert('CPF não encontrado: ' + res.mensagem);
          }
        })
        .catch(err => {
          // const errorDiv = document.getElementById('error');
          // errorDiv.textContent = err;
          // errorDiv.style.display = 'block';
        });
    }
  </script>

  <script>
    $("#desvincular").click(function() {
      var documento = $("#documento").val();
      var token = $("#token").val();
      desvincularToken(token, documento);
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // alert("✅ DOM carregado");
      if (window.ReactNativeWebView) {
        // alert("📱 App detectado");
        window.ReactNativeWebView.postMessage(JSON.stringify({
          tipo: 'pagina',
          pagina: 'login'
        }));
      } else {
        // alert("🌐 Navegador detectado");
      }
    });
  </script>

  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>

</html>