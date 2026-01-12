<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat - Desenvolve Cultura</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <style>
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
            flex-shrink: 0;
        }

        .chat-message-wrapper.sent .chat-avatar {
            background: #0d6efd;
            color: white;
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

        <div id="project-info">
            <!-- Content dynamically loaded via AJAX -->
        </div>

        <div class="mt-5"></div>
        <br>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/api-helpers.js"></script>
    <script>
        const jwtToken = '<?= $token ?>';
        const projectId = '<?= $_GET["id"] ?? "" ?>';

        $(function() {
            if (!projectId) {
                $('#project-info').html(`
                    <div class="alert alert-warning" role="alert">
                        <h5 class="alert-heading">ID do projeto não informado</h5>
                        <p class="mb-0">Por favor, acesse esta página através da lista de projetos.</p>
                        <a href="lista_projetos_api.php" class="btn btn-primary mt-2">Ver Meus Projetos</a>
                    </div>
                `);
                return;
            }

            fetchProjectInfo(projectId, jwtToken, {
                success: function(data) {
                    if (!data || !data.dados) {
                        $('#project-info').html(`
                            <div class="alert alert-warning" role="alert">
                                <h5 class="alert-heading">Dados não disponíveis</h5>
                                <p class="mb-0">Nenhum dado foi encontrado para este projeto.</p>
                            </div>
                        `);
                        return;
                    }

                    const dados = data.dados;

                    let html = buildCommonHeader(dados);

                    // Arquivos Anexados card
                    html += `<div class="card mb-3">
                        <div class="section-title">Arquivos Anexados</div>
                        <div class="card-body">
                            <div class="mb-2">
                                <a href="#" class="file-link text-decoration-none">
                                    <i class="bi bi-plus-circle me-1"></i> Adicionar arquivo
                                </a>
                            </div>
                            <div class="anexos-scroll">
                                <ul class="list-group mb-0">
                                    <li class="list-group-item">
                                        <span><i class="bi bi-file-earmark-pdf me-1"></i> Documento_exemplo.pdf</span>
                                        <small class="text-muted">12/01/2026</small>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="bi bi-file-earmark-image me-1"></i> Imagem_projeto.jpg</span>
                                        <small class="text-muted">10/01/2026</small>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="bi bi-file-earmark-text me-1"></i> Proposta.docx</span>
                                        <small class="text-muted">08/01/2026</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>`;

                    // Mensagens card
                    html += `<div class="card mb-3">
                        <div class="section-title">Mensagens</div>
                        <div class="card-body">
                            <div class="chat-history">
                                <!-- Received message -->
                                <div class="chat-message-wrapper received">
                                    <div class="chat-avatar">SC</div>
                                    <div>
                                        <div class="chat-sender">Secretaria de Cultura</div>
                                        <div class="chat-bubble">
                                            Olá! Sua proposta foi recebida e está em análise. Em breve você receberá um retorno sobre o andamento.
                                        </div>
                                        <div class="chat-timestamp">10/01/2026 10:30</div>
                                    </div>
                                </div>

                                <!-- Sent message -->
                                <div class="chat-message-wrapper sent">
                                    <div>
                                        <div class="chat-sender">Você</div>
                                        <div class="chat-bubble">
                                            Obrigado pela informação! Aguardo o retorno.
                                        </div>
                                        <div class="chat-timestamp">10/01/2026 11:15</div>
                                    </div>
                                    <div class="chat-avatar">EU</div>
                                </div>

                                <!-- Another received message -->
                                <div class="chat-message-wrapper received">
                                    <div class="chat-avatar">SC</div>
                                    <div>
                                        <div class="chat-sender">Secretaria de Cultura</div>
                                        <div class="chat-bubble">
                                            Verificamos que falta um documento. Por favor, envie o comprovante de endereço.
                                        </div>
                                        <div class="chat-timestamp">11/01/2026 14:20</div>
                                    </div>
                                </div>
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
                    </div>`;

                    $('#project-info').html(html);

                    // Handle form submission (placeholder functionality)
                    $('#chatForm').on('submit', function(e) {
                        e.preventDefault();
                        alert('Funcionalidade de envio de mensagem será implementada em breve.');
                    });
                }
            });
        });
    </script>
</body>

</html>
