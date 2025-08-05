<?php
include_once "../conexao.php";
include_once "../funcoes.php";
// session_start();
header('Content-Type: application/json');
$cpf = $_SESSION['cpf'] ?? null;
$submetidos = getProjetosSubmetidosUsuario($cpf);
$nao_submetidos = getProjetosNaoSubmetidosUsuario($cpf);
echo json_encode([
  'submetidos' => $submetidos,
  'nao_submetidos' => $nao_submetidos
]);
