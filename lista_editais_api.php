<?php
include_once "funcoes.php";

$cpf = $_SESSION['cpf'] ?? null;
$id = $_SESSION['id_user'] ?? null;

$payload = [
    'cpf' => $cpf,
    'id_user' => $id
];

$secret = getJwtSecret();
$token = generate_jwt($payload, $secret);

//    <a href="info_edital.php?id=${edital.id}" class="btn btn-sm btn-primary">Mais detalhes</a>
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Oportunidades - Desenvolve Cultura</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>
<body class="bg-light">
  <?php if ($cpf): ?>
    <script>
      window.ReactNativeWebView?.postMessage(JSON.stringify({
        tipo: 'autenticacao',
        cpf: '<?= $cpf ?>'
      }));
    </script>
  <?php endif; ?>
  <?php include_once "navbar.php"; ?>
  <main class="container-fluid py-3">
    <h1 class="h5 fw-bold mb-3">Oportunidades</h1>
    <div id="editais-list"></div>
  </main>
   <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
        const jwtToken = '<?= $token ?>';
    </script>
    <script>
        $(function() {
            $.ajax({
                url: 'http://192.168.2.15/desenvolve-cultura/api/editais_abertos.php',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwtToken 
                },
                beforeSend: function() {
                    $('#editais-list').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status">');
                },
                success: function(editais) {
      if (!editais.length) {
        $('#editais-list').html('<div class="alert alert-info" role="alert">Nenhum edital ativo no momento.</div>');
        return;
      }
      let html = '<div class="list-group">';
      editais.forEach(function(edital) {
        let status = edital.totalinscritos == '1000' ? 'success' : 'danger';
        let statusText = edital.totalinscritos == '1000' ? 'Inscrições abertas' : 'Inscrições encerradas';
        let dataCriacao = new Date(edital.datacria * 1000);
        let dataFechamento = new Date(edital.datafecha * 1000);
        let dataCriacaoStr = dataCriacao.toLocaleDateString('pt-BR');
        let dataFechamentoStr = dataFechamento.toLocaleDateString('pt-BR');
        html += `<div class="list-group-item d-flex flex-column align-items-start">
          <div class="mb-2">
            <span class="fw-semibold">${edital.titulo}</span>
          </div>
          <span class="badge bg-${status} rounded-pill my-2">${statusText}</span>
          <div class="d-flex w-100 justify-content-between">
            <small class="text-muted">${dataCriacaoStr} - ${dataFechamentoStr}</small>
            <a href="http://cultura.rj.gov.br/" class="btn btn-sm btn-primary">Inscrever-se</a>
            <a href="info_edital_api.php?id=${edital.id}" class="btn btn-sm btn-primary">Mais detalhes</a>
          </div>
        </div>`;
        
      });
      html += '</div>';
      $('#editais-list').html(html);
    }, error: function(err) {
                    const htmlErro = '<div class="alert alert-danger" role="alert">Erro ao carregar os dados. Tente novamente mais tarde.</div>';
                    $('#cadastro-card').html(htmlErro);
                    console.error('Erro na requisição:', err);
                }
            });
  });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
