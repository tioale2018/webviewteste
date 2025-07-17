<?php

include_once "conexao.php";
include_once "login.php";



function getEditaisAtivos() {
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

function getEditaisEncerrados() {
     global $connPDO;
    
    $stmt = $connPDO->prepare("SELECT * FROM tbeditais WHERE totalinscritos = 0 AND datafecha <" . time() . " ORDER BY datafecha DESC");
    $stmt->execute();

    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $result;
}

function getDadosEdital($id) {
    global $connPDO;
    
    $stmt = $connPDO->prepare("SELECT * FROM tbeditais WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}

function getLegislacoesEdital($id) {
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbpublicacaoedital WHERE idedital = :id AND tipo=2 AND ativo=1 ORDER BY sequencia");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;

}

function getPublicacoesEdital($id) {
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbpublicacaoedital WHERE idedital = :id AND tipo=1 AND ativo=1 ORDER BY sequencia");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;

}

function getAnexosObrigatorios($id) {
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

function getAnexosOpcionais($id) {
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


function getDadosUsuario() {
    global $connPDO;
    
    $stmt = $connPDO->prepare("SELECT * FROM users WHERE id_user = :id");
    $stmt->bindParam(':id', $_SESSION['id_user'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}

function getProjetosSubmetidosUsuario($id) {
    global $connPDO;
    
    $stmt = $connPDO->prepare("select tbeditais.status as statusedital, tbeditais.titulo as titulo_edital, tbeditais.grupo, tbeditais.totalinscritos, projetos_ablanc.contemplado as contablanc, projetos_icms.contemplado as conticms, projetos.* from projetos INNER join tbeditais on projetos.idedital=tbeditais.id left join projetos_ablanc on projetos_ablanc.idprojeto = projetos.id_project left join projetos_icms on projetos_icms.idprojeto = projetos.id_project where projetos.status<99 and submetido=1 and projetos.user_input = :id order by id_project desc");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $result;
}

function getProjetosNaoSubmetidosUsuario($id) {
    global $connPDO;
    
    $stmt = $connPDO->prepare("select tbeditais.status as statusedital, tbeditais.titulo as titulo_edital, tbeditais.grupo, tbeditais.totalinscritos, projetos_ablanc.contemplado as contablanc, projetos_icms.contemplado as conticms, projetos.* from projetos INNER join tbeditais on projetos.idedital=tbeditais.id left join projetos_ablanc on projetos_ablanc.idprojeto = projetos.id_project left join projetos_icms on projetos_icms.idprojeto = projetos.id_project where projetos.status<99 and submetido=0 and projetos.user_input = :id order by id_project desc");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $result;
}

function getProjetoSubmetidoEdital($id) {
    global $connPDO;
    $stmt = $connPDO->prepare("SELECT * FROM projetos WHERE idedital = :id AND user_input = :user_input AND submetido = 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_input', $_SESSION['cpf'], PDO::PARAM_INT);
    $stmt->execute();
    
    // echo "<pre>Debug Row Count: "; var_dump($stmt->rowCount()); echo "</pre>";

    // Retorna true se houver projeto submetido, false caso contrário
    return $stmt->rowCount() > 0;
}

function getPendencias($id) {
    global $connPDO;
    $stmt = $connPDO->prepare("SELECT * FROM tbpendencias WHERE idedital = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
