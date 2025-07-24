<?php
require __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use GuzzleHttp\RequestOptions;



// var_dump(file_exists(__DIR__ . '/../firebase_credentials.json'));
// die();

// Carrega as credenciais da conta de serviço

if (getenv('SERVER_NAME') == 'webview.sophx.com.br') {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=/home/comsophxadm/firebase_credentials.json');
} else {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../firebase_credentials.json');
}


$client = new Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/firebase.messaging');

$httpClient = $client->authorize();

// ID do projeto (do seu JSON)
$projectId = $_ENV['FIREBASE_ID_PROJECT'];

// Lê o JSON enviado no body da requisição
$input = json_decode(file_get_contents('php://input'), true);

$token = $input['token'] ?? null;
$title = $input['title'] ?? 'Notificação';
$body  = $input['body'] ?? 'Você recebeu uma nova mensagem.';

if (!$token) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Token do dispositivo não informado.']);
    exit;
}

// Monta a mensagem no formato da API v1
$message = [
    'message' => [
        'token' => $token,
        'notification' => [
            'title' => $title,
            'body' => $body,
        ],
        'data' => [
            'extra_info' => 'valor opcional'
        ]
    ]
];

// Envia a requisição para a API v1
$response = $httpClient->request('POST',
    "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
    [
        RequestOptions::JSON => $message
    ]
);


// Retorna a resposta
echo json_encode([
    'success' => true,
    'response' => json_decode($response->getBody(), true),
]);
