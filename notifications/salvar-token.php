<?php
include_once "../bootstrap.php";
include_once "../conexao.php";
include_once "../funcoes.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$cpf = $data['cpf'] ?? null;
$token = $data['token'] ?? null;

file_put_contents('log.txt', json_encode($data));

$salvarToken = salvarToken($cpf, $token);

if ($cpf && $token) {
    try {
         $stmt = $connPDO->prepare("SELECT * FROM tokens WHERE cpf = :cpf AND token = :token AND ativo = 0");
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute([$cpf, $token]);
            if ($stmt->rowCount() > 0) {
                // Se o token estiver vinculado ao CPF, atualiza o status para ativo
                $stmt = $connPDO->prepare("UPDATE tokens SET ativo = 1 WHERE cpf = :cpf AND token = :token");
                $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->execute([$cpf, $token]);
                return ['success' => true, 'message' => 'Token já vinculado ao CPF.'];
            } else {
            $stmt = $connPDO->prepare("INSERT IGNORE INTO tokens (cpf, token) VALUES (?, ?)");
            $stmt->execute([$cpf, $token]);
            return ['success' => true, 'message' => 'Token vinculado ao CPF com sucesso.'];
            }
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
