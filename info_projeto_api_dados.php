<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dados do Projeto - Desenvolve Cultura</title>
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

    <!-- Card 3: Dados do Proponente -->
    <div class="card mb-3">
      <div class="section-title">Dados do Proponente</div>
      <div class="card-body">
        <div class="info-row">
          <div class="info-label">Nome Fantasia:</div>
          <div class="info-value"><b id="nomefantasia"></b></div>
        </div>
        <div class="info-row">
          <div class="info-label">CNPJ/CPF:</div>
          <div class="info-value"><b id="cnpjcpf"></b></div>
        </div>
        <div class="info-row">
          <div class="info-label">E-mail:</div>
          <div class="info-value"><b id="email"></b></div>
        </div>
        <div class="info-row">
          <div class="info-label">Telefone:</div>
          <div class="info-value"><b id="telefone"></b></div>
        </div>
      </div>
    </div>

    <!-- Card 4: Informações Socioeconômicas -->
    <div class="card mb-3" id="cardSocioeconomicas" style="display:none;">
      <div class="section-title">Informações Socioeconômicas</div>
      <div class="card-body" id="socioeconomicasBody">
      </div>
    </div>

    <!-- Card 5: Dados da Proposta Cultural -->
    <div class="card mb-3">
      <div class="section-title">Dados da Proposta Cultural</div>
      <div class="card-body">
        <div class="info-row" id="rowCategoria" style="display:none;">
          <div class="info-label">Categoria:</div>
          <div class="info-value"><b id="categoria"></b></div>
        </div>
        <div class="info-row" id="rowAreaCultural" style="display:none;">
          <div class="info-label">Área Cultural:</div>
          <div class="info-value"><b id="areaCultural"></b></div>
        </div>
        <div class="info-row" id="rowConcorrencia" style="display:none;">
          <div class="info-label">Concorrência:</div>
          <div class="info-value"><b id="concorrencia"></b></div>
        </div>
        <div class="info-row" id="rowNomeProjeto" style="display:none;">
          <div class="info-label">Nome do Projeto:</div>
          <div class="info-value"><b id="nomeProjeto"></b></div>
        </div>
        <div class="info-row" id="rowDataRealizacao" style="display:none;">
          <div class="info-label">Data de Realização:</div>
          <div class="info-value"><b id="dataRealizacao"></b></div>
        </div>
        <div class="info-row" id="rowLocal" style="display:none;">
          <div class="info-label">Local:</div>
          <div class="info-value"><b id="local"></b></div>
        </div>
      </div>
    </div>

    <!-- Card 6: Equipe -->
    <div class="card mb-3" id="cardEquipe" style="display:none;">
      <div class="section-title">Equipe</div>
      <div class="card-body">
        <ul class="list-group" id="equipeList">
        </ul>
      </div>
    </div>

    <!-- Card 7: Anexos -->
    <div class="card mb-3">
      <div class="section-title">Anexos</div>
      <div class="card-body">
        <ul class="list-group mb-0">
          <li class="list-group-item">
            <i class="bi bi-paperclip me-1"></i> Contrato Social - <a href="#" class="text-decoration-none">Baixar</a>
          </li>
          <li class="list-group-item">
            <i class="bi bi-paperclip me-1"></i> Plano de Execução - <a href="#" class="text-decoration-none">Baixar</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="mb-4"></div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
    // jwtToken is already defined globally in navbar.php
    const projectId = '<?= $_GET["id"] ?? "" ?>';

    function safe(v) {
      return (v === null || v === undefined || v === '' || v === '0000-00-00') ? '' : v;
    }

    $(function() {
      if (!projectId) {
        alert('ID do projeto não informado. Por favor, acesse esta página através da lista de projetos.');
        window.location.href = 'lista_projetos_api.php';
        return;
      }

      $.ajax({
        url: 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api/projeto_dados.php?id=' + encodeURIComponent(projectId),
        type: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + jwtToken
        },
        success: function(data) {
          const dados = data.dados;
          const dadosinfo = data.dadosinfo || {};

          // Populate header fields
          $('#editalTitle').text('Inscrição de proposta de projeto para ' + (dados.titulo_edital || ''));
          $('#supportEmail').attr('href', 'mailto:' + (dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br')).text(dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br');

          // Populate process status
          $('#projectTitle').text(dados.titulo || '');
          $('#submissionDate').text(dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : '');
          $('#projectNumber').text(dados.id_project || '');
          $('#currentPhase').text(dados.nomepublico || '');

          // Populate proponent data
          $('#nomefantasia').text(safe(dados.nomefantasia || dados.proponente));
          $('#cnpjcpf').text(safe(dados.user_input || dados.cpf));
          $('#email').text(safe(dados.email));
          $('#telefone').text(safe(dados.telefone || dados.celular));

          // Socioeconomic information (conditional)
          const socioKeys = ['perfil', 'democratizacao', 'receita_bruta'];
          const hasSocio = socioKeys.some(k => safe(dados[k]));
          if (hasSocio) {
            let socioHtml = '';
            socioKeys.forEach(function(k) {
              if (safe(dados[k])) {
                const label = k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                socioHtml += '<div class="info-row"><div class="info-label">' + label + ':</div><div class="info-value">' + dados[k] + '</div></div>';
              }
            });
            $('#socioeconomicasBody').html(socioHtml);
            $('#cardSocioeconomicas').show();
          }

          // Cultural proposal data
          if (dadosinfo.nome_categoria) {
            $('#categoria').text(dadosinfo.nome_categoria);
            $('#rowCategoria').show();
          }
          if (dadosinfo.nome_acultural) {
            $('#areaCultural').text(dadosinfo.nome_acultural);
            $('#rowAreaCultural').show();
          }
          if (dadosinfo.nome_concorrencia) {
            $('#concorrencia').text(dadosinfo.nome_concorrencia);
            $('#rowConcorrencia').show();
          }
          if (safe(dados.titulo)) {
            $('#nomeProjeto').text(dados.titulo);
            $('#rowNomeProjeto').show();
          }

          // Dates
          const inicio = safe(dados.dt_inicio_realiz) || safe(dados.dt_inicio_exec);
          const fim = safe(dados.dt_fim_realiz) || safe(dados.dt_fim_exec);
          if (inicio || fim) {
            $('#dataRealizacao').text(inicio + (inicio && fim ? ' a ' + fim : ''));
            $('#rowDataRealizacao').show();
          }

          // Location
          const localParts = [];
          if (safe(dados.endereco)) localParts.push(dados.endereco + (dados.numero ? ', ' + dados.numero : ''));
          if (safe(dados.bairro)) localParts.push(dados.bairro);
          if (safe(dados.municipio)) localParts.push(dados.municipio);
          if (safe(dados.uf)) localParts.push(dados.uf);
          if (localParts.length) {
            $('#local').text(localParts.join(' - '));
            $('#rowLocal').show();
          }

          // Team
          const equipeItems = [];
          if (safe(dados.nome_resp)) equipeItems.push({name: dados.nome_resp, role: 'Responsável'});
          if (safe(dados.nome_coord)) equipeItems.push({name: dados.nome_coord, role: 'Coordenador'});
          if (equipeItems.length) {
            let equipeHtml = '';
            equipeItems.forEach(function(p) {
              equipeHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><span><i class="bi bi-person-fill me-1"></i> ' + p.name + '</span><small class="text-muted">' + p.role + '</small></li>';
            });
            $('#equipeList').html(equipeHtml);
            $('#cardEquipe').show();
          }
        },
        error: function(xhr, status, error) {
          console.error('Erro na requisição:', {status: xhr.status, error: error});
          alert('Erro ao carregar os dados do projeto. Para visualizar mais detalhes, entre no Desenvolve Cultura através do nosso site.');
        }
      });
    });
  </script>
</body>

</html>
