<?php
header('Content-Type: application/json');
require_once 'funcoes.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['token'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Token não recebido']);
    exit;
}

$token = $input['token'];
$dadosToken = verificaUltimoTokenAtivo($token);

if ($dadosToken && isset($dadosToken['cpf'])) {
    echo json_encode(['status' => 'sucesso', 'cpf' => $dadosToken['cpf']]);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'CPF não encontrado para o token']);
}
