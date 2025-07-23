<?php
header('Content-Type: application/json');

// Obter a Server Key de uma variável de ambiente segura
$serverKey = ''; // ⚠️ configure essa variável no seu servidor

// Lê o JSON enviado no body da requisição
$input = json_decode(file_get_contents('php://input'), true);

// Verificações básicas
if (!$serverKey) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'FIREBASE_API_KEY não está definida no ambiente.']);
    exit;
}

$token = $input['token'] ?? null;
$title = $input['title'] ?? 'Notificação';
$body  = $input['body'] ?? 'Você recebeu uma nova mensagem.';

if (!$token) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Token do dispositivo não informado.']);
    exit;
}

// Monta os dados da notificação
$notification = [
    'title' => $title,
    'body' => $body,
];

$data = [
    'to' => $token,
    'notification' => $notification,
    'priority' => 'high',
];

$headers = [
    'Authorization: key=' . $serverKey,
    'Content-Type: application/json',
];

// Envia a requisição para o FCM
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Retorna a resposta
echo json_encode([
    'success' => $httpCode === 200,
    'response' => json_decode($response, true),
]);
