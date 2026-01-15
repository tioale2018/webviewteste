<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acompanhamento da Proposta - Desenvolve Cultura</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="./bootstrap/bootstrap-icons.css" rel="stylesheet">
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

    <!-- Card 3: Parecer do Recurso -->
    <div class="card mb-3" id="cardParecer" style="display:none;">
      <div class="section-title">Parecer do Recurso</div>
      <div class="card-body">
        <p class="mb-0">Período: <span id="parecerPeriodo"></span></p>
      </div>
    </div>

    <!-- Card 4: Nota do projeto -->
    <div class="card mb-3" id="cardNotas" style="display:none;">
      <div class="section-title">Nota do projeto</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle mb-3">
            <thead class="table-light">
              <tr>
                <th>Critério</th>
                <th>Média</th>
              </tr>
            </thead>
            <tbody id="notasTableBody">
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td>Total</td>
                <td id="totalNota"></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="row" id="pareceres">
        </div>
      </div>
    </div>

    <!-- Card 5: Resultado Recurso Avaliação Documental -->
    <div class="card mb-3" id="cardResultadoRecurso" style="display:none;">
      <div class="section-title">Resultado Recurso Avaliação Documental</div>
      <div class="card-body">
        <p><strong>Observação:</strong> <span id="resultadoObservacao"></span></p>
        <p class="mb-0"><strong>Motivo da inabilitação:</strong> <span id="resultadoMotivo"></span></p>
      </div>
    </div>

    <!-- Card 6: Avaliação Documental -->
    <div class="card mb-3" id="cardAvaliacaoDocumental" style="display:none;">
      <div class="section-title">Avaliação Documental</div>
      <div class="card-body">
        <p><strong>Observação:</strong> <span id="avaliacaoObservacao"></span></p>
        <p class="mb-0"><strong>Motivo da inabilitação:</strong> <span id="avaliacaoMotivo"></span></p>
      </div>
    </div>

    <!-- Card 7: Envio de Recurso -->
    <div class="card mb-3" id="cardEnvioRecurso" style="display:none;">
      <div class="section-title">Envio de Recurso</div>
      <div class="card-body">
        <div class="mb-2" id="recursoArquivo" style="display:none;">
          <strong>Arquivo adicionado:</strong>
          <div><a href="#" id="recursoArquivoLink" class="file-link text-decoration-none"><i class="bi bi-paperclip"></i> <span id="recursoArquivoNome"></span></a></div>
        </div>
        <div class="mb-2" id="recursoMensagem" style="display:none;">
          <strong>Mensagem enviada:</strong>
          <div class="border p-2 bg-light" id="recursoMensagemTexto"></div>
        </div>
        <p class="text-danger mb-0" id="recursoData" style="display:none;"><strong>Recurso recebido em:</strong> <span id="recursoDataTexto"></span></p>
      </div>
    </div>

    <!-- Card 8: Recurso de nota -->
    <div class="card mb-3" id="cardRecursoNota" style="display:none;">
      <div class="section-title">Recurso de nota</div>
      <div class="card-body">
        <div class="mb-2">
          <p class="mb-0" id="recursoNotaMensagem"></p>
        </div>
      </div>
    </div>

    <div class="mb-4"></div>
  <!-- Spacer for fixed bottom navbar -->
  <div style="height: 80px;"></div>
