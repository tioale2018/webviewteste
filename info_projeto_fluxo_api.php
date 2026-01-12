<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fluxo do Projeto - Desenvolve Cultura</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
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
                    let datas = data.datas || [];

                    // Build main HTML with shared header
                    let html = buildMainHtml(dados);
                    $('#project-info').html(html);

                    // Render fluxo-specific section
                    renderFluxoSection(data, datas);

                    // Set active navbar button
                    setActiveNav('info_projeto_fluxo');
                },
                // Error callback - fall back to static page
                function(err) {
                    console.error('API Error, redirecting to static page');
                    window.location.href = 'info_projeto.php';
                }
        ***REMOVED***;
        });

        /**
         * Renders the project flow/status section
         * This is the most complex section with multiple conditional cards
         * based on date configuration and data availability
         * @param {Object} data - Full API response data
         * @param {Array} datas - Array of date configuration objects
         */
        function renderFluxoSection(data, datas) {
            const $sub = $('#project-subsection');

            // Check if each configuration is active in the period (campo2)
            const ativoRecursoparecer = getItemSeAtivo(datas, 'recursoparecerdata');
            const ativoAvaltecrecursodata = getItemSeAtivo(datas, 'avaltecrecursodata');
            const ativoExibeNotaRecurso = getItemSeAtivo(datas, 'exibenotarecurso');
            const ativoExibeNotaProponente = getItemSeAtivo(datas, 'exibenotaproponente');
            const ativoResultadoRecurso = getItemSeAtivo(datas, 'resultadorecavaldoc') || getItemSeAtivo(datas, 'resultadoavaldoc');
            const ativoExibeRecursoAvalDoc = getItemSeAtivo(datas, 'exiberecursoavaldoc');

            let html = '';

            // ==================== PARECER DO RECURSO ====================
            // Only show if configuration is active
            if (ativoRecursoparecer) {
                html += `<div class="card">
                    <div class="section-title">Parecer do Recurso</div>
                    <div class="card-body">
                        <p>Período: ${ativoRecursoparecer.campo2}</p>
                    </div>
                </div>`;
            }

            // ==================== NOTA DO PROJETO ====================
            // Only show if:
            // 1. Project has scores (data.notas exists)
            // 2. At least one of the display configurations is active
            const mostraNotas = data.notas && (ativoExibeNotaRecurso || ativoExibeNotaProponente || ativoResultadoRecurso);

            if (mostraNotas) {
                html += `<div class="card">
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
                                <tbody>`;

                // Calculate total score while rendering rows
                let totalNota = 0;
                (data.notas || []).forEach(function(nota) {
                    html += `<tr>
                        <td>${nota.pergunta}</td>
                        <td>${nota.media}</td>
                    </tr>`;
                    totalNota += parseFloat(nota.media) || 0;
                });

                html += `</tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>${JSON.stringify(totalNota)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>`;

                // Show evaluator comments if available
                if (data.notas.pareceres) {
                    html += `<div class="row">`;
                    data.notas.pareceres.forEach(function(parecer, index) {
                        html += `<div class="col-md-6">
                            <h6>Avaliador ${index + 1}:</h6>
                            <p class="mb-2">${parecer}</p>
                        </div>`;
                    });
                    html += `</div>`;
                }

                html += `</div></div>`;
            }

            // ==================== RESULTADO DO RECURSO ====================
            // Only show if:
            // 1. Resource result data exists
            // 2. Result display configuration is active
            if (data.resultado_recurso && ativoResultadoRecurso) {
                html += `<div class="card">
                    <div class="section-title">Resultado Recurso Avaliação Documental</div>
                    <div class="card-body">
                        <p><strong>Observação:</strong> ${data.resultado_recurso.observacao}</p>
                        <p><strong>Motivo da inabilitação:</strong> ${data.resultado_recurso.motivo || 'Habilitado'}</p>
                    </div>
                </div>`;
            }

            // ==================== AVALIAÇÃO DOCUMENTAL ====================
            // Always show if data exists (no date-based condition)
            if (data.avaliacao_documental) {
                html += `<div class="card">
                    <div class="section-title">Avaliação Documental</div>
                    <div class="card-body">
                        <p><strong>Observação:</strong> ${data.avaliacao_documental.observacao}</p>
                        <p><strong>Motivo da inabilitação:</strong> ${data.avaliacao_documental.motivo || 'N/A'}</p>
                    </div>
                </div>`;
            }

            // ==================== ENVIO DE RECURSO ====================
            // Only show if:
            // 1. Resource submission data exists
            // 2. Display configuration is active (during submission period)
            if (data.recurso && ativoExibeRecursoAvalDoc) {
                html += `<div class="card">
                    <div class="section-title">Envio de Recurso</div>
                    <div class="card-body">`;

                // Show attached file if exists
                if (data.recurso.arquivo) {
                    html += `<div class="mb-2">
                        <strong>Arquivo adicionado:</strong>
                        <div>
                            <a href="${data.recurso.arquivo.url}" class="file-link text-decoration-none">
                                <i class="bi bi-paperclip"></i> ${data.recurso.arquivo.nome}
                            </a>
                        </div>
                    </div>`;
                }

                // Show message if exists
                if (data.recurso.mensagem) {
                    html += `<div class="mb-2">
                        <strong>Mensagem enviada:</strong>
                        <div class="border p-2 bg-light">${data.recurso.mensagem}</div>
                    </div>`;
                }

                // Show submission date if exists
                if (data.recurso.data_envio) {
                    html += `<p class="text-danger mb-0">
                        <strong>Recurso recebido em:</strong>
                        ${new Date(data.recurso.data_envio * 1000).toLocaleString('pt-BR')}
                    </p>`;
                }

                html += `</div></div>`;
            }

            // ==================== RECURSO DE NOTA ====================
            // Only show if:
            // 1. Score resource data exists
            // 2. Score display configuration is active
            if (data.recurso_nota && ativoExibeNotaRecurso) {
                html += `<div class="card mb-5">
                    <div class="section-title">Recurso de nota</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <p class="">${data.recurso_nota.mensagem}</p>
                        </div>
                    </div>
                </div>`;
            }

            // Add bottom spacing for fixed navbar
            html += '<div class="mb-5"></div><br>';

            // Inject rendered HTML into subsection
            $sub.html(html);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
