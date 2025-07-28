<?php

include_once "conexao.php";
include_once "login.php";



function getEditaisAtivos()
{
    global $connPDO;

    if ($connPDO == null) {
        throw new Exception("N foi possivel conectar ao banco de dados.");
    }

    $stmt = $connPDO->prepare("SELECT * FROM tbeditais WHERE totalinscritos = 1000 ORDER BY datacria DESC");
    $stmt->execute();

    if ($stmt == null) {
        throw new Exception("N foi possivel executar a query.");
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result == null) {
        throw new Exception("N foi possivel obter os resultados da query.");
    }

    return $result;
}

function getEditaisEncerrados()
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbeditais WHERE totalinscritos = 0 AND datafecha <" . time() . " ORDER BY datafecha DESC");
    $stmt->execute();


    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    return $result;
}

function getDadosEdital($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbeditais WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getLegislacoesEdital($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbpublicacaoedital WHERE idedital = :id AND tipo=2 AND ativo=1 ORDER BY sequencia");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function getPublicacoesEdital($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbpublicacaoedital WHERE idedital = :id AND tipo=1 AND ativo=1 ORDER BY sequencia");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function getAnexosObrigatorios($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("select * from tbanexos_edital 
                                                              inner join tbanexos on tbanexos.id = tbanexos_edital.idanexo 
                                                              where obrigatorio=1 and idedital= :id
                                                              order by tbanexos_edital.id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $rowCount = $stmt->rowCount();
    if ($rowCount == 0) {
        $output = "Nenhum anexo obrigatorio encontrado.";
    } else {
        $anexos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $output = "<b><p class='mt-2'>Anexos Obrigatórios</p></b>";
        $output .= "<ol class='list-group list-group-numbered'>";
        foreach ($anexos as $anexo) {
            $output .= "<li class='list-group-item'>" . htmlspecialchars($anexo['descricao']) . "</li>";
        }
        $output .= "</ol>";
    }
    return $output;
}

function getAnexosOpcionais($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("select * from tbanexos_edital 
                                                              inner join tbanexos on tbanexos.id = tbanexos_edital.idanexo 
                                                              where obrigatorio=0 and idedital= :id
                                                              order by tbanexos_edital.id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $rowCount = $stmt->rowCount();
    if ($rowCount == 0) {
        return "Nenhum anexo opcional encontrado.";
    } else {
        $anexos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $output = "<b><p class='mt-2'>Anexos Opcionais</p></b>";
        $output .= "<ol class='list-group list-group-numbered'>";
        foreach ($anexos as $anexo) {
            $output .= "<li class='list-group-item '>" . htmlspecialchars($anexo['descricao']) . "</li>";
        }
        $output .= "</ol>";
    }
    return $output;
}


function getDadosUsuario()
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM users WHERE id_user = :id");
    $stmt->bindParam(':id', $_SESSION['id_user'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getProjetosSubmetidosUsuario($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("select tbeditais.status as statusedital, tbeditais.titulo as titulo_edital, tbeditais.grupo, tbeditais.totalinscritos, projetos_ablanc.contemplado as contablanc, projetos_icms.contemplado as conticms, projetos.* from projetos INNER join tbeditais on projetos.idedital=tbeditais.id left join projetos_ablanc on projetos_ablanc.idprojeto = projetos.id_project left join projetos_icms on projetos_icms.idprojeto = projetos.id_project where projetos.status<99 and submetido=1 and projetos.user_input = :id order by id_project desc");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function getProjetosNaoSubmetidosUsuario($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("select tbeditais.status as statusedital, tbeditais.titulo as titulo_edital, tbeditais.grupo, tbeditais.totalinscritos, projetos_ablanc.contemplado as contablanc, projetos_icms.contemplado as conticms, projetos.* from projetos INNER join tbeditais on projetos.idedital=tbeditais.id left join projetos_ablanc on projetos_ablanc.idprojeto = projetos.id_project left join projetos_icms on projetos_icms.idprojeto = projetos.id_project where projetos.status<99 and submetido=0 and projetos.user_input = :id order by id_project desc");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function getProjetoSubmetidoEdital($id)
{
    global $connPDO;
    $stmt = $connPDO->prepare("SELECT * FROM projetos WHERE idedital = :id AND user_input = :user_input AND submetido = 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_input', $_SESSION['cpf'], PDO::PARAM_INT);
    $stmt->execute();

    // echo "<pre>Debug Row Count: "; var_dump($stmt->rowCount()); echo "</pre>";

    // Retorna true se houver projeto submetido, false caso contrário
    return $stmt->rowCount() > 0;
}

function getPendencias($id)
{
    global $connPDO;
    $stmt = $connPDO->prepare("SELECT * FROM tbpendencias WHERE idedital = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function verificaUltimoTokenAtivo($token)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tokens WHERE token = :token AND ativo = 1 ORDER BY created_at DESC LIMIT 1");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC); // Token ativo
    } else {
        return false; // Token n o encontrado
    }
}


function getTokensAtivos()
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tokens WHERE ativo = 1");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os tokens ativos
    } else {
        return []; // Nenhum token ativo encontrado
    }
}


function salvarToken($cpf, $token)
{
    global $connPDO;

    if ($cpf && $token) {
        try {
            $stmt = $connPDO->prepare("INSERT IGNORE INTO tokens (cpf, token) VALUES (?, ?)");
            $stmt->execute([$cpf, $token]);
            return ['success' => true, 'message' => 'Token vinculado ao CPF com sucesso.'];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'success' => false,
                'message' => 'Erro ao salvar.',
                'error' => $e->getMessage()
            ];
        }
    } else {
        http_response_code(400);
        return ['success' => false, 'message' => 'CPF ou token ausente.'];
    }
}


function desvincularTokenCPF($token, $cpf)
{
    global $connPDO;

    if ($token && $cpf) {
        try {
            $stmt = $connPDO->prepare("UPDATE tokens SET ativo = 0 WHERE token = :token AND cpf = :cpf");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->execute();
            return ['success' => true, 'message' => 'Token desvinculado com sucesso.'];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                'success' => false,
                'message' => 'Erro ao desvincular.',
                'error' => $e->getMessage()
            ];
        }
    } else {
        http_response_code(400);
        return ['success' => false, 'message' => 'Token ou CPF ausente.'];
    }
}