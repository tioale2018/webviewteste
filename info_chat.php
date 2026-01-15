<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="./bootstrap/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">

  <style>
    body {
      background-color: #f8f9fa;
    }

    .section-title {
      font-weight: 600;
      font-size: 1rem;
      background: #f8f9fa;
      padding: 0.75rem;
      border-bottom: 1px solid #dee2e6;
    }

    .card {
      margin-bottom: 1rem;
    }

    .file-link {
      display: inline-block;
      margin-top: 0.5rem;
    }

    .file-link {
      display: inline-block;
      margin: .25rem 0;
      color: #0d6efd;
    }

    .list-group-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .95rem;
    }

    
.chat-history {
  display: flex;
  flex-direction: column;
  gap: .5rem;
  overflow-y: auto;
  height: 50vh; /* 50% da altura da tela */
  padding-right: .25rem;
}


    .chat-message {
      max-width: 80%;
      padding: .75rem;
      border-radius: .75rem;
      background: #f9facdff;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      word-wrap: break-word;
    }

    .chat-message.sent {
      background: #c0ecf8ff;
      align-self: flex-end;
    }

    .anexos-scroll {
      max-height: 200px;
      overflow-y: auto;
    }

    .form-chat-fixed {
      border-top: 1px solid #dee2e6;
      padding-top: 1rem;
      margin-top: 1rem;
    }

    @media (max-width: 576px) {

      .chat-history,
      .anexos-scroll {
        height: 160px;
      }
    }

    
@media (max-width: 576px) {
  .chat-history {
    height: 50vh;
  }
}

  </style>
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
    <?php include_once "navbar-bottom.php"; ?>

    <h1 class="h5 fw-bold mb-3">Chat</h1>
    <!-- Chat -->
    <div class="card mb-3">
      <div class="section-title">Mensagens</div>
      <div class="card-body d-flex flex-column">
        <div class="chat-history mb-3 overflow-auto">
          <div class="chat-message">
            Mobilização e contratação de equipe técnica e artística; Planejamento logístico do evento; Criação de elementos visuais e outros registros.
          </div>
          <div class="chat-message sent">
            A execução do objeto foi realizada no período oficial junino na data 20/07/2024, Quadra da Praça Pública J, bairro Irajá, RJ.
          </div>
          <div class="chat-message">
            Quais os desdobramentos da proposta cultural? O projeto proporcionou a salvaguarda das tradições da quadrilha junina.
          </div>
          <div class="chat-message">
            Mobilização e contratação de equipe técnica e artística; Planejamento logístico do evento; Criação de elementos visuais e outros registros.
          </div>
          <div class="chat-message sent">
            A execução do objeto foi realizada no período oficial junino na data 20/07/2024, Quadra da Praça Pública J, bairro Irajá, RJ.
          </div>
          <div class="chat-message">
            Quais os desdobramentos da proposta cultural? O projeto proporcionou a salvaguarda das tradições da quadrilha junina.
          </div>
          <div class="chat-message">
            Mobilização e contratação de equipe técnica e artística; Planejamento logístico do evento; Criação de elementos visuais e outros registros.
          </div>
          <div class="chat-message sent">
            A execução do objeto foi realizada no período oficial junino na data 20/07/2024, Quadra da Praça Pública J, bairro Irajá, RJ.
          </div>
          <div class="chat-message">
            Quais os desdobramentos da proposta cultural? O projeto proporcionou a salvaguarda das tradições da quadrilha junina.
          </div>
          <div class="chat-message">
            Mobilização e contratação de equipe técnica e artística; Planejamento logístico do evento; Criação de elementos visuais e outros registros.
          </div>
          <div class="chat-message sent">
            A execução do objeto foi realizada no período oficial junino na data 20/07/2024, Quadra da Praça Pública J, bairro Irajá, RJ.
          </div>
          <div class="chat-message">
            Quais os desdobramentos da proposta cultural? O projeto proporcionou a salvaguarda das tradições da quadrilha junina.
          </div>
          <div class="chat-message">
            Mobilização e contratação de equipe técnica e artística; Planejamento logístico do evento; Criação de elementos visuais e outros registros.
          </div>
          <div class="chat-message sent">
            A execução do objeto foi realizada no período oficial junino na data 20/07/2024, Quadra da Praça Pública J, bairro Irajá, RJ.
          </div>
          <div class="chat-message">
            Quais os desdobramentos da proposta cultural? O projeto proporcionou a salvaguarda das tradições da quadrilha junina.
          </div>
        </div>

        <form class="form-chat-fixed">
          <div class="mb-2">
            <textarea class="form-control" rows="2" placeholder="Escreva sua mensagem..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Enviar</button>
        </form>
      </div>
    </div>
    <div class="mb-5"></div>
  <!-- Spacer for fixed bottom navbar -->
  <div style="height: 80px;"></div>
</main>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>