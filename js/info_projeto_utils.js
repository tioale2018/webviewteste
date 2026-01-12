/**
 * info_projeto_utils.js
 *
 * Shared utilities for project information pages
 * Used by: info_projeto_dados_api.php, info_projeto_fluxo_api.php, info_projeto_chat_api.php
 *
 * Contains:
 * - Date parsing and validation functions
 * - API communication wrapper
 * - Shared HTML builders
 * - Navigation state management
 */

// ==================== DATE UTILITIES ====================

/**
 * Parses a date string in various formats
 * @param {string|number} value - Date string (ISO, Unix timestamp, etc.)
 * @returns {Date|null} Parsed date or null if invalid
 */
function parseDateString(value) {
    if (!value) return null;
    const s = String(value).trim();

    // Unix timestamp em segundos (10 digits)
    if (/^\d{10}$/.test(s)) {
        return new Date(parseInt(s, 10) * 1000);
    }

    // Substitui espaço por 'T' para parsing mais consistente (ISO-like)
    const t = s.replace(' ', 'T');
    const d = new Date(t);
    return isNaN(d) ? null : d;
}

/**
 * Checks if a configuration period is currently active
 * @param {Array} datas - Array of date configuration objects
 * @param {string} chaveCampo1 - Configuration key to search for (e.g., 'exibenotarecurso')
 * @returns {Object|null} Configuration object if active, null otherwise
 */
function getItemSeAtivo(datas, chaveCampo1) {
    if (!Array.isArray(datas)) return null;

    const item = datas.find(d => d.campo1 === chaveCampo1);
    if (!item || !item.campo2) return null;

    // campo2 expected format: "YYYY-MM-DD HH:mm,YYYY-MM-DD HH:mm" (start,end)
    const parts = item.campo2.split(',').map(p => p.trim()).filter(Boolean);
    if (parts.length < 2) return null;

    const inicio = parseDateString(parts[0]);
    const fim = parseDateString(parts[1]);
    if (!inicio || !fim) return null;

    // Se as datas estiverem invertidas (início > fim), considerar como inválido
    if (inicio > fim) return null;

    // Return the start date (original logic - indicates period is configured)
    // Note: Original implementation doesn't check if NOW is within the period
    // It just returns the start date if valid dates are found
    return inicio;
}

// ==================== DATA UTILITIES ====================

/**
 * Safely handles null, undefined, empty, or invalid date values
 * @param {any} v - Value to check
 * @returns {string} Original value or empty string if invalid
 */
function safe(v) {
    return (v === null || v === undefined || v === '' || v === '0000-00-00') ? '' : v;
}

// ==================== HTML BUILDERS ====================

/**
 * Builds the common header HTML for project pages
 * Includes: page title, contact email, project status, and subsection placeholder
 * @param {Object} dados - Project data object from API
 * @returns {string} HTML string
 */
function buildMainHtml(dados) {
    let html = '';

    // Page title
    html += `<h1 class="h5 fw-bold mb-3">Inscrição de proposta de projeto para ${dados.titulo_edital || ''}</h1>`;

    // Contact email card
    html += `<div class="card">
        <div class="card-body">
            <p class="mb-0">Dúvidas relacionadas ao edital devem ser encaminhadas para o e-mail
            <a href="mailto:${dados.linha1 ? dados.linha1 : 'suportedesenvolvecultura@cultura.rj.gov.br'}">
            ${dados.linha1 ? dados.linha1 : 'suportedesenvolvecultura@cultura.rj.gov.br'}</a></p>
        </div>
    </div>`;

    // Status card
    html += `<div class="card">
        <div class="section-title">Andamento do processo</div>
        <div class="card-body">
            <p>Seu projeto <strong>${dados.titulo || ''}</strong> foi submetido para análise em
            <strong>${dados.datasubmete ? new Date(dados.datasubmete * 1000).toLocaleDateString('pt-BR') : ''}</strong>
            sob o número <strong>${dados.id_project || ''}</strong>.</p>
            <p>Fase atual: <strong class="text-primary">${dados.nomepublico || ''}</strong></p>
        </div>
    </div>`;

    // Placeholder for subsection content (will be populated by page-specific render functions)
    html += `<div id="project-subsection" class="mb-3"></div>`;

    return html;
}

// ==================== API COMMUNICATION ====================

/**
 * Fetches project data from the API
 * @param {string} projectId - Project ID
 * @param {string} jwtToken - JWT authentication token
 * @param {function} successCallback - Callback function on success (receives data object)
 * @param {function} errorCallback - Callback function on error (receives error object)
 */
function fetchProjectData(projectId, jwtToken, successCallback, errorCallback) {
    $.ajax({
        url: 'https://desenvolvecultura.rj.gov.br/desenvolve-cultura/api/info_projeto.php?id=' + encodeURIComponent(projectId),
        type: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + jwtToken
        },
        data: JSON.stringify({
            id: projectId
        }),
        beforeSend: function() {
            // Show loading spinner
            $('#project-info').html(
                '<div class="d-flex justify-content-center">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="visually-hidden">Carregando...</span>' +
                '</div></div>'
        ***REMOVED***;
        },
        success: function(data) {
            if (typeof successCallback === 'function') {
                successCallback(data);
            }
        },
        error: function(err) {
            console.error('Erro na requisição:', err);
            if (typeof errorCallback === 'function') {
                errorCallback(err);
            }
        }
    });
}

// ==================== NAVIGATION ====================

/**
 * Sets the active state for bottom navigation bar
 * @param {string} id - ID of the navigation element to activate
 */
function setActiveNav(id) {
    // Remove active class from all nav items
    $('#info_projeto_dados, #info_projeto_fluxo, #info_projeto_chat').removeClass('text-warning');

    // Add active class to selected item
    $('#' + id).addClass('text-warning');
}