</main>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
    // jwtToken is already defined globally in navbar.php
    const projectId = '<?= $_GET["id"] ?? "" ?>';

    function safe(v) {
      return (v === null || v === undefined || v === '' || v === '0000-00-00') ? '' : v;
    }

    // Date helper functions
    function parseDateString(value) {
      if (!value) return null;
      const s = String(value).trim();
      // Unix timestamp in seconds
      if (/^\d{10}$/.test(s)) return new Date(parseInt(s, 10) * 1000);
      // Replace space with 'T' for ISO-like parsing
      const t = s.replace(' ', 'T');
      const d = new Date(t);
      return isNaN(d) ? null : d;
    }

    function getItemSeAtivo(datas, chaveCampo1) {
      if (!Array.isArray(datas)) return null;
      const item = datas.find(d => d.campo1 === chaveCampo1);
      if (!item || !item.campo2) return null;

      const parts = item.campo2.split(',').map(p => p.trim()).filter(Boolean);
      if (parts.length < 2) return null;

      const inicio = parseDateString(parts[0]);
      const fim = parseDateString(parts[1]);
      if (!inicio || !fim || inicio > fim) return null;

      const agora = new Date();
      return (agora >= inicio && agora <= fim) ? item : null;
    }

    $(function() {
      if (!projectId) {
        alert('ID do projeto não informado. Por favor, acesse esta página através da lista de projetos.');
        window.location.href = 'lista_projetos_api.php';
        return;
      }

      $.ajax({
        url: 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api/projeto_fluxo.php?id=' + encodeURIComponent(projectId),
        type: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + jwtToken
        },
        success: function(data) {
          const dados = data.dados;
          const datas = data.datas || [];

          // Populate header fields
          $('#editalTitle').text('Inscrição de proposta de projeto para ' + (dados.titulo_edital || ''));
          $('#supportEmail').attr('href', 'mailto:' + (dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br')).text(dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br');

          // Populate process status
          $('#projectTitle').text(dados.titulo || '');
          $('#submissionDate').text(dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : '');
          $('#projectNumber').text(dados.id_project || '');
          $('#currentPhase').text(dados.nomepublico || '');

          // Check date configurations
          const ativoRecursoparecer = getItemSeAtivo(datas, 'recursoparecerdata');
          const ativoExibeNotaRecurso = getItemSeAtivo(datas, 'exibenotarecurso');
          const ativoExibeNotaProponente = getItemSeAtivo(datas, 'exibenotaproponente');
          const ativoResultadoRecurso = getItemSeAtivo(datas, 'resultadorecavaldoc') || getItemSeAtivo(datas, 'resultadoavaldoc');
          const ativoExibeRecursoAvalDoc = getItemSeAtivo(datas, 'exiberecursoavaldoc');

          // Parecer do Recurso
          if (ativoRecursoparecer) {
            $('#parecerPeriodo').text(ativoRecursoparecer.campo2 || '');
            $('#cardParecer').show();
          }

          // Notas
          const mostraNotas = data.notas && (ativoExibeNotaRecurso || ativoExibeNotaProponente || ativoResultadoRecurso);
          if (mostraNotas) {
            let totalNota = 0;
            let rowsHtml = '';
            (data.notas || []).forEach(function(nota) {
              rowsHtml += '<tr><td>' + nota.pergunta + '</td><td>' + nota.media + '</td></tr>';
              totalNota += parseFloat(nota.media) || 0;
            });
            $('#notasTableBody').html(rowsHtml);
            $('#totalNota').text(totalNota.toFixed(2));

            // Pareceres
            if (data.notas.pareceres) {
              let pareceresHtml = '';
              data.notas.pareceres.forEach(function(parecer, index) {
                pareceresHtml += '<div class="col-md-6 mb-3"><h6>Avaliador ' + (index + 1) + ':</h6><p class="mb-2">' + parecer + '</p></div>';
              });
              $('#pareceres').html(pareceresHtml);
            }
            $('#cardNotas').show();
          }

          // Resultado do recurso
          if (data.resultado_recurso && ativoResultadoRecurso) {
            $('#resultadoObservacao').text(data.resultado_recurso.observacao || '');
            $('#resultadoMotivo').text(data.resultado_recurso.motivo || 'Habilitado');
            $('#cardResultadoRecurso').show();
          }

          // Avaliação documental
          if (data.avaliacao_documental) {
            $('#avaliacaoObservacao').text(data.avaliacao_documental.observacao || '');
            $('#avaliacaoMotivo').text(data.avaliacao_documental.motivo || 'N/A');
            $('#cardAvaliacaoDocumental').show();
          }

          // Envio de recurso
          if (data.recurso && ativoExibeRecursoAvalDoc) {
            if (data.recurso.arquivo) {
              $('#recursoArquivoLink').attr('href', data.recurso.arquivo.url || '#');
              $('#recursoArquivoNome').text(data.recurso.arquivo.nome || '');
              $('#recursoArquivo').show();
            }
            if (data.recurso.mensagem) {
              $('#recursoMensagemTexto').text(data.recurso.mensagem);
              $('#recursoMensagem').show();
            }
            if (data.recurso.data_envio) {
              $('#recursoDataTexto').text(new Date(data.recurso.data_envio * 1000).toLocaleString('pt-BR'));
              $('#recursoData').show();
            }
            $('#cardEnvioRecurso').show();
          }

          // Recurso de nota
          if (data.recurso_nota && ativoExibeNotaRecurso) {
            $('#recursoNotaMensagem').text(data.recurso_nota.mensagem || '');
            $('#cardRecursoNota').show();
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
