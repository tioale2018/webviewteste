<?php
ob_clean();
header('Content-Type: application/json');
require_once 'funcoes.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cpf'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'cpf não recebido']);
    exit;
}

$cpf = trim($input['cpf']);
$dadosMensagem = carregarMensagensCPF($cpf);

// echo $token . ' - ' . (json_encode($dadosToken));
// die();

if ($dadosMensagem) {
    $contador = 0;
    foreach ($dadosMensagem as $mensagem) {
        $contador = $contador + 1;
    }
    echo json_encode(['status' => 'sucesso',
                      'mensagem' => $dadosMensagem,
                      'contador' => $contador
                    ]);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'CPF não encontrado para o token']);
}
