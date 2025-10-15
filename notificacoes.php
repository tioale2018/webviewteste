<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">

  <?php include_once "navbar.php";
  
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: index.php");
  exit;
}

$cpf = $_SESSION['cpf'] ?? null;
$id = $_SESSION['id_user'] ?? null;
$token_celular = $_SESSION['token'] ?? null;

$payload = [
  'documento' => $cpf,
  'id_user' => $id,
  'token_celular' => $token_celular
];

$secret = getJwtSecret();
$token = generate_jwt($payload, $secret);

  ?>

  <main class="container py-3"> 
  <h1 class="h5 fw-bold mb-3">Notificações</h1>
  <div class="list-group">
    <div id="listaNotificacoes"></div>
  </div> <!-- fechamento correto -->



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const jwtToken = '<?= $token ?>';
  </script>
  <script>
    token = "<?php echo isset($_SESSION['token']) ? $_SESSION['token'] : ''; ?>";
    let contador = 0;
    function carregarNotificacoes() {
      const lista = document.getElementById('listaNotificacoes');
      fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/buscar-notificacoes-cpf.php', {
      method: 'POST',
      headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwtToken 
                }
      })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'sucesso') {
          lista.innerHTML = '';
          res.mensagem.forEach((mensagem) => {
            const dataFormatada = formatarDataBrasileira(mensagem.enviado_em);
            const div = document.createElement('div');
            div.className = 'border rounded p-3 mb-2 shadow-sm';
            div.innerHTML = `
              <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                  <div class="fw-semibold mb-1">Projeto:${mensagem.projeto}</div>
                  <div class="text-muted small"></div>
                  <div class="text-muted small">${mensagem.mensagem}</div>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="text-muted" style="font-size: 0.65rem;">${dataFormatada}</div>
                <button class="btn btn-sm p-1 px-2" style="border: none; outline: none;" title="Marcar como lida" onclick="marcarComoLida('${mensagem.id}')">
                  <i class="bi bi-trash-fill text-danger"></i>
                </button>
              </div>
            `;
            lista.appendChild(div);
          });
        } else {
          lista.innerHTML = `<div class="list-group-item rounded-3 shadow-sm mb-3">
              <div class="mb-2"><br>
              <p class="text-center text-muted">Nenhuma notificação.</p>
              </div>
            </div>`;
        }
      })
      .catch((err) => {
        console.error(err);
        lista.innerHTML = '<div class="text-center text-danger">Erro ao carregar notificações.</div>';
      });
    }

    function marcarComoLida(id) {
      const token = "<?php echo isset($_SESSION['token']) ? $_SESSION['token'] : ''; ?>";
      fetch('https://cultura.rj.gov.br/desenvolve-cultura/api/marcar-notificacao.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, token })
      })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'sucesso') {
          carregarNotificacoes();
        } else {
          alert('Erro ao marcar como lida: ' + JSON.stringify(res));
        }
      });
    }

    document.addEventListener('DOMContentLoaded', carregarNotificacoes);
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
</main>
</body>

</html>