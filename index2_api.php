<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';

if ($server_name == 'webview.sophx.com.br' || $server_name == 'homologa.sophx.com.br') {
  $dotenv = Dotenv::createImmutable('/home/comsophxadm');
  $dotenv->load();
} else {
  $dotenv = Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

isset($_GET['documento']) ? $documento = $_GET['documento'] : $documento = '';

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

// Verifica se é uma requisição AJAX para gerar token
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    
    // Pega o conteúdo JSON enviado
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!isset($data['documento'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Documento não fornecido']);
        exit;
    }
    
    $documento = preg_replace('/[^\d]/', '', $data['documento']);
    
    if (empty($documento)) {
        http_response_code(400);
        echo json_encode(['error' => 'Documento inválido']);
        exit;
    }
    
    try {
        $payload = [
            'documento' => $documento,
            'timestamp' => time()
        ];
        
        $secret = getJwtSecret();
        $tokenJwt = generate_jwt($payload, $secret);
        
        echo json_encode(['token' => $tokenJwt]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao gerar token']);
        exit;
    }
}

$secret = getJwtSecret();
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
      <div id="error" class="text-center alert alert-danger" role="alert" style="display: none;"></div>
     <div id="success" class="text-center alert alert-success" role="alert" style="display: none;"></div>
        <input type="text" id="token" name="token" value="<?php echo $token; ?>"  hidden>
        <div class="mb-3">
          <label for="documento" class="form-label">CNPJ/CPF</label>
          <div class="input-group">
            <span class="input-group-text bg-white">
              <i class="bi bi-envelope"></i>
            </span>
            <input type="text" name="documento" id="documento" class="form-control" placeholder="Digite seu CNPJ/CPF" required inputmode="numeric" pattern="[0-9]*" value="<?php echo $documento; ?>">
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
        <button id="realizar-login" class="btn btn-primary w-100">Entrar</button>
      <a href="./recuperar_senha.php" class="d-block text-center mt-3 text-decoration-none"> Esqueceu sua senha?</a>
    
    </div>
  </div>


 

  <!-- Script de comunicação com WebView -->

  <script>

    var token = localStorage.getItem('token');
    document.getElementById('token').value = token;
    
    // window.receberTokenDoApp = function(token) {
      // document.getElementById('token').value = token;
     

      /*
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
        }); */
    // }
  </script>

 <script>
    // Função para gerar o token JWT
    async function generateJWTToken(documento) {
      const response = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ documento: documento })
      });
      const data = await response.json();
      return data.token;
    }

    // Função para limpar mensagens
    function clearMessages() {
      document.getElementById('error').style.display = 'none';
      document.getElementById('success').style.display = 'none';
    }

    // Função para mostrar erro
    function showError(message) {
      const errorDiv = document.getElementById('error');
      errorDiv.textContent = message;
      errorDiv.style.display = 'block';
    }

    // Função para mostrar sucesso
    function showSuccess(message) {
      const successDiv = document.getElementById('success');
      successDiv.textContent = message;
      successDiv.style.display = 'block';
    }

    document.getElementById("realizar-login").addEventListener("click", async function() {
      clearMessages();
      const documento = document.getElementById("documento").value.replace(/[^\d]/g, '');
      const senha = document.getElementById("senha").value;
      const token = document.getElementById("token").value; // Token do app
      
      if (!documento) {
        showError('Por favor, preencha o CNPJ/CPF.');
        return;
      }

      try {
        // Gera o token JWT para autenticação com a API
        const tokenJwt = await generateJWTToken(documento);
        
        const response = await fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + tokenJwt
          },
          body: JSON.stringify({
            documento: documento,
            senha: senha,
            token: token // Token do app
          })
        });

        const data = await response.json();
        
        if (data.success) {
          // Salva a sessão localmente
          const sessaoResponse = await fetch('salvar_sessao.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user: data.user,
              token: token // Token do app
            })
          });

          const sessaoResult = await sessaoResponse.json();
          
          if (sessaoResult.success) {
            showSuccess('Login realizado com sucesso!');
            window.location.href = 'lista_editais_api.php';
          } else {
            showError('Erro ao salvar sessão. Tente novamente.');
          }
        } else {
          showError(data.message || 'Usuário ou senha inválidos');
        }
      } catch (err) {
        showError('Erro ao processar sua solicitação. Tente novamente.');
        console.error(err);
      }
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