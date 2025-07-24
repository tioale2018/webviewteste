<?php
// session_start();
$cpf = $_SESSION['cpf'] ?? null;
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
  <?php include_once "navbar.php";

  $editais = getEditaisAtivos();

  echo '<main class="container-fluid py-3">
    <h1 class="h5 fw-bold mb-3">Oportunidades</h1>';
  if (empty($editais)) {
    echo '<div class="alert alert-info" role="alert">Nenhum edital ativo no momento.</div>';
  } else {
    echo '<div class="list-group">';
    foreach ($editais as $edital) {
      $status = $edital['totalinscritos'] == '1000' ? 'success' : 'danger';
      $statusText = $edital['totalinscritos'] == '1000' ? 'Inscrições abertas' : 'Inscrições encerradas';
      echo '<div class="list-group-item d-flex flex-column align-items-start">
              <div class="mb-2">
                <span class="fw-semibold">' . htmlspecialchars($edital['titulo']) . '</span>
              </div>
            <span class="badge bg-' . $status . ' rounded-pill my-2">' . $statusText . '</span>
              <div class="d-flex w-100 justify-content-between">
                <small class="text-muted">' . date('d/m/Y', $edital['datacria']) . ' - ' . date('d/m/Y', $edital['datafecha']) . '</small>
             
              </div>
            </div>';
    }
    echo '</div>';
  }

  /*
  ?> o link abaixo fica na LINHA 33 entre o fechamento da div e abaixo do small class="text-muted"
     <a href="info_edital.php?id=' . $edital['id'] . '" class="btn btn-sm btn-primary">Mais detalhes</a>

  ?> 

  <!-- Main Content -->
  <main class="container-fluid py-3">
    <h1 class="h5 fw-bold mb-3">Oportunidades</h1>
    
    <div class="list-group">
      <!-- Opportunity Item -->
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Produção Literária e Projetos Artísticos</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">24/06/2025 - 25/07/2025</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
      <!-- More items below -->
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Feiras Literárias e Projetos de Formação</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">24/06/2025 - 25/07/2025</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Manutenção de Espaços Literários</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">24/06/2025 - 25/07/2025</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Contação de Histórias</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">24/06/2025 - 25/07/2025</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Eleição Assentos Conselho Estadual</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">14/03/2025 - 25/06/2028</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Lei Incentivo à Cultura</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">01/03/2025 - 01/12/2025</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
      <div class="list-group-item d-flex flex-column align-items-start">
        <div class="mb-2">
          <span class="fw-semibold">Mapeamento Cultura Viva RJ</span>
        </div>
        <b><small class="text-success mb-1">Inscrições abertas</small></b>
        <div class="d-flex w-100 justify-content-between">
          <small class="text-muted">18/06/2024 - 09/04/2027</small>
          <a href="#" class="btn btn-sm btn-primary">Mais detalhes</a>
        </div>
      </div>
    </div>
<?php */ ?>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>