<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dados do Projeto - Desenvolve Cultura</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
                    let dadosinfo = data.dadosinfo || {};

                    // Build main HTML with shared header
                    let html = buildMainHtml(dados);
                    $('#project-info').html(html);

                    // Render dados-specific section
                    renderDadosSection(dados, dadosinfo);

                    // Set active navbar button
                    setActiveNav('info_projeto_dados');
                },
                // Error callback - fall back to static page
                function(err) {
                    console.error('API Error, redirecting to static page');
                    window.location.href = 'info_projeto_dados.php';
                }
        ***REMOVED***;
        });

        /**
         * Renders the project data section
         * @param {Object} dados - Project main data
         * @param {Object} dadosinfo - Additional project information
         */
        function renderDadosSection(dados, dadosinfo) {
            const $sub = $('#project-subsection');

            // Check if we have data to display
            if (!dados && !dadosinfo) {
                $sub.html('<div class="alert alert-info">Nenhum dado do projeto disponível.</div>');
                return;
            }

            let html = '';

            // ==================== DADOS DO PROPONENTE ====================
            html += `<div class="card mb-3">
                <div class="section-title">Dados do Proponente</div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Nome Fantasia:</div>
                        <div class="info-value"><b>${safe(dados.nomefantasia || dados.proponente)}</b></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">CNPJ/CPF:</div>
                        <div class="info-value"><b>${safe(dados.user_input || dados.cpf)}</b></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">E-mail:</div>
                        <div class="info-value"><b>${safe(dados.email)}</b></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telefone:</div>
                        <div class="info-value"><b>${safe(dados.telefone || dados.celular)}</b></div>
                    </div>
                </div>
            </div>`;

            // ==================== INFORMAÇÕES SOCIOECONÔMICAS ====================
            // Only render if at least one socioeconomic field is present
            const socioKeys = ['perfil', 'democratizacao', 'receita_bruta'];
            const hasSocio = socioKeys.some(k => safe(dados[k]));

            if (hasSocio) {
                html += `<div class="card mb-3">
                    <div class="section-title">Informações Socioeconômicas</div>
                    <div class="card-body">`;

                socioKeys.forEach(function(k) {
                    if (safe(dados[k])) {
                        const label = k.replace(/_/g, ' ');
                        html += `<div class="info-row">
                            <div class="info-label">${label}:</div>
                            <div class="info-value">${dados[k]}</div>
                        </div>`;
                    }
                });

                html += `</div></div>`;
            }

            // ==================== DADOS DA PROPOSTA CULTURAL ====================
            html += `<div class="card mb-3">
                <div class="section-title">Dados da Proposta Cultural</div>
                <div class="card-body">`;

            // Category, Cultural Area, Competition
            if (dadosinfo && (dadosinfo.nome_categoria || dadosinfo.nome_acultural || dadosinfo.nome_concorrencia)) {
                if (dadosinfo.nome_categoria) {
                    html += `<div class="info-row">
                        <div class="info-label">Categoria:</div>
                        <div class="info-value"><b>${dadosinfo.nome_categoria}</b></div>
                    </div>`;
                }
                if (dadosinfo.nome_acultural) {
                    html += `<div class="info-row">
                        <div class="info-label">Área Cultural:</div>
                        <div class="info-value"><b>${dadosinfo.nome_acultural}</b></div>
                    </div>`;
                }
                if (dadosinfo.nome_concorrencia) {
                    html += `<div class="info-row">
                        <div class="info-label">Concorrência:</div>
                        <div class="info-value"><b>${dadosinfo.nome_concorrencia}</b></div>
                    </div>`;
                }
            }

            // Project title
            if (safe(dados.titulo)) {
                html += `<div class="info-row">
                    <div class="info-label">Nome do Projeto:</div>
                    <div class="info-value"><b>${dados.titulo}</b></div>
                </div>`;
            }

            // Execution dates (with fallback)
            const inicio = safe(dados.dt_inicio_realiz) || safe(dados.dt_inicio_exec);
            const fim = safe(dados.dt_fim_realiz) || safe(dados.dt_fim_exec);
            if (inicio || fim) {
                html += `<div class="info-row">
                    <div class="info-label">Data de Realização:</div>
                    <div class="info-value"><b>${inicio}${inicio && fim ? ' a ' + fim : ''}</b></div>
                </div>`;
            }

            // Location (constructed from multiple fields)
            const localParts = [];
            if (safe(dados.endereco)) {
                localParts.push(dados.endereco + (dados.numero ? ', ' + dados.numero : ''));
            }
            if (safe(dados.bairro)) localParts.push(dados.bairro);
            if (safe(dados.municipio)) localParts.push(dados.municipio);
            if (safe(dados.uf)) localParts.push(dados.uf);

            if (localParts.length) {
                html += `<div class="info-row">
                    <div class="info-label">Local:</div>
                    <div class="info-value"><b>${localParts.join(' - ')}</b></div>
                </div>`;
            }

            html += `</div></div>`;

            // ==================== EQUIPE ====================
            const equipeItems = [];
            if (safe(dados.nome_resp)) {
                equipeItems.push({
                    name: dados.nome_resp,
                    role: 'Responsável'
                });
            }
            if (safe(dados.nome_coord)) {
                equipeItems.push({
                    name: dados.nome_coord,
                    role: 'Coordenador'
                });
            }

            if (equipeItems.length) {
                html += `<div class="card mb-3">
                    <div class="section-title">Equipe</div>
                    <div class="card-body">
                        <ul class="list-group">`;

                equipeItems.forEach(function(p) {
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-fill me-1"></i> ${p.name}</span>
                        <small class="text-muted">${p.role}</small>
                    </li>`;
                });

                html += `</ul>
                    </div>
                </div>`;
            }

            // ==================== ANEXOS ====================
            // Placeholder for attachments
            html += `<div class="card mb-3">
                <div class="section-title">Anexos</div>
                <div class="card-body">
                    <ul class="list-group mb-0">
                        <li class="list-group-item">
                            <i class="bi bi-paperclip me-1"></i> Contrato Social -
                            <a href="#" class="text-decoration-none">Baixar</a>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-paperclip me-1"></i> Plano de Execução -
                            <a href="#" class="text-decoration-none">Baixar</a>
                        </li>
                    </ul>
                </div>
            </div>`;

            // Inject rendered HTML into subsection
            $sub.html(html);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
