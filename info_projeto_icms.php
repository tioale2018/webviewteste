<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Andamento da Proposta</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="./bootstrap/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>

  <main class="container py-3">
    <h1 class="h5 fw-bold mb-3">Andamento da Proposta</h1>

    <!-- Andamento do processo -->
    <div class="card">
      <div class="section-title">Andamento do processo</div>
      <div class="card-body">
        <p>Seu projeto <strong>Aquela Cia 20 anos : Oficina de Criação</strong> foi submetido para análise em <strong>08/10/2024</strong> sob o número <strong>59059</strong>.</p>
        <p>Fase atual: <strong class="text-primary">Avaliação documental</strong></p>
      </div>
    </div>

    <!-- Histórico de diligências -->
    <div class="accordion" id="accordionInfo">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
            Análise Documental
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionInfo">
          <div class="accordion-body">
            <div class="card mb-3">
              <div class="section-title">Histórico de Diligências</div>
              <div class="card-body">

                <!-- Diligência 1 -->
                <div class="mb-4">
                  <h6 class="fw-semibold">Análise Documental</h6>
                  <div class="mb-2"><strong>Data da Resposta:</strong> 15/07/2024</div>
                  <div class="mb-2"><strong>Arquivos enviados:</strong></div>
                  <ul class="mb-3">
                    <li><a href="#" class="file-link"><i class="bi bi-paperclip"></i> Contrato Captação DiversiGames EAD.pdf</a></li>
                    <li><a href="#" class="file-link"><i class="bi bi-paperclip"></i> CONTRATO SOCIAL REGISTRADO SERRA PRODUÇÕES.pdf</a></li>
                    <li><a href="#" class="file-link"><i class="bi bi-paperclip"></i> Planilha Orçamentária Diversigames EAD 2.pdf</a></li>
                    <li><a href="#" class="file-link"><i class="bi bi-paperclip"></i> Plano Metodológico Diversigames EAD Cultura.pdf</a></li>
                  </ul>
                </div>

                <!-- Diligência 2 -->
                <div class="border-top pt-3 mt-3">
                  <div class="mb-2">
                    <strong>Diligência:</strong><br>
                    Prezado proponente,<br>
                    Solicitamos encaminhar currículo da empresa SERRA PRODUÇÕES E CONSULTORIAS LTDA.<br>
                    <small>Att,<br>Lei de Incentivo à Cultura</small>
                  </div>
                  <div class="mb-2"><strong>Data da Diligência:</strong> 16/07/2024</div>
                  <div class="mb-2"><strong>Resposta:</strong><br>
                    Prezados, encaminho o currículo da empresa Serra Produções e Consultorias Ltda.<br>
                    <small>Atenciosamente,</small>
                  </div>
                  <div class="mb-2"><strong>Data da Resposta:</strong> 19/07/2024</div>
                  <div><strong>Arquivo enviado:</strong></div>
                  <ul class="mb-0">
                    <li><a href="#" class="file-link"><i class="bi bi-paperclip"></i> Currículo Serra Produções.pdf</a></li>
                  </ul>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="accordion" id="accordionInfo">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
              Análise Técnica
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionInfo">
            <div class="accordion-body">
              <div class="card mb-3">
                <div class="section-title">Histórico de Diligências</div>
                <div class="card-body">
                  <p class="">Nenhuma diligência.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>