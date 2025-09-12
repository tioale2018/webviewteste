<?php
include_once "../conexao.php";
include_once "../funcoes.php";
// session_start();

header('Access-Control-Allow-Origin: *'); // ou coloque apenas https://webview.sophx.com.br em produção
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');
header('Content-Type: application/json');

// $cpf = $_SESSION['cpf'] ?? null;
// --- Captura header Authorization corretamente ---
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$authHeader = $headers['authorization'] ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? '');

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['error' => 'Token ausente ou malformado']);
    exit();
}

$jwt = $matches[1];
$secret = getJwtSecret();

// --- Valida JWT ---
try {
    $payload = validate_jwt($jwt, $secret);

    if (!$payload) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido ou expirado']);
        exit();
    }

    $cpf = $payload['cpf'] ?? null;
    if (!$cpf) {
        http_response_code(400);
        echo json_encode(['error' => 'CPF não encontrado no token']);
        exit();
    }

    // --- Busca projetos ---
    $submetidos = getProjetosSubmetidosUsuario($cpf);
    $nao_submetidos = getProjetosNaoSubmetidosUsuario($cpf);

    // --- Retorna JSON ---
    echo json_encode([
        'submetidos' => $submetidos,
        'nao_submetidos' => $nao_submetidos
    ]);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno: ' . $e->getMessage()]);
    exit();
}