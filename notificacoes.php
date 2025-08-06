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

  <?php include_once "navbar.php";?>

  <main class="container py-3"> 
  <h1 class="h5 fw-bold mb-3">Notificações</h1>
  <div class="list-group">
    <div id="listaNotificacoes"></div>
  </div> <!-- fechamento correto -->


 <script>
       window.receberTokenDoApp = function(token) {
        // alert("📥 Token recebido do app: " + token);
        token;
       }


    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    cpf = "<?php echo isset($_SESSION['cpf']) ? $_SESSION['cpf'] : ''; ?>";
    token = "<?php echo isset($_SESSION['token']) ? $_SESSION['token'] : ''; ?>";
// const token = "fTa0cCK3QK-9OjPlD21dZK:APA91bFPWM8lX4VsAZd0NcnIu2J0LkStdvst6e5T814g-hoqmxdTJsYJf06ea1LhQs3NlF2_JGhKqMJvT5YROZlWM04Ab7k8HGpdricBifEx06Zm4KqCuig";
    function carregarNotificacoes() {
      const lista = document.getElementById('listaNotificacoes');
      fetch('buscar-notificacoes-cpf.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cpf })
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
                  <div class="text-muted small">Mensagem: ${mensagem.mensagem}</div>
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
          lista.innerHTML = '<div class="text-center text-muted">Nenhuma mensagem.</div>';
        }
      })
      .catch((err) => {
        console.error(err);
        lista.innerHTML = '<div class="text-center text-danger">Erro ao carregar notificações.</div>';
      });
    }

    function marcarComoLida(id) {
      fetch('marcar-notificacao.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, cpf, token })
      })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'sucesso') {
          carregarNotificacoes();
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