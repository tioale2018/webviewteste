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
  $submetidos = getProjetosSubmetidosUsuario($_SESSION['cpf']);
  $nao_submetidos = getProjetosNaoSubmetidosUsuario($_SESSION['cpf']);
  // print_r($submetidos);
  // die();
  ?>


  <!-- Main Content -->
  <main class="container py-3">

    <!-- Page Title -->
    <h1 class="h5 fw-bold mb-3">Meus Projetos</h1>

    <!-- Tabs as Nav Pills -->
    <ul class="nav nav-pills mb-3" id="tabs" role="tablist">
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link active" id="tab-abertos" data-bs-toggle="pill" data-bs-target="#pane-abertos" type="button" role="tab">Não submetidos</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-submetidos" data-bs-toggle="pill" data-bs-target="#pane-submetidos" type="button" role="tab">Submetidos</button>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="tabsContent">
      <div class="tab-pane fade show active" id="pane-abertos" role="tabpanel">
        <div class="list-group">
          <?php if (empty($nao_submetidos)): ?>
            <div class="list-group-item rounded-3 shadow-sm mb-3">
              <div class="mb-2">
                <p class="text-center text-muted">Nenhum projeto submetido.</p>
              </div>
            </div>
          <?php endif; ?>
          <?php foreach ($nao_submetidos as $projeto_nao_submetido): ?>
            <div class="list-group-item rounded-3 shadow-sm mb-3">
              <div class="mb-2">
                <small class="text-muted">Código:</small>
                <span class="fw-semibold"><?= htmlspecialchars($projeto_nao_submetido['id_project']) ?></span>
              </div>
              <div class="mb-2">
                <small class="text-muted">Projeto:</small>
                <div class="fw-semibold"><?= htmlspecialchars($projeto_nao_submetido['titulo']) ?></div>
              </div>
              <div class="mb-2">
                <small class="text-muted">Oportunidade:</small>
                <div><?= htmlspecialchars($projeto_nao_submetido['titulo_edital']) ?></div>
              </div>
              <div class="mt-2">
                <?php if ($projeto_nao_submetido['totalinscritos'] == 0): ?>
                  <span class="badge bg-danger rounded-pill mb-2">Inscrição Encerrada</span>
                <?php elseif (getProjetoSubmetidoEdital($projeto_nao_submetido['idedital'])): ?>
                  <span class="badge bg-warning rounded-pill mb-2">Outro projeto já foi submetido neste edital</span>
                <?php else: ?>
                  <!-- <a href="info_projeto.php?id=<?= $projeto_nao_submetido['id_project'] ?>" class="btn btn-sm btn-primary w-100 mb-1">Acompanhe seu projeto</a> -->
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        </div>
<?php /*
        <div class="list-group">
          <!-- Example Item -->
          <div class="list-group-item rounded-3 shadow-sm mb-3">
            <div class="mb-2">
              <small class="text-muted">Código:</small>
              <span class="fw-semibold">59059</span>
            </div>
            <div class="mb-2">
              <small class="text-muted">Projeto:</small>
              <div class="fw-semibold">Aquela Cia 20 anos : Oficina de Criação</div>
            </div>
            <div class="mb-2">
              <small class="text-muted">Oportunidade:</small>
              <div>Fluxos Fluminenses</div>
            </div>
            <div class="mt-2 rounded-3" style="background-color: #f7cdd3">
              <div class="p-2">
                <div class="text-center">
                  <span class="fw-semibold fs-6">Pendências:</span>
                </div>
                <ul class="m-1">
                  <li class="fw">Documento de identidade</li>
                  <li class="fw">Comprovante de residência</li>
                  <li class="fw">Certidão de nascimento</li>
                  <li class="fw">Documento de comprovação de renda</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="list-group-item rounded-3 shadow-sm mb-3">
            <div class="mb-2">
              <small class="text-muted">Código:</small>
              <span class="fw-semibold">33333</span>
            </div>
            <div class="mb-2">
              <small class="text-muted">Projeto:</small>
              <div class="fw-semibold">Projeto para Submeter</div>
            </div>
            <div class="mb-2">
              <small class="text-muted">Oportunidade:</small>
              <div>Edital Ágora</div>
            </div>
            <div class="mt-2">
              <a href="#" class="btn btn-sm btn-success w-100 mb-1">Finalizar inscrição</a>
            </div>
          </div>
        </div>
      </div>
<?php */ ?>

      <!-- Other tabs can have placeholder content -->
      <div class="tab-pane fade" id="pane-submetidos" role="tabpanel">
        <div class="list-group">
          <?php if (!empty($submetidos)): ?>
            <?php foreach ($submetidos as $projeto): ?>
              <div class="list-group-item rounded-3 shadow-sm mb-3">
                <div class="mb-2">
                  <small class="text-muted">Código:</small>
                  <span class="fw-semibold"><?= htmlspecialchars($projeto['id_project']) ?></span>
                </div>
                <div class="mb-2">
                  <small class="text-muted">Projeto:</small>
                  <div class="fw-semibold"><?= htmlspecialchars($projeto['titulo']) ?></div>
                </div>
                <div class="mb-2">
                  <small class="text-muted">Oportunidade:</small>
                  <div><?= htmlspecialchars($projeto['titulo_edital']) ?></div>
                </div>
                <div class="mt-2">
                  <!-- <a href="info_projeto.php?id=<?= $projeto['id_project'] ?>" class="btn btn-sm btn-primary w-100 mb-1">Acompanhe seu projeto</a> -->
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="list-group-item rounded-3 shadow-sm mb-3">
              <div class="mb-2">
                <p class="text-center text-muted">Nenhum projeto submetido.</p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
