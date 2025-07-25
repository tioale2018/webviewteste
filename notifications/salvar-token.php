<?php
include_once "../bootstrap.php";
include_once "../conexao.php";
include_once "../funcoes.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$cpf = $data['cpf'] ?? null;
$token = $data['token'] ?? null;


$response = salvarToken($cpf, $token);
echo json_encode($response);


