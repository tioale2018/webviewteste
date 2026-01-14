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
    // jwtToken is already defined globally in navbar.php
    const projectId = '<?= $_GET["id"] ?? "" ?>';

    $(function() {
      if (!projectId) {
        alert('ID do projeto não informado. Por favor, acesse esta página através da lista de projetos.');
        window.location.href = 'lista_projetos_api.php';
        return;
      }

      // Helper function for file icons
      function getFileIcon(filename) {
        if (!filename) return 'bi-file-earmark';
        const ext = filename.split('.').pop().toLowerCase();
        const iconMap = {
          'pdf': 'bi-file-earmark-pdf',
          'doc': 'bi-file-earmark-word',
          'docx': 'bi-file-earmark-word',
          'xls': 'bi-file-earmark-excel',
          'xlsx': 'bi-file-earmark-excel',
          'jpg': 'bi-file-earmark-image',
          'jpeg': 'bi-file-earmark-image',
          'png': 'bi-file-earmark-image',
          'gif': 'bi-file-earmark-image',
          'zip': 'bi-file-earmark-zip',
          'rar': 'bi-file-earmark-zip'
        };
        return iconMap[ext] || 'bi-file-earmark';
      }

      function loadMessages() {
        $.ajax({
          url: 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api/projeto_chat.php?id=' + encodeURIComponent(projectId),
          type: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + jwtToken
          },
          success: function(data) {
            const dados = data.dados;
            const mensagens = data.mensagens || [];
            const anexos = data.anexos || [];

            // NEW: Check if chat interaction is enabled (from backend)
            const chatInterativo = dados.chat_interativo === true || dados.campoextra1 == 1;

            // Populate header fields
            $('#editalTitle').text('Inscrição de proposta de projeto para ' + (dados.titulo_edital || ''));
            $('#supportEmail').attr('href', 'mailto:' + (dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br')).text(dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br');

            // Populate process status
            $('#projectTitle').text(dados.titulo || '');
            $('#submissionDate').text(dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : '');
            $('#projectNumber').text(dados.id_project || '');
            $('#currentPhase').text(dados.nomepublico || '');

            // Populate messages
            let chatHtml = '';
            if (mensagens.length === 0) {
              chatHtml = '<div class="text-center text-muted py-3">Nenhuma mensagem ainda</div>';
            } else {
              mensagens.forEach(function(msg) {
                const isReceived = msg.tiporesposta === 'admin' || msg.tiporesposta === 'avaliador';
                const avatar = isReceived ? (msg.nome_setor ? msg.nome_setor.substring(0,2).toUpperCase() : 'SC') : 'EU';
                const wrapperClass = isReceived ? 'received' : 'sent';
                const sender = isReceived ? (msg.nome_setor || 'Secretaria de Cultura') : 'Você';
                const timestamp = msg.dataresposta ? new Date(msg.dataresposta).toLocaleString('pt-BR') : '';

                chatHtml += `
                  <div class="chat-message-wrapper ${wrapperClass}">
                    ${isReceived ? '<div class="chat-avatar">' + avatar + '</div>' : ''}
                    <div>
                      <div class="chat-sender">${sender}</div>
                      <div class="chat-bubble">${msg.texto || ''}</div>
                      <div class="chat-timestamp">${timestamp}</div>
                    </div>
                    ${!isReceived ? '<div class="chat-avatar">' + avatar + '</div>' : ''}
                  </div>
                `;
              });
            }
            $('#chatMessages').html(chatHtml);

            // NEW: Control chat form interaction based on campoextra1
            const $form = $('#chatForm');
            const $formWrapper = $('.form-chat-fixed');
            const $textarea = $form.find('textarea');
            const $submitBtn = $form.find('button[type="submit"]');

            // Remove any previous warning messages
            $('.chat-disabled-warning').remove();

            if (chatInterativo) {
              // Enable interaction
              $textarea.prop('disabled', false).prop('readonly', false);
              $textarea.attr('placeholder', 'Escreva sua mensagem...');
              $submitBtn.prop('disabled', false);
              $form.removeClass('chat-disabled');
              $formWrapper.removeClass('chat-disabled');
            } else {
              // Disable interaction but keep chat visible
              $textarea.prop('disabled', true).prop('readonly', true);
              $textarea.attr('placeholder', 'Interação desabilitada');
              $submitBtn.prop('disabled', true);
              $form.addClass('chat-disabled');
              $formWrapper.addClass('chat-disabled');

              // Add informative message before the form
              $formWrapper.before(`
                <div class="alert alert-warning chat-disabled-warning mb-3" role="alert">
                  <i class="bi bi-info-circle me-2"></i>
                  Este projeto não está habilitado para interação via chat no momento.
                </div>
              `);
            }

            // Populate files
            let filesHtml = '';
            if (anexos.length === 0) {
              filesHtml = '<li class="list-group-item text-muted">Nenhum arquivo anexado</li>';
            } else {
              anexos.forEach(function(file) {
                const fileIcon = getFileIcon(file.tipo_arquivo || file.nome_arquivo);
                const uploadDate = file.data_upload ? new Date(file.data_upload).toLocaleDateString('pt-BR') : '';

                filesHtml += `
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="bi ${fileIcon} me-1"></i> ${file.nome_arquivo || 'Arquivo'}</span>
                    <small class="text-muted">${uploadDate}</small>
                  </li>
                `;
              });
            }
            $('#fileList').html(filesHtml);

            // Scroll to bottom of chat
            const chatContainer = document.getElementById('chatMessages');
            if (chatContainer) {
              chatContainer.scrollTop = chatContainer.scrollHeight;
            }
          },
          error: function(xhr, status, error) {
            console.error('Erro na requisição:', {status: xhr.status, error: error});
            alert('Erro ao carregar os dados do projeto. Para visualizar mais detalhes, entre no Desenvolve Cultura através do nosso site.');
          }
        });
      }

      // Load messages on page load
      loadMessages();

      // Handle form submission
      $('#chatForm').on('submit', function(e) {
        e.preventDefault();

        // NEW: Check if form is disabled (extra protection)
        if ($(this).hasClass('chat-disabled')) {
          alert('Chat não está habilitado para interação neste momento.');
          return false;
        }

        const textarea = $(this).find('textarea');
        const mensagem = textarea.val().trim();

        if (!mensagem) {
          alert('Por favor, digite uma mensagem.');
          return;
        }

        $.ajax({
          url: 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api/projeto_chat.php?id=' + encodeURIComponent(projectId),
          type: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + jwtToken
          },
          data: JSON.stringify({
            texto: mensagem,
            setor_id: null
          }),
          success: function(response) {
            // NEW: Check for explicit error response from backend
            if (response && response.success === false) {
              alert(response.message || 'Erro ao enviar mensagem.');
              return;
            }
            textarea.val(''); // Clear textarea
            loadMessages(); // Reload messages
          },
          error: function(xhr, status, error) {
            // NEW: Handle 403 Forbidden (chat disabled)
            if (xhr.status === 403) {
              alert('Chat não está habilitado para interação neste momento.');
              loadMessages(); // Refresh to update UI state
            } else {
              console.error('Erro ao enviar mensagem:', {status: xhr.status, error: error});
              alert('Erro ao enviar mensagem. Tente novamente.');
            }
          }
        });
      });
    });
  </script>
</body>

</html>
