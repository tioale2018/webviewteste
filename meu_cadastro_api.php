<?php

include_once "funcoes.php";


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
  </main>
   <script src="./js/jquery-3.7.1.min.js"></script>
  <script>
    const jwtToken = '<?= $token ?>';
  </script>
  <script>
  $.ajax({
                url: 'https://cultura.rj.gov.br/desenvolve-cultura/api/usuario.php',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwtToken 
                },
                beforeSend: function() {
                    $('#cadastro-card').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status">');
                },
                success: function(usuario) {
      let pessoa_juridica = usuario.cpf && usuario.cpf.length > 11;
     let html = '<div class="card shadow-sm p-3 mb-4 rounded-4">';

const renderField = (label, value) => `
  <div class="mb-3">
    <label class="form-label text-muted small">${label}</label>
    <div class="fw-semibold text-dark">${value || '-'}</div>
  </div>
`;

html += renderField(pessoa_juridica ? 'Razão Social' : 'Nome Completo', usuario.nome);
html += renderField(pessoa_juridica ? 'Nome Fantasia (caso haja)' : 'Nome Artístico (caso haja)', usuario.nomefantasia);
html += renderField(pessoa_juridica ? 'CNPJ' : 'CPF', usuario.cpf);

if (pessoa_juridica) {
  html += renderField('MEI?', usuario.cpfmei ? 'Sim' : 'Não');
  html += renderField('CPF do Responsável', usuario.cpfmei);
} else {
  html += renderField('Data de Nascimento', usuario.nascimento);
}

html += renderField(pessoa_juridica ? 'Inscrição Estadual' : 'RG', usuario.rg);
html += renderField(pessoa_juridica ? 'Inscrição Municipal' : 'Órgão', usuario.orgao);
html += renderField('E-mail', usuario.email);
html += renderField('E-mail Alternativo', usuario.email_alternativo);
html += renderField('Telefone', usuario.telefone);
html += renderField('Celular', usuario.celular);
html += renderField('CEP', usuario.cep);
html += renderField('Endereço', usuario.endereco);
html += renderField('Número', usuario.numero);
html += renderField('Complemento', usuario.complemento);
html += renderField('Bairro', usuario.bairro);
html += renderField('Município', usuario.municipio);
html += renderField('UF', usuario.uf);

html += '</div>';

      $('#cadastro-card').html(html);
    },
                error: function(err) {
                    const htmlErro = '<div class="alert alert-danger" role="alert">Erro ao carregar os dados. Tente novamente mais tarde.</div>';
                    $('#cadastro-card').html(htmlErro);
                    console.error('Erro na requisição:', err);
                }
            });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
