<?php
header('Content-Type: application/json');
require_once 'funcoes.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['token'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Token não recebido']);
    exit;
}

$token = trim($input['token']);
$dadosMensagem = carregarMensagensToken($token);

// echo $token . ' - ' . (json_encode($dadosToken));
// die();

if ($dadosMensagem) {
    echo json_encode(['status' => 'sucesso',
                      'mensagem' => $dadosMensagem
                    ]);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'CPF não encontrado para o token']);
}
