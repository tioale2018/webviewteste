<?php
include_once "funcoes.php";

$cpf = $_SESSION['cpf'] ?? null;
$id = $_SESSION['id_user'] ?? null;
$project_id = $_GET['id'] ?? null;


$payload = [
    'cpf' => $cpf,
    'id_user' => $id
];

$secret = getJwtSecret();
$token = generate_jwt($payload, $secret);
?>

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
    <?php // if ($cpf): ?>
        <script>
            // window.ReactNativeWebView?.postMessage(JSON.stringify({
            //     tipo: 'autenticacao',
            //     cpf: '<?= $cpf ?>'
            // }));
        </script>
    <?php // endif; ?>
    
    <?php include_once "navbar.php"; ?>
    <main class="container py-3">
        <?php include_once "navbar-bottom.php"; ?>


        <div id="project-info"></div>
    </main>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script>
        const jwtToken = '<?= $token ?>';
        const projectId = '<?= $project_id ?>';

        $(function() {
            $.ajax({
                url: 'https://cultura.rj.gov.br/desenvolve-cultura/api/info_projeto.php?id=' + encodeURIComponent(projectId),
                type: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: JSON.stringify({
                    id: projectId
                }),
                beforeSend: function() {
                    $('#project-info').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>');
                },
                success: function(data) {
                    let dados = data.dados;
                    let html = '';
                    
                    // Título do Edital
                    html += `<h1 class="h5 fw-bold mb-3">Inscrição de proposta de projeto para edital ${data.titulo_edital}</h1>`;
                    
                    // Card de Email para Dúvidas
                    html += `<div class="card">
                        <div class="card-body">
                            <p class="mb-0">Dúvidas relacionadas ao edital devem ser encaminhadas para o e-mail <a href="mailto:${data.email_duvidas}">${data.email_duvidas}</a></p>
                        </div>
                    </div>`;

                    // Card de Andamento do Processo
                    html += `<div class="card">
                        <div class="section-title">Andamento do processo</div>
                        <div class="card-body">
                            <p>Seu projeto <strong>${dados.titulo}</strong> foi submetido para análise em <strong>${dados.datasubmete}</strong> sob o número <strong>${dados.id_project}</strong>.</p>
                            <p>Fase atual: <strong class="text-primary">${dados.fase}</strong></p>
                        </div>
                    </div>`;

                    // Card de Nota do Projeto (se disponível)
                    if (data.notas) {
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
                                        
                        data.notas.criterios.forEach(function(criterio) {
                            html += `<tr>
                                <td>${criterio.nome}</td>
                                <td>${criterio.nota}</td>
                            </tr>`;
                        });

                        html += `</tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td>${data.notas.total}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>`;

                        // Pareceres dos avaliadores
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

                    // Card de Resultado do Recurso (se disponível)
                    if (data.resultado_recurso) {
                        html += `<div class="card">
                            <div class="section-title">Resultado Recurso Avaliação Documental</div>
                            <div class="card-body">
                                <p><strong>Observação:</strong> ${data.resultado_recurso.observacao}</p>
                                <p><strong>Motivo da inabilitação:</strong> ${data.resultado_recurso.motivo || 'Habilitado'}</p>
                            </div>
                        </div>`;
                    }

                    // Card de Avaliação Documental (se disponível)
                    if (data.avaliacao_documental) {
                        html += `<div class="card">
                            <div class="section-title">Avaliação Documental</div>
                            <div class="card-body">
                                <p><strong>Observação:</strong> ${data.avaliacao_documental.observacao}</p>
                                <p><strong>Motivo da inabilitação:</strong> ${data.avaliacao_documental.motivo || 'N/A'}</p>
                            </div>
                        </div>`;
                    }

                    // Card de Envio de Recurso (se disponível)
                    if (data.recurso) {
                        html += `<div class="card">
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

                    // Card de Recurso de Nota (se disponível)
                    if (data.recurso_nota) {
                        html += `<div class="card mb-5">
                            <div class="section-title">Recurso de nota</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <p class="">${data.recurso_nota.mensagem}</p>
                                </div>
                            </div>
                        </div>`;
                    }

                    html += '<div class="mb-5"></div><br>';
                    
                    $('#project-info').html(html);
                },
                error: function(err) {
                    const htmlErro = '<div class="alert alert-danger" role="alert">Erro ao carregar os dados do projeto. Tente novamente mais tarde.</div>';
                    $('#project-info').html(htmlErro);
                    console.error('Erro na requisição:', err);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>