<?php
include_once "../conexao.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$cpf = $data['cpf'] ?? null;
$token = $data['token'] ?? null;

if ($cpf && $token) {
    try {
        $stmt = $connPDO->prepare("INSERT IGNORE INTO tokens (cpf, token) VALUES (?, ?)");
        $stmt->execute([$cpf, $token]);
        echo json_encode(['success' => true, 'message' => 'Token vinculado ao CPF com sucesso.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao salvar.',
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'CPF ou token ausente.']);
}
