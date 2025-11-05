<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Oportunidade - Produção Literária</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>
<body class="bg-light">

  <?php include_once "navbar.php";
        $id = $_GET['id'] ?? null;
        $dados = getDadosEdital($id);
        $legislacoes = getLegislacoesEdital($id);
        $publicacoes = getPublicacoesEdital($id);
  
  ?>

  <!-- Main Content -->
  <main class="container py-3">

    <!-- Page Title -->
    <h1 class="h5 fw-bold mb-3"><?php echo $dados['titulo']; ?></h1>

    <!-- Tabs as Nav Pills -->
    <ul class="nav nav-pills mb-3" id="tabs" role="tablist">
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link active" id="tab-inscricoes" data-bs-toggle="pill" data-bs-target="#pane-inscricoes" type="button" role="tab">Inscrições</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-legislacoes" data-bs-toggle="pill" data-bs-target="#pane-legislacoes" type="button" role="tab">Legislações</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-manual" data-bs-toggle="pill" data-bs-target="#pane-manual" type="button" role="tab">Manual</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-publicacoes" data-bs-toggle="pill" data-bs-target="#pane-publicacoes" type="button" role="tab">Publicações</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-faq" data-bs-toggle="pill" data-bs-target="#pane-faq" type="button" role="tab">Perguntas Frequentes</button>
      </li>
    </ul>

 

    <!-- Tab Content -->
    <div class="tab-content" id="tabsContent">
      <div class="tab-pane fade show active" id="pane-inscricoes" role="tabpanel">
        <?php if ($dados): ?>
          <p class="text-justify"><?php echo $dados['descricao']; ?></p>
        <?php endif; ?>
        <!-- Accordion -->
        <div class="accordion" id="accordionInfo">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                Sistema de Inscrições
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionInfo">
              <div class="accordion-body">
                  <?php if ($dados): ?>
               <p class="text-justify"><?php echo $dados['visaogeral']; ?></p>
                <br>
                <?php endif; ?>
                Informações e esclarecimentos de dúvidas: <?php echo $dados['linha1'] ? "<a href='mailto:" . htmlspecialchars($dados['linha1']) . "'>" . htmlspecialchars($dados['linha1']) . "</a>" : ""; ?>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                Passo a passo
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionInfo">
              <div class="accordion-body">
               <?php $etapas = explode("\n", $dados['etapas']); ?>
                <ol class="list-group list-group-numbered">
                  <?php foreach ($etapas as $etapa): ?>
                    <li class="list-group-item"><?php echo trim($etapa); ?></li>
                  <?php endforeach; ?>
                </ol>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                Anexos
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionInfo">
              <div class="accordion-body">
                <?php echo getAnexosObrigatorios($id); ?>
                <?php echo getAnexosOpcionais($id); ?>
              </div>
            </div>
          </div>
          <?php   if(!empty($dados['anexos'])): ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                Portfólio de Atuação Cultural
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionInfo">
              <div class="accordion-body p-3">
                <?php $obs = explode("\n", $dados['anexos']);
                echo "<p=text-justify>" . $dados['anexos_intro']. "</p>"; ?>
               <ol class="list-group list-group-numbered">
                  <?php
                   $i = 0;
                   foreach ($obs as $info)
                                      {
                                        if(preg_match("/\S+/",$info)==0)
                                        {
                                          echo "</ol><ul style='margin-top:1rem'>";
                                        }
                                        if($i>=0 && preg_match("/\S+/",$info)>0)
                                        {
                                    ?>
                                      <li class="list-group-item"><?=$info?></li>
                                    <?php
                                        }
                                        $i++;
                                      }
                                    ?>
                </ul>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Action Button -->
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-success btn-lg">Iniciar processo de inscrição</button>
        </div>
      </div>
      <!-- Other tabs can have placeholder content -->
      <div class="tab-pane fade" id="pane-legislacoes" role="tabpanel">
        <?php if ($legislacoes): ?>
          <ul class="list-group">
            <?php foreach ($legislacoes as $legislacao): ?>
              <li class="list-group-item"><a href="<?= $legislacao['nomearquivo'] ?>" target="_blank"><?= $legislacao['nome'] ?></a> – <?= $legislacao['textoapoio'] ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: echo "Acesse o Desenvolve Cultura através do computador para visualizar as legislações."; endif ?>
      </div>
      <div class="tab-pane fade" id="pane-publicacoes" role="tabpanel">
        <?php if ($publicacoes): ?>
          <ul class="list-group">
            <?php foreach ($publicacoes as $publicacao): ?>
              <li class="list-group-item"><a href="<?= $publicacao['nomearquivo'] ?>" target="_blank"><?= $publicacao['nome'] ?></a> – <?= $publicacao['textoapoio'] ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: echo "Acesse o Desenvolve Cultura através do computador para visualizar as publicações."; endif ?>
      </div>
      <div class="tab-pane fade" id="pane-manual" role="tabpanel">Clique aqui para fazer o download do <a href="http://desenvolvecultura.rj.gov.br" target="_blank">Manual do Proponente</a></div>
      <div class="tab-pane fade" id="pane-faq" role="tabpanel">Clique <a target="_blank" href="http://desenvolvecultura.rj.gov.br/">aqui para acessar a Perguntas Frequentes</a></div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
