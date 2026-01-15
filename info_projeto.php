<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acompanhamento da Proposta</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="./bootstrap/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
  <?php include_once "navbar-bottom.php"; ?>


    <h1 class="h5 fw-bold mb-3">Inscrição de proposta de projeto para edital Fluxos Fluminenses</h1>

    <!-- Tabs as Nav Pills -->
    <!-- <ul class="nav nav-pills mb-3" id="tabs" role="tablist">
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link active" id="tab-abertos" data-bs-toggle="pill" data-bs-target="#pane-abertos" type="button" role="tab">Informações</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-submetidos" data-bs-toggle="pill" data-bs-target="#pane-submetidos" type="button" role="tab">Andamento</button>
      </li>
      <li class="nav-item m-1" role="presentation">
        <button class="nav-link" id="tab-recurso" data-bs-toggle="pill" data-bs-target="#pane-recurso" type="button" role="tab">Chat</button>
      </li>
    </ul> -->

    <div class="card">
      <div class="card-body">
        <p class="mb-0">Dúvidas relacionadas ao edital devem ser encaminhadas para o e-mail <a href="mailto:fluxosrj@desenvolvecultura.rj.gov.br">fluxosrj@desenvolvecultura.rj.gov.br</a></p>
      </div>
    </div>

    <!-- Andamento do processo -->
    <div class="card">
      <div class="section-title">Andamento do processo</div>
      <div class="card-body">
        <p>Seu projeto <strong>Aquela Cia 20 anos : Oficina de Criação</strong> foi submetido para análise em <strong>08/10/2024</strong> sob o número <strong>59059</strong>.</p>
        <p>Fase atual: <strong class="text-primary">Avaliação documental</strong></p>
      </div>
    </div>

    <!-- Nota do projeto -->
    <div class="card">
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
            <tbody>
              <tr>
                <td>Relevância e impacto da proposta para o cenário cultural local</td>
                <td>11,00</td>
              </tr>
              <tr>
                <td>Acessibilidade da proposta</td>
                <td>12,00</td>
              </tr>
              <tr>
                <td>Viabilidade técnica da proposta</td>
                <td>11,00</td>
              </tr>
              <tr>
                <td>Democratização do acesso e coerência das ações de difusão</td>
                <td>11,00</td>
              </tr>
              <tr>
                <td>Trajetória proponente</td>
                <td>12,50</td>
              </tr>
              <tr>
                <td>Compatibilidade da ficha técnica com as atividades desenvolvidas</td>
                <td>8,50</td>
              </tr>
              <tr>
                <td>Qualidade do projeto - coerência da proposta</td>
                <td>11,00</td>
              </tr>
              <tr>
                <td>Indutor populacional</td>
                <td>0,00</td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td>Total</td>
                <td>77,00</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="row">
          <div class="col-md-6">
            <h6>Avaliador 1:</h6>
            <p class="mb-2">O projeto difunde os conhecimentos desta companhia com longa trajetória, propondo medidas de acessibilidade e inclusão.</p>
          </div>
          <div class="col-md-6">
            <h6>Avaliador 2:</h6>
            <p class="mb-2">Cronograma de execução sem detalhamento eficaz.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Resultado recurso -->
    <div class="card">
      <div class="section-title">Resultado Recurso Avaliação Documental</div>
      <div class="card-body">
        <p><strong>Observação:</strong> Habilitado</p>
        <p><strong>Motivo da inabilitação:</strong> Habilitado</p>
      </div>
    </div>

    <!-- Avaliação documental -->
    <div class="card">
      <div class="section-title">Avaliação Documental</div>
      <div class="card-body">
        <p><strong>Observação:</strong> Subitem 8.4.1: alínea b</p>
        <p><strong>Motivo da inabilitação:</strong> O PROPONENTE não apresentou CÓPIA SIMPLES DO CONTRATO ou ESTATUTO SOCIAL.</p>
      </div>
    </div>

    <!-- Envio de recurso -->
    <div class="card">
      <div class="section-title">Envio de Recurso</div>
      <div class="card-body">
        <div class="mb-2">
          <strong>Arquivo adicionado:</strong>
          <div><a href="#" class="file-link text-decoration-none"><i class="bi bi-paperclip"></i> AQ_CONTRATO_SOCIAL.pdf</a></div>
        </div>
        <div class="mb-2">
          <strong>Mensagem enviada:</strong>
          <div class="border p-2 bg-light">
            Por uma falha nossa, na hora de submeter o projeto, não vimos que o arquivo não havia sido anexado corretamente, mediante isso, enviamos o contrato anexo e solicitamos aceite nesse recurso.<br>Obrigada
          </div>
        </div>
        <p class="text-danger mb-0"><strong>Recurso recebido em:</strong> 25/06/2024 às 13:39</p>
      </div>
    </div>

    <div class="card mb-5">
      <div class="section-title">Recurso de nota</div>
      <div class="card-body">
        <div class="mb-2">
          <p class="">O recurso de nota encerrou no dia 14/01/2025 às 20:00h.</p>
        </div>

      </div>
    </div>
    <div class="mb-5"></div>
    <br>
  </main>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>