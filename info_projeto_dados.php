<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dados do Projeto</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="./bootstrap/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
  <style>
    .info-row {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: .5rem;
    }
    .info-label {
      font-weight: 600;
      margin-right: .25rem;
    }
    .info-value {
      flex: 1;
    }
    @media (max-width: 576px) {
      .info-row {
        flex-direction: column;
      }
      .info-label {
        margin-bottom: .25rem;
      }
    }
  </style>
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
  <?php include_once "navbar-bottom.php"; ?>

    <h1 class="h5 fw-bold mb-3">Dados do Projeto</h1>

    <!-- Andamento do processo -->
    <div class="card">
      <div class="section-title">Andamento do processo</div>
      <div class="card-body">
        <p>Seu projeto <strong>Aquela Cia 20 anos : Oficina de Criação</strong> foi submetido para análise em <strong>08/10/2024</strong> sob o número <strong>59059</strong>.</p>
        <p>Fase atual: <strong class="text-primary">Avaliação documental</strong></p>
      </div>
    </div>

    <!-- Dados do Proponente -->
    <div class="card mb-3">
      <div class="section-title">Dados do Proponente</div>
      <div class="card-body">
        <div class="info-row">
          <div class="info-label">Nome Fantasia:</div>
          <div class="info-value">Aquela Cia de Cultura</div>
        </div>
        <div class="info-row">
          <div class="info-label">CNPJ:</div>
          <div class="info-value">12.345.678/0001-90</div>
        </div>
        <div class="info-row">
          <div class="info-label">E-mail:</div>
          <div class="info-value">contato@aquelacia.org.br</div>
        </div>
        <div class="info-row">
          <div class="info-label">Telefone:</div>
          <div class="info-value">(21) 99999-8888</div>
        </div>
      </div>
    </div>

    <!-- Informações Socioeconômicas -->
    <div class="card mb-3">
      <div class="section-title">Informações Socioeconômicas</div>
      <div class="card-body">
        <div class="info-row">
          <div class="info-label">Tem renda familiar até 3 salários mínimos?</div>
          <div class="info-value">Sim</div>
        </div>
        <div class="info-row">
          <div class="info-label">Pessoa com deficiência?</div>
          <div class="info-value">Não</div>
        </div>
      </div>
    </div>

    <!-- Dados da Proposta Cultural -->
    <div class="card mb-3">
      <div class="section-title">Dados da Proposta Cultural</div>
      <div class="card-body">
        <div class="info-row">
          <div class="info-label">Categoria:</div>
          <div class="info-value">Quadrilha Junina</div>
        </div>
        <div class="info-row">
          <div class="info-label">Nome do Projeto:</div>
          <div class="info-value">Show de Prata 20 Anos</div>
        </div>
        <div class="info-row">
          <div class="info-label">Data de Realização:</div>
          <div class="info-value">15/07/2024</div>
        </div>
        <div class="info-row">
          <div class="info-label">Local:</div>
          <div class="info-value">Praça Pública J, Irajá, RJ</div>
        </div>
      </div>
    </div>

    <!-- Equipe -->
    <div class="card mb-3">
      <div class="section-title">Equipe</div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="bi bi-person-fill me-1"></i> Alessandra Nogueira</span>
            <small class="text-muted">Direção Geral</small>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="bi bi-person-fill me-1"></i> Rafael Vasconcelos</span>
            <small class="text-muted">Produção</small>
          </li>
        </ul>
      </div>
    </div>

    <!-- Anexos -->
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
    <div class="mt-5"></div>
    <br>
  <!-- Spacer for fixed bottom navbar -->
  <div style="height: 80px;"></div>
</main>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
