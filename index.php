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

  if (!isset($data['token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Documento não fornecido']);
    exit;
  }

  $documento = $data['token'];

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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      background-color: #f9fafb;
      font-family: 'Roboto', 'Inter', sans-serif;
      color: #333;
    }

    .navbar {
      border-bottom: 1px solid #e5e7eb;
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
    }

    .navbar-brand {
      font-size: 1.1rem;
      font-weight: 600;
      display: flex;
      align-items: center;
    }

    .navbar .btn {
      background: none;
      border: none;
    }

    #badgeNotificacoes {
      font-size: 0.75rem;
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      background-color: #ffffff;
    }

    .card h5 {
      font-weight: 600;
      color: #111827;
    }

    #listaVinculados .border {
      border: 1px solid #e5e7eb !important;
      border-radius: 0.75rem;
      background-color: #ffffff;
      transition: box-shadow 0.2s ease;
    }

    #listaVinculados .border:hover {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .btn-outline-primary {
      border-radius: 0.75rem;
      background-color: #1cb3bb;
      border-color: #ffffff;
      color: #ffffff;
      font-weight: 500;
    }

    .btn-outline-primary:hover {
      background-color: #1cb3bb;
      color: white;
    }

    .btn-outline-primary:active {
      background-color: #72d2e0ff;
      color: white;
    }

    .modal-content {
      border-radius: 1rem;
    }

    .modal-title {
      font-weight: 600;
    }

    .btn {
      border-radius: 0.5rem;
    }

    .text-muted {
      font-size: 0.92rem;
      color: #6b7280 !important;
    }

    #listaNotificacoes .border {
      background-color: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      font-size: 0.9rem;
      transition: box-shadow 0.2s ease;
    }

    #listaNotificacoes .border:hover {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid justify-content-between">
      <span class="navbar-brand mb-0 h1"><img src="src/logo.svg" alt="Logo" style="height:40px;"> <span class="fw-bold">Desenvolve Cultura Mobile</span></span>
      <button id="btnNotificacoes" class="btn position-relative" type="button">
        <i class="bi bi-bell fs-4"></i>
        <span id="badgeNotificacoes" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">0</span>
      </button>
    </div>
  </nav>

  <div class="container-fluid d-flex flex-column align-items-center justify-content-center" style="min-height:80vh;">
    <input type="text" value="fTa0cCK3QK-9OjPlD21dZK:APA91bFPWM8lX4VsAZd0NcnIu2J0LkStdvst6e5T814g-hoqmxdTJsYJf06ea1LhQs3NlF2_JGhKqMJvT5YROZlWM04Ab7k8HGpdricBifEx06Zm4KqCuig" id="token" hidden>
    <div class="card shadow-sm p-4 w-100 mb-4" style="max-width: 400px;">
      <h5 class="text-center mb-3 fw-semibold">Contas vinculadas</h5>
      <div id="listaVinculados" class="mb-3 overflow-auto" style="max-height: 300px;">
      </div>
      <a class="btn btn-outline-primary w-100 mb-2" href="index2_api.php">
        <i class="bi bi-plus-circle"></i> Adicionar conta
      </a>
    </div>
  </div>

  <!-- Modal Notificações -->
  <div class="modal fade" id="modalNotificacoes" tabindex="-1" aria-labelledby="modalNotificacoesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNotificacoesLabel">Notificações</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="listaNotificacoes">
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Desvincular -->
  <div class="modal fade" id="modalDesvincular" tabindex="-1" aria-labelledby="modalDesvincularLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDesvincularLabel">Desvincular conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalDesvincularBody">
          Tem certeza que deseja desvincular este CNPJ/CPF?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="btnConfirmarDesvincular">Desvincular</button>
        </div>
      </div>
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

  <script>
    // Top-level holder for JWT used in Authorization headers
    let tokenJwt = null;

    // Função para gerar o token JWT (a própria página responde ao POST com o JWT)
    async function generateJWTToken(token) {
      const response = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          token: token
        })
      });
      const data = await response.json();
      return data.token;
    }

    // Recebe token do app (ex: via WebView) e inicializa fluxo
    window.receberTokenDoApp = async function(token) {
      document.getElementById('token').value = token;
      localStorage.setItem('token', token);
      try {
        tokenJwt = await generateJWTToken(token);
      } catch (e) {
        console.error('Erro ao gerar JWT a partir do token do app:', e);
        tokenJwt = null;
      }
      carregarVinculados(token);
      carregarNotificacoes(false);
    }

    // Inicialização ao carregar a página: tenta recuperar token do localStorage
    document.addEventListener('DOMContentLoaded', async () => {
      const token = localStorage.getItem('token');
      if (token) {
        document.getElementById('token').value = token;
        try {
          tokenJwt = await generateJWTToken(token);
        } catch (e) {
          console.error('Erro ao gerar JWT no boot:', e);
          tokenJwt = null;
        }
        carregarVinculados(token);
        carregarNotificacoes(false);
      }
    });


    // Carregar lista de CNPJs/CPFs vinculados
    function carregarVinculados(token) {
      const tokenFinal = typeof token === 'string' && token.length ? token : localStorage.getItem('token');

      if (!tokenFinal) {
        document.getElementById('listaVinculados').innerHTML = `<div class="text-danger text-center">Erro ao carregar dados.</div>`;
        return;
      }

      const headers = { 'Content-Type': 'application/json' };
      if (tokenJwt) headers['Authorization'] = 'Bearer ' + tokenJwt;
      fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/buscar-cpf-vinculados.php', {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ token: tokenFinal })
      })
        .then(r => r.json())
        .then(res => {
          const lista = document.getElementById('listaVinculados');
          lista.innerHTML = '';
          if (res.status === 'sucesso' && Array.isArray(res.cpfs) && res.cpfs.length) {
            res.cpfs.forEach((item) => {
              const div = document.createElement('div');
              div.className = 'd-flex align-items-center justify-content-between border rounded p-2 mb-2 bg-white';
              div.innerHTML = `
          <a href="#" onclick="abrirLoginComToken('${item.cpf}')" class="text-decoration-none text-dark fw-semibold flex-grow-1">
            ${formatarDocumento(item.cpf)}
          </a>
          <button class="btn btn-sm btn-outline-danger ms-2" style="border: none; outline: none;" title="Desvincular" onclick="abrirModalDesvincular('${item.cpf}')">
            <i class="bi bi-trash"></i>
          </button>`;
              lista.appendChild(div);
            });
          } else {
            lista.innerHTML = '<div class="text-center text-muted">Nenhum CNPJ/CPF vinculado.</div>';
          }
        })
        .catch((err) => {
          document.getElementById('listaVinculados').innerHTML = `<div class="text-danger text-center">Erro ao carregar dados.</div>`;
        });
    }


    // Abrir modal de desvincular
    let cpfParaDesvincular = '';

    function abrirModalDesvincular(cpf) {
      cpfParaDesvincular = cpf;
      document.getElementById('modalDesvincularBody').textContent = `Tem certeza que deseja desvincular o CNPJ/CPF ${cpf}?`;
      var modal = new bootstrap.Modal(document.getElementById('modalDesvincular'));
      modal.show();
    }

    // Confirmar desvincular
    document.getElementById('btnConfirmarDesvincular').addEventListener('click', function() {
      const token = document.getElementById('token').value;
      if (!cpfParaDesvincular || !token) return;
      const headersDesv = { 'Content-Type': 'application/json' };
      if (tokenJwt) headersDesv['Authorization'] = 'Bearer ' + tokenJwt;
      fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/desvincular-cpf.php', {
          method: 'POST',
          headers: headersDesv,
          body: JSON.stringify({ token, cpf: cpfParaDesvincular })
        })
        .then(r => r.json())
        .then(res => {
          if (res.status === 'sucesso') {
            carregarVinculados(token);
            carregarNotificacoes(false);
          }
          var modal = bootstrap.Modal.getInstance(document.getElementById('modalDesvincular'));
          modal.hide();
        });
    });









    // Carregar notificações e abrir modal
    document.getElementById('btnNotificacoes').addEventListener('click', function() {
      carregarNotificacoes(true);
    });

    function carregarNotificacoes(abrirModal = false) {
      const token = document.getElementById('token').value;
      const headersNot = { 'Content-Type': 'application/json' };
      if (tokenJwt) headersNot['Authorization'] = 'Bearer ' + tokenJwt;
      fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/buscar-notificacoes.php', {
          method: 'POST',
          headers: headersNot,
          body: JSON.stringify({ token })
        })
        .then(r => r.json())
        .then(res => {
          const badge = document.getElementById('badgeNotificacoes');
          const lista = document.getElementById('listaNotificacoes');
          let naoLidas = 0;
          lista.innerHTML = '';

          const notificacoes = Array.isArray(res.notificacoes) ? res.notificacoes : res.mensagem;

          if (res.status === 'sucesso' && Array.isArray(notificacoes) && notificacoes.length) {
            notificacoes.forEach((item) => {
              const lida = parseInt(item.lido ?? item.lida ?? 0);
              if (lida === 1) naoLidas++;

              const dataFormatada = formatarDataBrasileira(item.enviado_em);

              const div = document.createElement('div');
              div.className = 'border rounded p-3 mb-2 shadow-sm';
              div.innerHTML = `
  <div class="d-flex justify-content-between align-items-start">
    <div class="flex-grow-1">
      <div class="fw-semibold mb-1">${formatarDocumento(item.cpf)}</div>
      <div class="text-muted small">Projeto: <strong>${item.projeto}</strong></div>
      <div class="text-muted small">Mensagem: ${item.mensagem}</div>
    </div>
  </div>
  <div class="d-flex justify-content-between align-items-center mt-2">
    <div class="text-muted" style="font-size: 0.65rem;">${dataFormatada}</div>
    <button class="btn btn-sm p-1 px-2 style="border: none; outline: none;"" title="Marcar como lida" onclick="marcarComoLida('${item.id}')">
      <i class="bi bi-trash-fill text-danger"></i>
    </button>
  </div>`;

              lista.appendChild(div);
            });

            badge.textContent = naoLidas;
            badge.style.display = naoLidas > 0 ? 'inline-block' : 'none';
          } else {
            lista.innerHTML = '<div class="text-center text-muted">Nenhuma mensagem.</div>';
            badge.textContent = 0;
            badge.style.display = 'none';
          }

          if (abrirModal) {
            var modal = new bootstrap.Modal(document.getElementById('modalNotificacoes'));
            modal.show();
          }
        })
        .catch(() => {
          const badge = document.getElementById('badgeNotificacoes');
          badge.textContent = 0;
          badge.style.display = 'none';
          document.getElementById('listaNotificacoes').innerHTML = '<div class="text-center text-danger">Erro ao carregar notificações.</div>';
        });
    }


    function marcarComoLida(id) {
      const token = document.getElementById('token').value;
      const headersMark = { 'Content-Type': 'application/json' };
      if (tokenJwt) headersMark['Authorization'] = 'Bearer ' + tokenJwt;
      fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/marcar-notificacao.php', {
         method: 'POST',
         headers: headersMark,
         body: JSON.stringify({ id, token })
        })
        .then(r => r.json())
        .then(res => {
          if (res.status === 'sucesso') {
            carregarNotificacoes();
          } else {
            alert('Erro ao marcar como lida: ' + res.mensagem);
          }
        });
    }






    // Helper: ao clicar em um CNPJ/CPF vinculado, salvar no localStorage e abrir a tela de login externa
    function abrirLoginComToken(cpf) {
      try {
        localStorage.setItem('selected_cpf', cpf);
        // manter token da app se existir
        const token = localStorage.getItem('token');
        // redireciona para index2_api.php (a página vai ler selected_cpf)
        window.location.href = 'index2_api.php';
      } catch (e) {
        console.error('Erro ao iniciar login com token:', e);
      }
    }
  </script>




  <script>
    // Comunicação com WebView e inicialização
    document.addEventListener("DOMContentLoaded", function() {
      if (window.ReactNativeWebView) {
        window.ReactNativeWebView.postMessage(JSON.stringify({
          tipo: 'pagina',
          pagina: 'login'
        }));
      }
      if (token) {
        carregarVinculados(token);
        carregarNotificacoes(false);
      }
    });
  </script>
  <script>
    function formatarDocumento(documento) {
      // Remove any non-numeric characters
      documento = documento.replace(/\D/g, '');

      if (documento.length === 11) {
        // Format CPF
        return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
      } else if (documento.length === 14) {
        // Format CNPJ
        return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
      }

      return documento; // Return as is if length doesn't match CPF or CNPJ
    }
  </script>

  <script>
    function formatarDataBrasileira(dataISO) {
      const data = new Date(dataISO);
      const dia = String(data.getDate()).padStart(2, '0');
      const mes = String(data.getMonth() + 1).padStart(2, '0');
      const ano = data.getFullYear();
      const horas = String(data.getHours()).padStart(2, '0');
      const minutos = String(data.getMinutes()).padStart(2, '0');
      return `${dia}/${mes}/${ano} ${horas}:${minutos}`;
    }
  </script>

  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>

</html>