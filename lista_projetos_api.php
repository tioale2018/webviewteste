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
    <title>Meus Projetos - Desenvolve Cultura</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
    <?php if ($cpf): ?>
        <script>
            window.ReactNativeWebView?.postMessage(JSON.stringify({
                tipo: 'autenticacao',
                cpf: '<?= $cpf ?>'
            }));
        </script>
    <?php endif; ?>
    <?php include_once "navbar.php"; ?>
    <main class="container py-3">
        <h1 class="h5 fw-bold mb-3">Meus Projetos</h1>
        <ul class="nav nav-pills mb-3" id="tabs" role="tablist">
            <li class="nav-item m-1" role="presentation">
                <button class="nav-link active" id="tab-abertos" data-bs-toggle="pill" data-bs-target="#pane-abertos" type="button" role="tab">Não submetidos</button>
            </li>
            <li class="nav-item m-1" role="presentation">
                <button class="nav-link" id="tab-submetidos" data-bs-toggle="pill" data-bs-target="#pane-submetidos" type="button" role="tab">Submetidos</button>
            </li>
        </ul>
        <div class="tab-content" id="tabsContent">
            <div class="tab-pane fade show active" id="pane-abertos" role="tabpanel">
                <div id="projetos-abertos-list"></div>
            </div>
            <div class="tab-pane fade" id="pane-submetidos" role="tabpanel">
                <div id="projetos-submetidos-list"></div>
            </div>
        </div>
    </main>
     <script src="./js/jquery-3.7.1.min.js"></script>
    <script>
        const jwtToken = '<?= $token ?>';
        // const cpf = '<?= $cpf ?>';
    </script>
    <script>
        $(function() {
            $.ajax({
                // url: 'http://localhost/desenvolve-cultura/api/projetos.php',
                url: 'https://desenvolvecultura.com.br/api/api_projetos.php',
                type: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwtToken 
                },
                beforeSend: function() {                    
                    $('#projetos-abertos-list').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>');
                    $('#projetos-submetidos-list').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>');
                },
                success: function(data) {
                    let abertos = data.nao_submetidos;
                    let htmlAbertos = '<div class="list-group">';
                    if (!abertos.length) {
                        htmlAbertos += '<div class="list-group-item rounded-3 shadow-sm mb-3"><div class="mb-2"><p class="text-center text-muted">Nenhum projeto aberto para inscrição.</p></div></div>';
                    } else {
                        abertos.forEach(function(proj) {
                            htmlAbertos += `<div class="list-group-item rounded-3 shadow-sm mb-3">
            <div class="mb-2"><small class="text-muted">Código:</small> <span class="fw-semibold">${proj.id_project}</span></div>
            <div class="mb-2"><small class="text-muted">Projeto:</small> <div class="fw-semibold">${proj.titulo}</div></div>
            <div class="mb-2"><small class="text-muted">Oportunidade:</small> <div>${proj.titulo_edital}</div></div>
            <div class="mt-2">`;
                            if (proj.totalinscritos == 0) {
                                htmlAbertos += '<span class="badge bg-danger rounded-pill mb-2">Inscrição Encerrada</span>';
                            } else if (proj.submetido) {
                                htmlAbertos += '<span class="badge bg-warning rounded-pill mb-2">Outro projeto já foi submetido neste edital</span>';
                            } else {
                                htmlAbertos += `<a href="info_projeto.php?id=${proj.id_project}" class="btn btn-sm btn-primary w-100 mb-1">Acompanhe seu projeto</a>`;
                            }
                            htmlAbertos += `</div></div>`;
                        });
                    }
                    htmlAbertos += '</div>';
                    $('#projetos-abertos-list').html(htmlAbertos);

                    let submetidos = data.submetidos;
                    let htmlSubmetidos = '<div class="list-group">';
                    if (!submetidos.length) {
                        htmlSubmetidos += '<div class="list-group-item rounded-3 shadow-sm mb-3"><div class="mb-2"><p class="text-center text-muted">Nenhum projeto submetido.</p></div></div>';
                    } else {
                        submetidos.forEach(function(proj) {
                            htmlSubmetidos += `<div class="list-group-item rounded-3 shadow-sm mb-3">
            <div class="mb-2"><small class="text-muted">Código:</small> <span class="fw-semibold">${proj.id_project}</span></div>
            <div class="mb-2"><small class="text-muted">Projeto:</small> <div class="fw-semibold">${proj.titulo}</div></div>
            <div class="mb-2"><small class="text-muted">Oportunidade:</small> <div>${proj.titulo_edital}</div></div>
          </div>`;
                        });
                    }
                    htmlSubmetidos += '</div>';
                    $('#projetos-submetidos-list').html(htmlSubmetidos);
                },
                error: function(err) {
                    const htmlErro = `<div class="alert alert-danger" role="alert">${err} Erro ao carregar os projetos. Tente novamente mais tarde.</div>`;
                    $('#projetos-submetidos-list').html(htmlErro);
                    $('#projetos-abertos-list').html(htmlErro);
                    console.error('Erro na requisição:', err);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>