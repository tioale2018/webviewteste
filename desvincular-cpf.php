<?php
header('Content-Type: application/json');
require_once 'funcoes.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['token'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Token não recebido']);
    exit;
}

if (!isset($input['cpf'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'CPF não recebido']);
    exit;
}

$token = trim($input['token']);
$cpf = trim($input['cpf']);
$desvincularTokenCPF = desvincularTokenCPF($token, $cpf);

if ($desvincularTokenCPF) {
    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Documento desvinculado com sucesso']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao desvincular documento']);
}


