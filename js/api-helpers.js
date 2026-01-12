// Common API configuration
const API_BASE_URL = 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api';

// API call wrapper
function fetchProjectInfo(projectId, jwtToken, callbacks) {
    $.ajax({
        url: `${API_BASE_URL}/info_projeto.php?id=${encodeURIComponent(projectId)}`,
        type: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + jwtToken
        },
        beforeSend: callbacks.beforeSend || function() {
            $('#project-info').html(`
                <div class="d-flex justify-content-center align-items-center" style="min-height: 300px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-3 text-muted">Carregando dados do projeto...</p>
                    </div>
                </div>
            `);
        },
        success: callbacks.success,
        error: callbacks.error || function(xhr, status, error) {
            let errorMsg = 'Erro ao carregar os dados do projeto.';
            let details = '';

            if (xhr.status === 401) {
                errorMsg = 'Sessão expirada. Por favor, faça login novamente.';
                details = '<a href="index.php" class="btn btn-primary mt-2">Ir para Login</a>';
            } else if (xhr.status === 404) {
                errorMsg = 'Projeto não encontrado.';
                details = '<a href="lista_projetos_api.php" class="btn btn-primary mt-2">Ver Meus Projetos</a>';
            } else if (xhr.status === 0) {
                errorMsg = 'Sem conexão com a internet.';
                details = '<button class="btn btn-primary mt-2" onclick="location.reload()">Tentar Novamente</button>';
            }

            $('#project-info').html(`
                <div class="alert alert-danger" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>${errorMsg}</h5>
                    <p class="mb-0">Para visualizar mais detalhes, entre no Desenvolve Cultura através do nosso site.</p>
                    ${details}
                </div>
            `);
            console.error('Erro na requisição:', {status: xhr.status, error: error});
        }
    });
}

// Build common header section (used by all pages)
function buildCommonHeader(dados) {
    let html = '';
    html += `<h1 class="h5 fw-bold mb-3">Inscrição de proposta de projeto para ${dados.titulo_edital || ''}</h1>`;
    html += `<div class="card mb-3">
        <div class="card-body">
            <p class="mb-0">Dúvidas relacionadas ao edital devem ser encaminhadas para o e-mail <a href="mailto:${dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br'}">${dados.linha1 || 'suportedesenvolvecultura@cultura.rj.gov.br'}</a></p>
        </div>
    </div>`;
    html += `<div class="card mb-3">
        <div class="section-title">Andamento do processo</div>
        <div class="card-body">
            <p>Seu projeto <strong>${dados.titulo || ''}</strong> foi submetido para análise em <strong>${dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : ''}</strong> sob o número <strong>${dados.id_project || ''}</strong>.</p>
            <p class="mb-0">Fase atual: <strong class="text-primary">${dados.nomepublico || ''}</strong></p>
        </div>
    </div>`;
    return html;
}

// Safe value handler
function safe(v) {
    return (v === null || v === undefined || v === '' || v === '0000-00-00') ? '' : v;
}

// Date parsing utility
function parseDateString(value) {
    if (!value) return null;
    const s = String(value).trim();
    if (/^\d{10}$/.test(s)) return new Date(parseInt(s, 10) * 1000);
    const t = s.replace(' ', 'T');
    const d = new Date(t);
    return isNaN(d) ? null : d;
}

// Check if date configuration is active
function getItemSeAtivo(datas, chaveCampo1) {
    if (!Array.isArray(datas)) return null;
    const item = datas.find(d => d.campo1 === chaveCampo1);
    if (!item || !item.campo2) return null;

    const parts = item.campo2.split(',').map(p => p.trim()).filter(Boolean);
    if (parts.length < 2) return null;

    const inicio = parseDateString(parts[0]);
    const fim = parseDateString(parts[1]);
    if (!inicio || !fim || inicio > fim) return null;

    const agora = new Date();
    return (agora >= inicio && agora <= fim) ? item : null;
}
