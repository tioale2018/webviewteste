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
            padding: 0.25rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            flex: 0 0 200px;
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            flex: 1;
        }

        @media (max-width: 576px) {
            .info-row {
                flex-direction: column;
            }

            .info-label {
                flex: 1;
                margin-bottom: 0.25rem;
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
                    const dadosinfo = data.dadosinfo || {};

                    let html = buildCommonHeader(dados);

                    // Dados do Proponente
                    html += `<div class="card mb-3">
                        <div class="section-title">Dados do Proponente</div>
                        <div class="card-body">
                            <div class="info-row"><div class="info-label">Nome Fantasia:</div><div class="info-value"><b>${safe(dados.nomefantasia || dados.proponente)}</b></div></div>
                            <div class="info-row"><div class="info-label">CNPJ/CPF:</div><div class="info-value"><b>${safe(dados.user_input || dados.cpf)}</b></div></div>
                            <div class="info-row"><div class="info-label">E-mail:</div><div class="info-value"><b>${safe(dados.email)}</b></div></div>
                            <div class="info-row"><div class="info-label">Telefone:</div><div class="info-value"><b>${safe(dados.telefone || dados.celular)}</b></div></div>
                        </div>
                    </div>`;

                    // Informações Socioeconômicas (render only if any present)
                    const socioKeys = ['perfil', 'democratizacao', 'receita_bruta'];
                    const hasSocio = socioKeys.some(k => safe(dados[k]));
                    if (hasSocio) {
                        html += `<div class="card mb-3">
                            <div class="section-title">Informações Socioeconômicas</div>
                            <div class="card-body">`;
                        socioKeys.forEach(function(k) {
                            if (safe(dados[k])) {
                                const label = k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                html += `<div class="info-row"><div class="info-label">${label}:</div><div class="info-value">${dados[k]}</div></div>`;
                            }
                        });
                        html += `</div></div>`;
                    }

                    // Dados da Proposta Cultural
                    html += `<div class="card mb-3">
                        <div class="section-title">Dados da Proposta Cultural</div>
                        <div class="card-body">`;

                    // Categoria / Área cultural / Concorrência
                    if (dadosinfo.nome_categoria) html += `<div class="info-row"><div class="info-label">Categoria:</div><div class="info-value"><b>${dadosinfo.nome_categoria}</b></div></div>`;
                    if (dadosinfo.nome_acultural) html += `<div class="info-row"><div class="info-label">Área Cultural:</div><div class="info-value"><b>${dadosinfo.nome_acultural}</b></div></div>`;
                    if (dadosinfo.nome_concorrencia) html += `<div class="info-row"><div class="info-label">Concorrência:</div><div class="info-value"><b>${dadosinfo.nome_concorrencia}</b></div></div>`;

                    // Título do Projeto e datas
                    if (safe(dados.titulo)) html += `<div class="info-row"><div class="info-label">Nome do Projeto:</div><div class="info-value"><b>${dados.titulo}</b></div></div>`;
                    const inicio = safe(dados.dt_inicio_realiz) || safe(dados.dt_inicio_exec);
                    const fim = safe(dados.dt_fim_realiz) || safe(dados.dt_fim_exec);
                    if (inicio || fim) html += `<div class="info-row"><div class="info-label">Data de Realização:</div><div class="info-value"><b>${inicio}${inicio && fim ? ' a ' + fim : ''}</b></div></div>`;

                    // Local
                    const localParts = [];
                    if (safe(dados.endereco)) localParts.push(dados.endereco + (dados.numero ? ', ' + dados.numero : ''));
                    if (safe(dados.bairro)) localParts.push(dados.bairro);
                    if (safe(dados.municipio)) localParts.push(dados.municipio);
                    if (safe(dados.uf)) localParts.push(dados.uf);
                    if (localParts.length) html += `<div class="info-row"><div class="info-label">Local:</div><div class="info-value"><b>${localParts.join(' - ')}</b></div></div>`;

                    html += `</div></div>`;

                    // Equipe
                    const equipeItems = [];
                    if (safe(dados.nome_resp)) equipeItems.push({name: dados.nome_resp, role: 'Responsável'});
                    if (safe(dados.nome_coord)) equipeItems.push({name: dados.nome_coord, role: 'Coordenador'});
                    if (equipeItems.length) {
                        html += `<div class="card mb-3"><div class="section-title">Equipe</div><div class="card-body"><ul class="list-group">`;
                        equipeItems.forEach(function(p) {
                            html += `<li class="list-group-item d-flex justify-content-between align-items-center"><span><i class="bi bi-person-fill me-1"></i> ${p.name}</span><small class="text-muted">${p.role}</small></li>`;
                        });
                        html += `</ul></div></div>`;
                    }

                    // Anexos (placeholder)
                    html += `<div class="card mb-3"><div class="section-title">Anexos</div><div class="card-body"><ul class="list-group mb-0">`;
                    html += `<li class="list-group-item"><i class="bi bi-paperclip me-1"></i> Contrato Social - <a href="#" class="text-decoration-none">Baixar</a></li>`;
                    html += `<li class="list-group-item"><i class="bi bi-paperclip me-1"></i> Plano de Execução - <a href="#" class="text-decoration-none">Baixar</a></li>`;
                    html += `</ul></div></div>`;

                    $('#project-info').html(html);
                }
            });
        });
    </script>
</body>

</html>
