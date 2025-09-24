<?php
session_start();
include_once "funcoes.php";

if (!isset($_SESSION['cpf'])) {
    header('Location: login.php');
    exit;
}


$cpf = $_SESSION['cpf'] ?? null;
$id = $_SESSION['id_user'] ?? null;

$payload = [
  'cpf' =>  $cpf,
  'id_user' => $id
];

$secret = getJwtSecret();
$token = generate_jwt($payload, $secret);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alterar Senha</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
    <h1 class="h5 fw-bold mb-3">Alterar Senha</h1>
    
    <div class="card shadow-sm p-4 w-100" style="max-width: 500px; margin: 0 auto;">
      <div id="message" class="alert" style="display: none;" role="alert"></div>
      
      <form id="senha-form">
        <div class="mb-3">
          <label for="senha-atual" class="form-label">Senha Atual</label>
          <div class="input-group">
            <input type="password" class="form-control" id="senha-atual" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('senha-atual')">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="mb-3">
          <label for="confirma-senha-atual" class="form-label">Confirme a Senha Atual</label>
          <div class="input-group">
            <input type="password" class="form-control" id="confirma-senha-atual" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirma-senha-atual')">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <div id="senha-atual-error" class="invalid-feedback" style="display: none;">
            As senhas não coincidem
          </div>
        </div>

        <div class="mb-4">
          <label for="senha-nova" class="form-label">Nova Senha</label>
          <div class="input-group">
            <input type="password" class="form-control" id="senha-nova" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('senha-nova')">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Alterar Senha</button>
      </form>
    </div>
  </main>

  <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
    const jwtToken = '<?= $token ?>';
  </script>
<script>
    function showMessage(message, type = 'success') {
      const messageDiv = $('#message');
      messageDiv.removeClass('alert-success alert-danger')
        .addClass(`alert-${type}`)
        .html(message)
        .show();
      
      setTimeout(() => messageDiv.hide(), 5000);
    }

    function togglePassword(fieldId) {
      const field = document.getElementById(fieldId);
      const button = field.nextElementSibling;
      const icon = button.querySelector('i');
      
      if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    }

    function validateSenhaAtual() {
      const senhaAtual = $('#senha-atual').val();
      const confirmaSenha = $('#confirma-senha-atual').val();
      const errorDiv = $('#senha-atual-error');
      
      if (confirmaSenha && senhaAtual !== confirmaSenha) {
        errorDiv.show();
        return false;
      }
      
      errorDiv.hide();
      return true;
    }

    // Adiciona validação em tempo real
    $('#confirma-senha-atual').on('input', validateSenhaAtual);

    $('#senha-form').on('submit', function(e) {
      e.preventDefault();
      
      if (!validateSenhaAtual()) {
        showMessage('As senhas atuais não coincidem', 'danger');
        return;
      }

      const senhaAtual = $('#senha-atual').val();
      const senhaNova = $('#senha-nova').val();

      if (senhaAtual === senhaNova) {
        showMessage('A nova senha não pode ser igual à senha atual', 'danger');
        return;
      }

      $.ajax({
        url: 'https://cultura.rj.gov.br/desenvolve-cultura/api/alterar-senha.php',
        type: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + jwtToken
        },
        data: JSON.stringify({
          senha_atual: senhaAtual,
          senha_nova: senhaNova
        }),
        success: function(response) {
          if(response.success === false) {
            showMessage(response.mensage, 'danger');
            return;
          } else {
          showMessage('Senha alterada com sucesso!', 'success');
          $('#senha-form')[0].reset();
          }
        },
        error: function(err) {
          let mensagem = 'Erro ao alterar senha. ';
          if (err.responseJSON && err.responseJSON.mensagem) {
            mensagem += err.responseJSON.mensagem;
          } else {
            mensagem += 'Tente novamente mais tarde.';
          }
          showMessage(mensagem, 'danger');
        }
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>