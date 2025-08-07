<?php

include_once "conexao.php";
include_once "login.php";

function getDadosProjeto($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM projetos WHERE projetos.id_project = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return null; // Nenhum projeto encontrado
    }

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getDadosInfoProjeto($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT 
                                tbinfoprojetos.*,
                                cat_acultural.textocategoria AS nome_acultural,
                                cat_categoria.textocategoria AS nome_categoria,
                                cat_concorrencia.textocategoria AS nome_concorrencia
                                FROM tbinfoprojetos
                                LEFT JOIN tbcategorias AS cat_acultural ON tbinfoprojetos.acultural = cat_acultural.id
                                LEFT JOIN tbcategorias AS cat_categoria ON tbinfoprojetos.categoria = cat_categoria.id
                                LEFT JOIN tbcategorias AS cat_concorrencia ON tbinfoprojetos.concorrencia = cat_concorrencia.id
                                WHERE tbinfoprojetos.idprojeto = :id;");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return null; // Nenhum projeto encontrado
    }

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getDatasEdital($id)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbconfiguracoes WHERE idedital = :id AND ativo = 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        http_response_code(404);
        return ['success' => false, 'message' => 'Nenhuma configuração encontrada.'];}
}

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


function carregarVinculados($token)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT cpf FROM tokens WHERE token = :token AND ativo = 1");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os CPFs vinculados ao token
    } else {
        return []; // Nenhum CPF encontrado
    }
}

function carregarMensagensToken($token)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT tokens.cpf, tbnotificacoes.* FROM tokens INNER JOIN tbnotificacoes ON tokens.cpf = tbnotificacoes.cpf WHERE token = :token AND tbnotificacoes.ativo = 0 AND tbnotificacoes.lido = 1 ORDER BY tbnotificacoes.enviado_em DESC");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos as mensagens vinculadas ao token
    } else {
        return []; // Nenhum CPF encontrado
    }
}

function carregarMensagensCPF($cpf)
{
    global $connPDO;

    $stmt = $connPDO->prepare("SELECT * FROM tbnotificacoes WHERE cpf = :cpf AND ativo = 0 AND lido = 1 ORDER BY enviado_em DESC
");
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos as mensagens vinculadas ao cpf
    } else {
        return []; // Nenhum CPF encontrado
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
            // Verifica se o token já está vinculado ao CPF
            $stmt = $connPDO->prepare("SELECT * FROM tokens WHERE cpf = :cpf AND token = :token AND ativo = 0");
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                // Se o token estiver vinculado ao CPF, atualiza o status para ativo
                $stmt = $connPDO->prepare("UPDATE tokens SET ativo = 1 WHERE cpf = :cpf AND token = :token");
                $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->execute();
                return ['success' => true, 'message' => 'Token já vinculado ao CPF.'];
            } else {
            $stmt = $connPDO->prepare("INSERT IGNORE INTO tokens (cpf, token) VALUES (?, ?)");
            $stmt->execute([$cpf, $token]);
            return ['success' => true, 'message' => 'Token vinculado ao CPF com sucesso.'];
            }
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


// Autenticação do usuario para consumir a API

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function generate_jwt($payload, $secret) {
    // Header
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];
    $encoded_header = base64url_encode(json_encode($header));

    // Payload
    // You can add 'exp' (expiration time) here for example:
    $payload['exp'] = time() + 3600; // Token expires in 1 hour
    $encoded_payload = base64url_encode(json_encode($payload));

    // Signature
    $signature_input = "$encoded_header.$encoded_payload";
    $signature = hash_hmac('sha256', $signature_input, $secret, true);
    $encoded_signature = base64url_encode($signature);

    // Combine to form the JWT
    return "$encoded_header.$encoded_payload.$encoded_signature";
}

function validate_jwt($jwt, $secret) {
    list($encoded_header, $encoded_payload, $encoded_signature) = explode('.', $jwt);

    // Decode header and payload (for inspection, not for validation)
    $header = json_decode(base64url_decode($encoded_header), true);
    $payload = json_decode(base64url_decode($encoded_payload), true);

    // Re-calculate signature
    $signature_input = "$encoded_header.$encoded_payload";
    $expected_signature = hash_hmac('sha256', $signature_input, $secret, true);
    $encoded_expected_signature = base64url_encode($expected_signature);

    // Compare signatures
    if ($encoded_signature !== $encoded_expected_signature) {
        return false; // Invalid signature
    }

    // Optional: Check expiration time
    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return false; // Token expired
    }

    return $payload; // Return decoded payload if valid
}


function getJwtSecret() {
    return 'qANyiZNu1zDgwfVYJCaLKEFmweJjfFQt2Ygj2nW0KLef7OR9UvL0HbNQpx97Naa8';
}