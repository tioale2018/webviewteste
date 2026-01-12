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
            height: 200px;
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
    </style>
</head>

<body class="bg-light">
    <?php
    include_once "navbar.php";

    // Get project ID from URL parameter
    $project_id = $_GET['id'] ?? null;

    // JWT token is already generated in navbar.php
    // Available as $token variable
    ?>

    <main class="container py-3">
        <?php include_once "navbar-bottom.php"; ?>

        <div id="project-info"></div>
    </main>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="./js/info_projeto_utils.js"></script>
    <script>
        const jwtToken = '<?= $token ?>';
        const projectId = '<?= $project_id ?>';

        $(function() {
            // Fetch project data from API
            fetchProjectData(projectId, jwtToken,
                // Success callback
                function(data) {
                    let dados = data.dados;

                    // Build main HTML with shared header
                    let html = buildMainHtml(dados);
                    $('#project-info').html(html);

                    // Render chat-specific section
                    renderChatSection(data);

                    // Set active navbar button
                    setActiveNav('info_projeto_chat');
                },
                // Error callback - fall back to static page
                function(err) {
                    console.error('API Error, redirecting to static page');
                    window.location.href = 'info_projeto_chat.php';
                }
        ***REMOVED***;
        });

        /**
         * Renders the chat/messaging section
         * Currently a placeholder implementation for future chat functionality
         * @param {Object} data - Full API response data
         */
        function renderChatSection(data) {
            const $sub = $('#project-subsection');

            let html = `<div class="card">
                <div class="section-title">Chat</div>
                <div class="card-body">
                    <p class="mb-2">Área de mensagens e anexos do projeto.</p>

                    <!-- Chat History -->
                    <div class="chat-history mb-3">
                        <div class="chat-message">Mensagem de exemplo</div>
                        <div class="chat-message sent">Resposta exemplo</div>
                    </div>

                    <!-- Chat Input Form -->
                    <form class="form-chat-fixed">
                        <div class="mb-2">
                            <textarea class="form-control" rows="2" placeholder="Escreva sua mensagem..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar</button>
                    </form>
                </div>
            </div>`;

            // Inject rendered HTML into subsection
            $sub.html(html);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
