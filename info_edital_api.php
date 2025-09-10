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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Oportunidade</title>
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
  <main class="container py-3">
    <h1 class="h5 fw-bold mb-3" id="titulo-edital"></h1>
    <div id="tabs-edital"></div>
    <div id="conteudo-edital"></div>
  </main>
   <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
     const jwtToken = '<?= $token ?>';
    // Pega o id da query string
    function getIdFromUrl() {
      const params = new URLSearchParams(window.location.search);
      return params.get('id');
    }
    $(function() {
      const id = getIdFromUrl();
      if (!id) {
        $('#conteudo-edital').html('<div class="alert alert-danger">ID do edital não informado.</div>');
        return;
      }
            $.ajax({
                url: 'https://cultura.rj.gov.br/desenvolve-cultura/api/edital.php?id=' + encodeURIComponent(id),
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwtToken 
                },
                beforeSend: function() {
                    $('#editais-list').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status">');
                },        success: function(data) {
        $('#titulo-edital').text(data.dados.titulo || 'Oportunidade');
        // Tabs
        let tabs = `
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
        `;
        $('#tabs-edital').html(tabs);

        // Conteúdo das abas
        let conteudo = `
        <div class="tab-content" id="tabsContent">
          <div class="tab-pane fade show active" id="pane-inscricoes" role="tabpanel">
            <p>${data.dados.descricao || ''}</p>
            <div class="accordion" id="accordionInfo">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                    Sistema de Inscrições
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionInfo">
                  <div class="accordion-body">
                    <p>${data.dados.visaogeral || ''}</p>
                    <br>
                    Informações e esclarecimentos de dúvidas: ${data.dados.linha1 ? `<a href='mailto:${data.dados.linha1}'>${data.dados.linha1}</a>` : ''}
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
                    <ol class="list-group list-group-numbered">
                      ${(data.dados.etapas || '').split('\n').map(etapa => `<li class="list-group-item">${etapa.trim()}</li>`).join('')}
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
                    ${(data.anexos_obrigatorios || '')}
                    ${(data.anexos_opcionais || '')}
                  </div>
                </div>
              </div>
              ${(data.dados.anexos && data.dados.anexos.length) ? `
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                    Portfólio de Atuação Cultural
                  </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionInfo">
                  <div class="accordion-body p-3">
                    <p>${data.dados.anexos_intro || ''}</p>
                    <ol class="list-group list-group-numbered">
                      ${(data.dados.anexos || '').split('\n').map(info => info.trim() ? `<li class="list-group-item">${info}</li>` : '').join('')}
                    </ol>
                  </div>
                </div>
              </div>
              ` : ''}
            </div>
            <div class="d-none gap-2 mt-3" >
              <button class="btn btn-success btn-lg">Iniciar processo de inscrição</button>
            </div>
          </div>
          <div class="tab-pane fade" id="pane-legislacoes" role="tabpanel">
            ${data.legislacoes && data.legislacoes.length ? `
              <ul class="list-group">
                ${data.legislacoes.map(l => `<li class="list-group-item"><a href="${l.nomearquivo}" target="_blank">${l.nome}</a> – ${l.textoapoio}</li>`).join('')}
              </ul>
            ` : 'Acesse o Desenvolve Cultura através do computador para visualizar as legislações.'}
          </div>
          <div class="tab-pane fade" id="pane-publicacoes" role="tabpanel">
            ${data.publicacoes && data.publicacoes.length ? `
              <ul class="list-group">
                ${data.publicacoes.map(p => `<li class="list-group-item"><a href="${p.nomearquivo}" target="_blank">${p.nome}</a> – ${p.textoapoio}</li>`).join('')}
              </ul>
            ` : 'Acesse o Desenvolve Cultura através do computador para visualizar as publicações.'}
          </div>
          <div class="tab-pane fade" id="pane-manual" role="tabpanel">
            Clique aqui para fazer o download do <a href="http://cultura.rj.gov.br" target="_blank">Manual do Proponente</a>
          </div>
          <div class="tab-pane fade" id="pane-faq" role="tabpanel">
            Clique <a target="_blank" href="http://cultura.rj.gov.br/">aqui para acessar a Perguntas Frequentes</a>
          </div>
        </div>
        `;
        $('#conteudo-edital').html(conteudo);
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

