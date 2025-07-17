<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">

  <?php include_once "navbar.php";
  $id = $_SESSION['id_user'] ?? null;
  $result = getDadosUsuario();
  // print_r(getDadosUsuario());
  // echo strlen($result['cpf']);
  if (strlen($result['cpf']) > 11) {
    $pessoa_juridica = true;
  } else {
    $pessoa_juridica = false;
  }
  ?>

  <main class="container py-3">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <div class="alert alert-success" role="alert">
        Dados atualizados com sucesso!
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="alert alert-danger" role="alert">
        Ocorreu um erro ao atualizar os dados. Por favor, tente novamente.
      </div>
    <?php endif; ?>
    
    <h1 class="h5 fw-bold mb-3">Editar Dados</h1>

    <form action="./forms/meu_cadastro.php" method="POST">
      <input type="hidden" name="id_user" value="<?= $id ?>">
      <div class="mb-3">
        <label for="nome" class="form-label"><?= $pessoa_juridica ? "Razão Social" : "Nome Completo" ?></label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($result['nome'] ?? '') ?>" readonly required>
      </div>

      <div class="mb-3">
        <label for="nomeFantasia" class="form-label"><?= $pessoa_juridica ? "Nome Fantasia (caso haja)" : "Nome Artístico (caso haja)" ?></label>
        <input type="text" class="form-control" id="nomeFantasia" name="nomeFantasia" value="<?= $result['nomefantasia'] ?? '' ?>">
      </div>




      <div class="mb-3">
        <label for="cpf" class="form-label"><?= $pessoa_juridica ? "CNPJ" : "CPF" ?></label>
        <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($result['cpf'] ?? '') ?>" <?= $pessoa_juridica ? ' ' : '' ?> readonly required>

      </div>
      <?php if ($pessoa_juridica) { ?>
        <div class="mb-3">
          <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" id="mei" name="mei" <?= isset($result['cpfmei']) && $result['cpfmei'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="mei">
              MEI?
            </label>
          </div>
        </div>

        <div class="mb-3" id="cpfmeidiv" style="display:none;">
          <label for="cpfmei">CPF do Responsável</label>
          <input type="text" class="form-control" name="cpfmei" id="cpfmei" placeholder="CPF Responsável pelo MEI" value="<?= htmlspecialchars($result['cpfmei'] ?? '') ?>">
        </div>

      <?php } else { ?>
        <div class="mb-3">
          <label for="nascimento" class="form-label">Data de Nascimento</label>
          <input type="date" class="form-control" id="nascimento" name="nascimento" value="<?= htmlspecialchars($result['nascimento'] ?? '') ?>">
        </div>
      <?php } ?>



      <div class="mb-3">
        <label for="rg" class="form-label"><?= $pessoa_juridica ? "Inscrição Estadual" : "RG" ?></label>
        <input type="text" class="form-control" id="rg" name="rg" value="<?= htmlspecialchars($result['rg'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="orgao" class="form-label"><?= $pessoa_juridica ? "Inscrição Municipal" : "Órgão" ?></label>
        <input type="text" class="form-control" id="orgao" name="orgao" value="<?= htmlspecialchars($result['orgao'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($result['email'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="emailAlt" class="form-label">E-mail Alternativo</label>
        <input type="email" class="form-control" id="emailAlt" name="emailAlt" value="<?= $result['email_alternativo'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" name="telefone" value="<?= $result['telefone'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="celular" class="form-label">Celular</label>
        <input type="tel" class="form-control" id="celular" name="celular" value="<?= $result['celular'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" class="form-control" id="cep" name="cep" value="<?= $result['cep'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="endereco" name="endereco" value="<?= $result['endereco'] ?? '' ?>">
        <?php if (isset($_SESSION['erro_endereco'])): ?>
          <span class="text-danger"><?= $_SESSION['erro_endereco'] ?></span>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="numero" class="form-label">Número</label>
        <input type="text" class="form-control" id="numero" name="numero" value="<?= $result['numero'] ?? '' ?>">

      </div>

      <div class="mb-3">
        <label for="complemento" class="form-label">Complemento</label>
        <input type="text" class="form-control" id="complemento" name="complemento" value="<?= $result['complemento'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="bairro" class="form-label">Bairro</label>
        <input type="text" class="form-control" id="bairro" name="bairro" value="<?= $result['bairro'] ?? '' ?>">

      </div>

      <div class="mb-3">
        <label for="municipio" class="form-label">Município</label>
        <input type="text" class="form-control" id="municipio" name="municipio" value="<?= $result['municipio'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="uf" class="form-label">UF</label>
        <input type="text" class="form-control" id="uf" name="uf" value="<?= $result['uf'] ?? '' ?>">
      </div>
      <button type="submit" class="btn btn-primary w-100">Atualizar e fechar</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
      $(document).ready(function() {
            // Masks
            $('#telefone').mask('(00) 00000-0000');
            $('#celular').mask('(00) 00000-0000');
            var isPessoaJuridica = <?= json_encode($pessoa_juridica) ?>;
            var cpfField = $('#cpf');
            if (isPessoaJuridica) {
              cpfField.mask('00.000.000/0000-00', {
                reverse: true
              });
            } else {
              cpfField.mask('000.000.000-00', {
                reverse: true
              });
            }
            $('#cep').mask('00000-000');
            $('#cpfmei').mask('000.000.000-00', {
              reverse: true
            });


           
             if ($('#mei').is(':checked')) {
                $('#cpfmeidiv').attr('hidden', false).show();
              }
               // Toggle MEI CPF field visibility
            $('#mei').change(function() {
              if ($(this).is(':checked')) {
                $('#cpfmeidiv').show();
              } else {
                $('#cpfmeidiv').hide();
              }
            });


              // CEP Validation
              $('#cep').blur(function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep != "") {
                  var validacep = /^[0-9]{8}$/;
                  if (validacep.test(cep)) {
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                      if (!("erro" in dados)) {
                        $("#endereco").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#municipio").val(dados.localidade);
                        $("#uf").val(dados.uf);
                      } else {
                        console.log('CEP não encontrado');
                      }
                    });
                  } else {
                    console.log('CEP inválido');
                  }
                }
              });
            });
    </script>
  </main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>