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
      <h5 class="text-center mb-3 fw-semibold">Recuperar Senha</h5>
      <div id="error" class="text-center alert alert-danger" role="alert" style="display: none;"></div>
     <div id="success" class="text-center alert alert-success" role="alert" style="display: none;"></div>
       <input type="text" id="token"  hidden>
        <div class="mb-3">
          <label for="documento" class="form-label">CNPJ/CPF</label>
          <div class="input-group">
            <span class="input-group-text bg-white">
              <i class="bi bi-envelope"></i>
            </span>
            <input type="text" name="documento" id="documento" class="form-control" placeholder="Digite seu CNPJ/CPF" required inputmode="numeric" pattern="[0-9]*">
          </div>
        </div>
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
          <div class="alert alert-danger" role="alert">
            Documento ou senha inválidos!
          </div>
        <?php endif; ?>
        <input type="hidden" name="login" value="1">
        <button id="recuperar-senha" class="btn btn-primary w-100">Enviar nova senha</button>
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
            // document.getElementById('token').value = token;
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
    document.getElementById("recuperar-senha").addEventListener("click", function() {
      var documento = document.getElementById("documento").value;
      var token = document.getElementById("token").value;
      if (!documento || !token) {
        const errorDiv = document.getElementById('error');
        errorDiv.textContent = 'Por favor, preencha o CNPJ/CPF.';
        errorDiv.style.display = 'block';
        return; 
      }
      fetch('http://cultura.rj.gov.br/desenvolve-cultura/api/recupera-senha.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          token: token,
          documento: documento
        })
      })
      .then(response => response.json())
      .then(res => {
        if (res.status === 'sucesso') {
          const successDiv = document.getElementById('success');
          successDiv.textContent = res.mensagem;
          successDiv.style.display = 'block';
          document.getElementById('documento').value = '';
      } else {
        const errorDiv = document.getElementById('error');
        errorDiv.textContent = res.mensagem;
        errorDiv.style.display = 'block';
      }
    })
      .catch(err => {
            const errorDiv = document.getElementById('error');
            errorDiv.textContent = err;
            errorDiv.style.display = 'block';
      });

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