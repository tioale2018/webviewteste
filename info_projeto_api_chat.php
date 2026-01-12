<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat - Desenvolve Cultura</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
    <?php include_once "navbar-bottom.php"; ?>

    <h1 class="h5 fw-bold mb-3" id="editalTitle">Carregando...</h1>

    <!-- Card 1: Support Email -->
    <div class="card mb-3">
      <div class="card-body">
        <p class="mb-0">Dúvidas relacionadas ao edital devem ser encaminhadas para o e-mail <a href="mailto:" id="supportEmail"></a></p>
      </div>
    </div>

    <!-- Card 2: Process Status -->
    <div class="card mb-3">
      <div class="section-title">Andamento do processo</div>
      <div class="card-body">
        <p>Seu projeto <strong id="projectTitle"></strong> foi submetido para análise em <strong id="submissionDate"></strong> sob o número <strong id="projectNumber"></strong>.</p>
        <p class="mb-0">Fase atual: <strong class="text-primary" id="currentPhase"></strong></p>
      </div>
    </div>

    <!-- Card 3: Arquivos Anexados -->
    <div class="card mb-3">
      <div class="section-title">Arquivos Anexados</div>
      <div class="card-body">
        <div class="mb-2">
          <a href="#" class="file-link text-decoration-none">
            <i class="bi bi-plus-circle me-1"></i> Adicionar arquivo
          </a>
        </div>
        <div class="anexos-scroll">
          <ul class="list-group mb-0" id="fileList">
            <!-- Files will be populated by JavaScript -->
          </ul>
        </div>
      </div>
    </div>

    <!-- Card 4: Mensagens -->
    <div class="card mb-3">
      <div class="section-title">Mensagens</div>
      <div class="card-body">
        <div class="chat-history" id="chatMessages">
          <!-- Messages will be populated by JavaScript -->
        </div>

        <div class="form-chat-fixed">
          <form id="chatForm">
            <div class="mb-2">
              <textarea class="form-control" rows="2" placeholder="Escreva sua mensagem..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-send me-1"></i> Enviar
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="mb-4"></div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
    const jwtToken = '<?= $token ?>';
    const projectId = '<?= $_GET["id"] ?? "" ?>';

    $(function() {
      if (!projectId) {
        alert('ID do projeto não informado. Por favor, acesse esta página através da lista de projetos.');
        window.location.href = 'lista_projetos_api.php';
        return;
      }

      $.ajax({
        url: 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api/info_projeto.php?id=' + encodeURIComponent(projectId),
        type: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + jwtToken
        },
        success: function(data) {
          const dados = data.dados;

          // Populate header fields
          $('#editalTitle').text('Inscrição de proposta de projeto para ' + (dados.titulo_edital || ''));
          $('#supportEmail').attr('href', 'mailto:' + (dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br')).text(dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br');

          // Populate process status
          $('#projectTitle').text(dados.titulo || '');
          $('#submissionDate').text(dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : '');
          $('#projectNumber').text(dados.id_project || '');
          $('#currentPhase').text(dados.nomepublico || '');

          // Note: Chat messages and files are currently placeholder data
          // Future: Connect to chat API endpoint to fetch real messages
        },
        error: function(xhr, status, error) {
          console.error('Erro na requisição:', {status: xhr.status, error: error});
          alert('Erro ao carregar os dados do projeto. Para visualizar mais detalhes, entre no Desenvolve Cultura através do nosso site.');
        }
      });

      // Handle form submission (placeholder functionality)
      $('#chatForm').on('submit', function(e) {
        e.preventDefault();
        alert('Funcionalidade de envio de mensagem será implementada em breve.');
      });
    });
  </script>
</body>

</html>
