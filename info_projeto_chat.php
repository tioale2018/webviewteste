<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
      gap: .75rem;
      overflow-y: auto;
      height: auto;
      max-height: 400px;
      padding-right: .25rem;
    }

    .chat-message-wrapper {
      display: flex;
      margin-bottom: 1rem;
      align-items: flex-start;
      gap: .5rem;
    }

    .chat-message-wrapper.sent {
      justify-content: flex-end;
    }

    .chat-message-wrapper.received {
      justify-content: flex-start;
    }

    .chat-bubble {
      max-width: 75%;
      padding: 1rem;
      border-radius: 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      word-wrap: break-word;
      line-height: 1.4;
    }

    .chat-message-wrapper.received .chat-bubble {
      background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
      color: #222;
      border-bottom-left-radius: .25rem;
    }

    .chat-message-wrapper.sent .chat-bubble {
      background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
      color: #fff;
      border-bottom-right-radius: .25rem;
    }

    .chat-sender {
      font-weight: 600;
      font-size: .85rem;
      margin-bottom: .25rem;
      color: #495057;
    }

    .chat-message-wrapper.sent .chat-sender {
      color: #0d6efd;
      text-align: right;
    }

    .chat-timestamp {
      font-size: .75rem;
      color: #6c757d;
      margin-top: .25rem;
    }

    .chat-message-wrapper.sent .chat-timestamp {
      text-align: right;
    }

    .chat-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: .75rem;
      color: #495057;
    }

    .chat-message-wrapper.sent .chat-avatar {
      background: #0d6efd;
      color: white;
    }

    /* Mensagens antigas */
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
  </style>
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
    <?php include_once "navbar-bottom.php"; ?>

    <h1 class="h5 fw-bold mb-3">Chat</h1>

    <div class="card">
      <div class="section-title">Andamento do processo</div>
      <div class="card-body">
        <p>Seu projeto <strong>Aquela Cia 20 anos : Oficina de Criação</strong> foi submetido para análise em <strong>08/10/2024</strong> sob o número <strong>59059</strong>.</p>
        <p>Fase atual: <strong class="text-primary">Avaliação documental</strong></p>
      </div>
    </div>

    <!-- Arquivos -->
    <div class="card mb-3">
      <div class="section-title">Arquivos Anexados</div>
      <div class="card-body">
        <div class="mb-3">
          <a href="#" class="text-decoration-none file-link"><i class="bi bi-paperclip"></i> Clique aqui para enviar um arquivo</a>
        </div>
        <div class="anexos-scroll">
          <ul class="list-group mb-0">
            <li class="list-group-item">
              <div><i class="bi bi-file-earmark-text me-1"></i> SHOW DE PRATA - MATERIAL COMPLEMENTAR.pdf</div>
              <small class="text-muted">20/05/2025</small>
            </li>
            <li class="list-group-item">
              <div><i class="bi bi-file-earmark-text me-1"></i> Termo Assinado - Quadrilha Show de Prata.pdf</div>
              <small class="text-muted">22/05/2024</small>
            </li>
            <li class="list-group-item">
              <div><i class="bi bi-file-earmark-text me-1"></i> Extrato Alessandra - Show de Prata.pdf</div>
              <small class="text-muted">19/05/2024</small>
            </li>
            <li class="list-group-item">
              <div><i class="bi bi-file-earmark-text me-1"></i> Extrato - Show de Prata.pdf</div>
              <small class="text-muted">18/05/2024</small>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Chat -->
    <div class="card mb-3">
      <div class="section-title">Mensagens</div>
      <div class="card-body d-flex flex-column">
        <div class="chat-history mb-3">
          <!-- Mensagem Recebida -->
          <div class="chat-message-wrapper received">
            <div>
              <div class="chat-sender">Avaliador</div>
              <div class="chat-bubble">
                Mobilização e contratação de equipe técnica e artística; Planejamento logístico do evento; Criação de elementos visuais e outros registros.
              </div>
              <div class="chat-timestamp">12/01/2026 14:30</div>
            </div>
          </div>

          <!-- Mensagem Enviada -->
          <div class="chat-message-wrapper sent">
            <div>
              <div class="chat-sender">Você</div>
              <div class="chat-bubble">
                A execução do objeto foi realizada no período oficial junino na data 20/07/2024, Quadra da Praça Pública J, bairro Irajá, RJ.
              </div>
              <div class="chat-timestamp">12/01/2026 15:45</div>
            </div>
          </div>

          <!-- Mensagem Recebida -->
          <div class="chat-message-wrapper received">
            <div>
              <div class="chat-sender">Avaliador</div>
              <div class="chat-bubble">
                Quais os desdobramentos da proposta cultural? O projeto proporcionou a salvaguarda das tradições da quadrilha junina.
              </div>
              <div class="chat-timestamp">12/01/2026 16:20</div>
            </div>
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
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>