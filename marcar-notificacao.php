<?php
header('Content-Type: application/json');
include_once "conexao.php";
include_once "funcoes.php";

$data = json_decode(file_get_contents('php://input'), true);
$tokensAtivos = getTokensAtivos();
$id = $data['id'] ?? null;
$token = $data['token'] ?? null;
$cpf = $data['cpf'] ?? null;



// if (!$token || !$cpf) {
//     echo json_encode(['status' => 'erro', 'mensagem' => 'Token ou CPF ausente.']);
//     exit;
// }


// if ($cpf !== $_SESSION['cpf']) {
//     echo json_encode(['status' => 'erro', 'mensagem' => 'CPF inválido.']);
//     exit;
// }

$listaTokens = array_column($tokensAtivos, 'token'); // extrai só os valores da coluna 'token'

if (!in_array($token, $listaTokens)) {
   echo json_encode(['status' => 'erro', 'mensagem' => 'Token inválido.']);
    exit;
}


if ($id) {
  $sql = "UPDATE tbnotificacoes SET lido = 0 WHERE id = ?";
  $stmt = $connPDO->prepare($sql);
  $stmt->execute([$id]);
  echo json_encode(['status' => 'sucesso']);
} else {
  echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido']);
}
