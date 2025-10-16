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
    <?php // if ($cpf): 
    ?>
    <script>
        // window.ReactNativeWebView?.postMessage(JSON.stringify({
        //     tipo: 'autenticacao',
        //     cpf: 
        // }));
    </script>
    <?php // endif; 
    ?>

    <?php include_once "navbar.php"; ?>
    <?php
// include_once "funcoes.php";

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
    <main class="container py-3">
        <?php // include_once "navbar-bottom.php"; 
        ?>

        <div class="row">
            <nav class="navbar fixed-bottom  bg-primary text-white px-3">
                <div class="container-fluid d-flex justify-content-around text-white">
                    <a id="info_projeto_dados" href="info_projeto_dados.php" style="font-size: 2rem; margin: 0 0.5rem;" class="bi bi-info-circle-fill text-white"></a>
                    <a id="info_projeto_fluxo" href="info_projeto.php" style="font-size: 2rem; margin: 0 0.5rem;" class="bi bi-clipboard2-data-fill text-white"></a>
                    <a id="info_projeto_chat" href="info_projeto_chat.php" style="font-size: 2rem; margin: 0 0.5rem;" class="bi bi-chat-left-text-fill text-white"></a>
                </div>
            </nav>
        </div>

        
        <div id="project-info"></div>
    </main>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script>
        //criar verificacao se variavel jwtToken existe
        if (typeof jwtToken === 'undefined') {
             const jwtToken = '<?= $token ?>';
        }
        
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
                    let datas = data.datas || [];
                    let dadosinfo = data.dadosinfo || {};

                    // Helper to build the common part of the page up to the Andamento card
                    function buildMainHtml() {
                        let html = '';
                        html += `<h1 class="h5 fw-bold mb-3">Inscrição de proposta de projeto para ${dados.titulo_edital || ''}</h1>`;
                        html += `<div class="card">
                            <div class="card-body">
                                <p class="mb-0">Dúvidas relacionadas ao edital devem ser encaminhadas para o e-mail <a href="mailto:${dados.linha1 ? dados.linha1 : 'suportedesenvolvecultura@cultura.rj.gov.br'}">${dados.linha1 ? dados.linha1 : 'suportedesenvolvecultura@cultura.rj.gov.br'}</a></p>
                            </div>
                        </div>`;

                        html += `<div class="card">
                            <div class="section-title">Andamento do processo</div>
                            <div class="card-body">
                                <p>Seu projeto <strong>${dados.titulo || ''}</strong> foi submetido para análise em <strong>${dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : ''}</strong> sob o número <strong>${dados.id_project || ''}</strong>.</p>
                                <p>Fase atual: <strong class="text-primary">${dados.nomepublico || ''}</strong></p>
                            </div>
                        </div>`;

                        // Placeholder where different subsection content will be injected
                        html += `<div id="project-subsection" class="mb-3"></div>`;

                        return html;
                    }

                    // Render subsection depending on selected view
                    function renderSubsection(view) {
                        const $sub = $('#project-subsection');
                        $sub.html('<div class="p-3 text-center text-muted">Carregando...</div>');
                        if (view === 'dados') {
                            if (!dados && !dadosinfo) {
                                $sub.html('<div class="alert alert-info">Nenhum dado do projeto disponível.</div>');
                                return;
                            }

                            function safe(v) { return (v === null || v === undefined || v === '' || v === '0000-00-00') ? '' : v; }

                            // Build cards similar to info_projeto_dados.php
                            let html = '';

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
                            const socioKeys = ['perfil','democratizacao','receita_bruta'];
                            const hasSocio = socioKeys.some(k => safe(dados[k]));
                            if (hasSocio) {
                                html += `<div class="card mb-3">
                                    <div class="section-title">Informações Socioeconômicas</div>
                                    <div class="card-body">`;
                                socioKeys.forEach(function(k) {
                                    if (safe(dados[k])) html += `<div class="info-row"><div class="info-label">${k.replace(/_/g,' ')}:</div><div class="info-value">${dados[k]}</div></div>`;
                                });
                                html += `</div></div>`;
                            }

                            // Dados da Proposta Cultural
                            html += `<div class="card mb-3">
                                <div class="section-title">Dados da Proposta Cultural</div>
                                <div class="card-body">`;

                            // Categoria / Área cultural / Concorrência
                            if (dadosinfo && (dadosinfo.nome_categoria || dadosinfo.nome_acultural || dadosinfo.nome_concorrencia)) {
                                if (dadosinfo.nome_categoria) html += `<div class="info-row"><div class="info-label">Categoria:</div><div class="info-value"><b>${dadosinfo.nome_categoria}</b></div></div>`;
                                if (dadosinfo.nome_acultural) html += `<div class="info-row"><div class="info-label">Área Cultural:</div><div class="info-value"><b>${dadosinfo.nome_acultural}</b></div></div>`;
                                if (dadosinfo.nome_concorrencia) html += `<div class="info-row"><div class="info-label">Concorrência:</div><div class="info-value"><b>${dadosinfo.nome_concorrencia}</b></div></div>`;
                            }

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
                                equipeItems.forEach(function(p){ html += `<li class="list-group-item d-flex justify-content-between align-items-center"><span><i class="bi bi-person-fill me-1"></i> ${p.name}</span><small class="text-muted">${p.role}</small></li>`; });
                                html += `</ul></div></div>`;
                            }

                            // Anexos (placeholder)
                            html += `<div class="card mb-3"><div class="section-title">Anexos</div><div class="card-body"><ul class="list-group mb-0">`;
                            html += `<li class="list-group-item"><i class="bi bi-paperclip me-1"></i> Contrato Social - <a href="#" class="text-decoration-none">Baixar</a></li>`;
                            html += `<li class="list-group-item"><i class="bi bi-paperclip me-1"></i> Plano de Execução - <a href="#" class="text-decoration-none">Baixar</a></li>`;
                            html += `</ul></div></div>`;

                            $sub.html(html);
                        } else if (view === 'chat') {
                             let html = '';
                            // Minimal chat placeholder (you can fetch server-rendered HTML if preferred)
                            html += `<div class="card">
                                <div class="section-title">Chat</div>
                                <div class="card-body">
                                    <p class="mb-2">Área de mensagens e anexos do projeto.</p>
                                    <div class="chat-history mb-3" style="max-height:200px;overflow:auto;">
                                        <div class="chat-message">Mensagem de exemplo</div>
                                        <div class="chat-message sent">Resposta exemplo</div>
                                    </div>
                                    <form>
                                        <div class="mb-2"><textarea class="form-control" rows="2" placeholder="Escreva sua mensagem..."></textarea></div>
                                        <button class="btn btn-primary w-100">Enviar</button>
                                    </form>
                                </div>
                            </div>`;
                            $sub.html(html);

                        } else if (view === 'fluxo') {
                            // Build the rest of the page (cards that come after the subsection)
                    
                        // Checa se cada configuração está ativa no período (campo2)
                        const ativoRecursoparecer = getItemSeAtivo(datas, 'recursoparecerdata');
                        const ativoAvaltecrecursodata = getItemSeAtivo(datas, 'avaltecrecursodata');
                        const ativoExibeNotaRecurso = getItemSeAtivo(datas, 'exibenotarecurso');
                        const ativoExibeNotaProponente = getItemSeAtivo(datas, 'exibenotaproponente');
                        const ativoResultadoRecurso = getItemSeAtivo(datas, 'resultadorecavaldoc') || getItemSeAtivo(datas, 'resultadoavaldoc');
                        const ativoExibeRecursoAvalDoc = getItemSeAtivo(datas, 'exiberecursoavaldoc');

                        // Parecer do Recurso
                        if (ativoRecursoparecer) {
                            html += `<div class="card">
                                <div class="section-title">Parecer do Recurso</div>
                                <div class="card-body">
                                    <p>Período: ${ativoRecursoparecer.campo2}</p>
                                </div>
                            </div>`;
                            $sub.html(html);
                        }

                        // Notas: só exibe se existir data.notas e alguma configuração de exibição estiver ativa
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
                                            $sub.html(html);

                            (data.notas.criterios || []).forEach(function(criterio) {
                                html += `<tr>
                                    <td>${criterio.nome}</td>
                                    <td>${criterio.nota}</td>
                                </tr>`;
                                $sub.html(html);
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
                                $sub.html(html);

                            if (data.notas.pareceres) {
                                html += `<div class="row">`;
                                data.notas.pareceres.forEach(function(parecer, index) {
                                    html += `<div class="col-md-6">
                                        <h6>Avaliador ${index + 1}:</h6>
                                        <p class="mb-2">${parecer}</p>
                                    </div>`;
                                    $sub.html(html);
                                });
                                html += `</div>`;
                                $sub.html(html);
                            }

                            html += `</div></div>`;
                            $sub.html(html);
                        }

                        // Resultado do recurso: só exibe se configurado e existir
                        if (data.resultado_recurso && ativoResultadoRecurso) {
                            html += `<div class="card">
                                <div class="section-title">Resultado Recurso Avaliação Documental</div>
                                <div class="card-body">
                                    <p><strong>Observação:</strong> ${data.resultado_recurso.observacao}</p>
                                    <p><strong>Motivo da inabilitação:</strong> ${data.resultado_recurso.motivo || 'Habilitado'}</p>
                                </div>
                            </div>`;
                            $sub.html(html);
                        }

                        // Avaliação documental (mantém exibição se existir)
                        if (data.avaliacao_documental) {
                            html += `<div class="card">
                                <div class="section-title">Avaliação Documental</div>
                                <div class="card-body">
                                    <p><strong>Observação:</strong> ${data.avaliacao_documental.observacao}</p>
                                    <p><strong>Motivo da inabilitação:</strong> ${data.avaliacao_documental.motivo || 'N/A'}</p>
                                </div>
                            </div>`;
                            $sub.html(html);
                        }

                        // Envio de recurso: exibe somente durante o período configurado
                        if (data.recurso && ativoExibeRecursoAvalDoc) {
                            html += `<div class="card">
                                <div class="section-title">Envio de Recurso</div>
                                <div class="card-body">`;
                                $sub.html(html);

                            if (data.recurso.arquivo) {
                                html += `<div class="mb-2">
                                    <strong>Arquivo adicionado:</strong>
                                    <div><a href="${data.recurso.arquivo.url}" class="file-link text-decoration-none"><i class="bi bi-paperclip"></i> ${data.recurso.arquivo.nome}</a></div>
                                </div>`;
                                $sub.html(html);
                            }

                            if (data.recurso.mensagem) {
                                html += `<div class="mb-2">
                                    <strong>Mensagem enviada:</strong>
                                    <div class="border p-2 bg-light">${data.recurso.mensagem}</div>
                                </div>`;
                                $sub.html(html);
                            }

                            if (data.recurso.data_envio) {
                                html += `<p class="text-danger mb-0"><strong>Recurso recebido em:</strong> ${new Date(data.recurso.data_envio * 1000).toLocaleString('pt-BR')}</p>`;
                                $sub.html(html);
                            }

                            html += `</div></div>`;
                            $sub.html(html);
                        }

                        // Recurso de nota: só exibe se configurado
                        if (data.recurso_nota && ativoExibeNotaRecurso) {
                            html += `<div class="card mb-5">
                                <div class="section-title">Recurso de nota</div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="">${data.recurso_nota.mensagem}</p>
                                    </div>
                                </div>
                            </div>`;
                            $sub.html(html);
                        }

                        html += '<div class="mb-5"></div><br>';
                        $sub.html(html);
                        } else {
                            // default: fluxo — do nothing or show contextual info
                            $sub.html('<div class="alert alert-secondary">Selecione uma opção no menu inferior para ver mais detalhes relacionados ao andamento do processo.</div>');
                        }
                    }

                    // Inject full page: main + remaining
                    let finalHtml = buildMainHtml();
                    $('#project-info').html(finalHtml);

                    // Attach click handlers to navbar items to load subsection views
                    function setActiveNav(id) {
                        $('#info_projeto_dados, #info_projeto_fluxo, #info_projeto_chat').removeClass('text-warning');
                        $('#' + id).addClass('text-warning');
                    }

                    $('#info_projeto_dados').off('click').on('click', function(e) {
                        e.preventDefault();
                        setActiveNav('info_projeto_dados');
                        renderSubsection('dados');
                    });

                    $('#info_projeto_chat').off('click').on('click', function(e) {
                        e.preventDefault();
                        setActiveNav('info_projeto_chat');
                        renderSubsection('chat');
                    });

                    $('#info_projeto_fluxo').off('click').on('click', function(e) {
                        e.preventDefault();
                        setActiveNav('info_projeto_fluxo');
                        renderSubsection('fluxo');
                    });

                    // Default view: fluxo (or choose 'dados' if you prefer)
                    setActiveNav('info_projeto_fluxo');
                    renderSubsection('fluxo');
                },
                error: function(err) {
                    const htmlErro = '<div class="alert alert-danger" role="alert">Erro ao carregar os dados do projeto. Tente novamente mais tarde.</div>';
                    $('#project-info').html(htmlErro);
                    console.error('Erro na requisição:', err);
                }
            });
        });
    </script>

    <script>
        // Retorna o item da configuração se o período (campo2) estiver ativo.
        // campo2 esperado: "YYYY-MM-DD HH:mm,YYYY-MM-DD HH:mm" (abertura,fechamento)
        function parseDateString(value) {
            if (!value) return null;
            const s = String(value).trim();
            // Unix timestamp em segundos
            if (/^\d{10}$/.test(s)) return new Date(parseInt(s, 10) * 1000);
            // Substitui espaço por 'T' para parsing mais consistente (ISO-like)
            const t = s.replace(' ', 'T');
            const d = new Date(t);
            return isNaN(d) ? null : d;
        }

        function getItemSeAtivo(datas, chaveCampo1) {
            if (!Array.isArray(datas)) return null;
            const item = datas.find(d => d.campo1 === chaveCampo1);
            if (!item || !item.campo2) return null;

            const parts = item.campo2.split(',').map(p => p.trim()).filter(Boolean);
            if (parts.length < 2) return null;

            const inicio = parseDateString(parts[0]);
            const fim = parseDateString(parts[1]);
            if (!inicio || !fim) return null;

            // Se as datas estiverem invertidas (início > fim), considerar como inválido
            if (inicio > fim) return null;

            const agora = new Date();
            return (agora >= inicio && agora <= fim) ? item : null;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>