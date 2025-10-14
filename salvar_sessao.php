<?php
session_start();
include_once "funcoes.php";

$data = json_decode(file_get_contents('php://input'), true);
$token = $_POST['token'] ?? '';

if ($data && isset($data['user'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['cpf'] = $data['user']['cpf'];
    $_SESSION['email'] = $data['user']['email'];
    $_SESSION['nome'] = $data['user']['nome'];
    $_SESSION['id_user'] = $data['user']['id_user'];
    $_SESSION['tipo_doc'] = $data['user']['tipo_doc'];
    // $_SESSION['token'] = $token;
    $_SESSION['token'] = $data['user']['token'];

    // Salva o token do dispositivo
    salvarToken($data['user']['cpf'], $data['user']['token']);
    
    echo json_encode(['success' => true, 'token' => $data['user']['token']]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}