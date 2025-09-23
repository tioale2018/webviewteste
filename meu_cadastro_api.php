<?php
session_start();
include_once "funcoes.php";

if (!isset($_SESSION['cpf'])) {
    header('Location: login.php');
    exit;
}


$cpf = $_SESSION['cpf'] ?? null;
$id = $_SESSION['id_user'] ?? null;

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
  <title>Meu Cadastro</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
  <?php include_once "navbar.php"; ?>
  <main class="container py-3">
    <h1 class="h5 fw-bold mb-3">Meus Dados</h1>
    <div id="cadastro-card"></div>
    <div id="message" class="alert" style="display: none;" role="alert"></div>
    <button id="edit-button" class="btn btn-primary mt-3" onclick="toggleEdit()">Editar Dados</button>
  </main>
  <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
    const jwtToken = '<?= $token ?>';
    let isEditing = false;
    let currentData = null;

    // Carrega os dados quando a página iniciar
    $(document).ready(function() {
      loadUserData();
    });
  </script>
  <script>
    function formatDate(dateString) {
 
        const parts = dateString.split('-');
        const year = parts[0];
        const month = parts[1];
        const day = parts[2];

  
      return `${day}/${month}/${year}`;
    }

  </script>
  <script>
    function showMessage(message, type = 'success') {
      const messageDiv = $('#message');
      messageDiv.removeClass('alert-success alert-danger')
        .addClass(`alert-${type}`)
        .html(message)
        .show();
      
      setTimeout(() => messageDiv.hide(), 5000);
    }

    function toggleEdit() {
      isEditing = !isEditing;
      loadUserData();
      $('#edit-button').text(isEditing ? 'Cancelar' : 'Editar Dados');
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function renderEditableField(label, value, fieldName, type = 'text') {
      const safeValue = escapeHtml(value || '');
      if (isEditing) {
        if (type === 'date' && value) {
          // Garante que a data está no formato YYYY-MM-DD para o input date
          const parts = value.split('-');
          if (parts.length === 3) {
            value = `${parts[0]}-${parts[1]}-${parts[2].split(' ')[0]}`;
          }
        }
        return `
          <div class="mb-3">
            <label class="form-label text-muted small">${escapeHtml(label)}</label>
            <input type="${type}" class="form-control" name="${escapeHtml(fieldName)}" 
              value="${type === 'date' ? value : safeValue}" ${fieldName === 'cpf' ? 'readonly' : ''}>
          </div>`;
      } else {
        const displayValue = type === 'date' && value ? formatDate(value) : (value || '-');
        return `
          <div class="mb-3">
            <label class="form-label text-muted small">${escapeHtml(label)}</label>
            <div class="fw-semibold text-dark">${escapeHtml(displayValue)}</div>
          </div>`;
      }
    }

    function saveChanges(formData) {
      // Adiciona o id do usuário aos dados
      formData.id_user = currentData.id_user;
      
      $.ajax({
        url: 'https://cultura.rj.gov.br/desenvolve-cultura/api/atualizar-usuario.php',
        type: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + jwtToken
        },
        data: JSON.stringify(formData),
        success: function(response) {
          showMessage('Dados atualizados com sucesso!', 'success');
          isEditing = false;
          loadUserData();
          $('#edit-button').text('Editar Dados');
        },
        error: function(err) {
          showMessage('Erro ao atualizar os dados. Tente novamente.', 'danger');
          console.error('Erro na atualização:', err);
        }
      });
    }

    function loadUserData() {
      $.ajax({
        url: 'https://cultura.rj.gov.br/desenvolve-cultura/api/usuario.php',
        type: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + jwtToken
        },
        beforeSend: function() {
          $('#cadastro-card').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status">');
        },
        success: function(data) {
          currentData = data.usuario;
          let usuario = data.usuario;
          let pessoa_juridica = usuario.cpf && usuario.cpf.length > 11;
          let html = `<div class="card shadow-sm p-3 mb-4 rounded-4">
                      ${isEditing ? '<form id="update-form">' : ''}`;

        html += renderEditableField(pessoa_juridica ? 'Razão Social' : 'Nome Completo', usuario.nome, 'nome');
        html += renderEditableField(pessoa_juridica ? 'Nome Fantasia (caso haja)' : 'Nome Artístico (caso haja)', usuario.nomefantasia, 'nomefantasia');
        html += renderEditableField(pessoa_juridica ? 'CNPJ' : 'CPF', usuario.cpf, 'cpf');

        if (pessoa_juridica) {
          html += renderEditableField('MEI?', usuario.cpfmei ? 'Sim' : 'Não', 'cpfmei');
          html += renderEditableField('CPF do Responsável', usuario.cpfmei, 'cpfmei');
        } else {
          html += renderEditableField('Data de Nascimento', usuario.nascimento, 'nascimento', 'date');
        }

        html += renderEditableField(pessoa_juridica ? 'Inscrição Estadual' : 'RG', usuario.rg, 'rg');
        html += renderEditableField(pessoa_juridica ? 'Inscrição Municipal' : 'Órgão', usuario.orgao, 'orgao');
        html += renderEditableField('E-mail', usuario.email, 'email', 'email');
        html += renderEditableField('E-mail Alternativo', usuario.email_alternativo, 'email_alternativo', 'email');
        html += renderEditableField('Telefone', usuario.telefone, 'telefone', 'tel');
        html += renderEditableField('Celular', usuario.celular, 'celular', 'tel');
        html += renderEditableField('CEP', usuario.cep, 'cep');
        html += renderEditableField('Endereço', usuario.endereco, 'endereco');
        html += renderEditableField('Número', usuario.numero, 'numero');
        html += renderEditableField('Complemento', usuario.complemento, 'complemento');
        html += renderEditableField('Bairro', usuario.bairro, 'bairro');
        html += renderEditableField('Município', usuario.municipio, 'municipio');
        html += renderEditableField('UF', usuario.uf, 'uf');

        if (isEditing) {
          html += '<button type="submit" class="btn btn-success w-100">Salvar Alterações</button>';
        }

        html += isEditing ? '</form>' : '';
        html += '</div>';

        $('#cadastro-card').html(html);

        if (isEditing) {
          $('#update-form').on('submit', function(e) {
            e.preventDefault();
            const formData = {};
            const form = $(this);
            
            form.find('input').each(function() {
              formData[this.name] = this.value;
            });
            
            saveChanges(formData);
          });
        }
      },
      error: function(err) {
        const htmlErro = '<div class="alert alert-danger" role="alert">Erro ao carregar os dados. Tente novamente mais tarde.</div>';
        $('#cadastro-card').html(htmlErro);
        console.error('Erro na requisição:', err);
      }
    }) };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>