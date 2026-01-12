<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acompanhamento da Proposta - Desenvolve Cultura</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
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
                    const datas = data.datas || [];

                    let html = buildCommonHeader(dados);

                    // Check which date configurations are active
                    const ativoRecursoparecer = getItemSeAtivo(datas, 'recursoparecerdata');
                    const ativoAvaltecrecursodata = getItemSeAtivo(datas, 'avaltecrecursodata');
                    const ativoExibeNotaRecurso = getItemSeAtivo(datas, 'exibenotarecurso');
                    const ativoExibeNotaProponente = getItemSeAtivo(datas, 'exibenotaproponente');
                    const ativoResultadoRecurso = getItemSeAtivo(datas, 'resultadorecavaldoc') || getItemSeAtivo(datas, 'resultadoavaldoc');
                    const ativoExibeRecursoAvalDoc = getItemSeAtivo(datas, 'exiberecursoavaldoc');

                    // Parecer do Recurso
                    if (ativoRecursoparecer) {
                        html += `<div class="card mb-3">
                            <div class="section-title">Parecer do Recurso</div>
                            <div class="card-body">
                                <p>Período: ${ativoRecursoparecer.campo2}</p>
                            </div>
                        </div>`;
                    }

                    // Notas: só exibe se existir data.notas e alguma configuração de exibição estiver ativa
                    const mostraNotas = data.notas && (ativoExibeNotaRecurso || ativoExibeNotaProponente || ativoResultadoRecurso);
                    if (mostraNotas) {
                        html += `<div class="card mb-3">
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
                                                <td>${totalNota.toFixed(2)}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>`;

                        if (data.notas.pareceres) {
                            html += `<div class="row">`;
                            data.notas.pareceres.forEach(function(parecer, index) {
                                html += `<div class="col-md-6 mb-3">
                                    <h6>Avaliador ${index + 1}:</h6>
                                    <p class="mb-2">${parecer}</p>
                                </div>`;
                            });
                            html += `</div>`;
                        }

                        html += `</div></div>`;
                    }

                    // Resultado do recurso: só exibe se configurado e existir
                    if (data.resultado_recurso && ativoResultadoRecurso) {
                        html += `<div class="card mb-3">
                            <div class="section-title">Resultado Recurso Avaliação Documental</div>
                            <div class="card-body">
                                <p><strong>Observação:</strong> ${data.resultado_recurso.observacao}</p>
                                <p class="mb-0"><strong>Motivo da inabilitação:</strong> ${data.resultado_recurso.motivo || 'Habilitado'}</p>
                            </div>
                        </div>`;
                    }

                    // Avaliação documental (mantém exibição se existir)
                    if (data.avaliacao_documental) {
                        html += `<div class="card mb-3">
                            <div class="section-title">Avaliação Documental</div>
                            <div class="card-body">
                                <p><strong>Observação:</strong> ${data.avaliacao_documental.observacao}</p>
                                <p class="mb-0"><strong>Motivo da inabilitação:</strong> ${data.avaliacao_documental.motivo || 'N/A'}</p>
                            </div>
                        </div>`;
                    }

                    // Envio de recurso: exibe somente durante o período configurado
                    if (data.recurso && ativoExibeRecursoAvalDoc) {
                        html += `<div class="card mb-3">
                            <div class="section-title">Envio de Recurso</div>
                            <div class="card-body">`;

                        if (data.recurso.arquivo) {
                            html += `<div class="mb-2">
                                <strong>Arquivo adicionado:</strong>
                                <div><a href="${data.recurso.arquivo.url}" class="file-link text-decoration-none"><i class="bi bi-paperclip"></i> ${data.recurso.arquivo.nome}</a></div>
                            </div>`;
                        }

                        if (data.recurso.mensagem) {
                            html += `<div class="mb-2">
                                <strong>Mensagem enviada:</strong>
                                <div class="border p-2 bg-light">${data.recurso.mensagem}</div>
                            </div>`;
                        }

                        if (data.recurso.data_envio) {
                            html += `<p class="text-danger mb-0"><strong>Recurso recebido em:</strong> ${new Date(data.recurso.data_envio * 1000).toLocaleString('pt-BR')}</p>`;
                        }

                        html += `</div></div>`;
                    }

                    // Recurso de nota: só exibe se configurado
                    if (data.recurso_nota && ativoExibeNotaRecurso) {
                        html += `<div class="card mb-3">
                            <div class="section-title">Recurso de nota</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <p class="mb-0">${data.recurso_nota.mensagem}</p>
                                </div>
                            </div>
                        </div>`;
                    }

                    $('#project-info').html(html);
                }
            });
        });
    </script>
</body>

</html>
