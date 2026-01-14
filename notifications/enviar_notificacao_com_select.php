<?php
require __DIR__ . '/../vendor/autoload.php';

include_once "../funcoes.php";

use Google\Client;
use GuzzleHttp\RequestOptions;

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
// $body  = $input['body'] ?? 'blabla.';
$body = ['mensagem1', 'mensagem2', 'mensagem3'];

/*if (!$token) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Token do dispositivo não informado.']);
    exit;
}
*/
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
$tokens = [ 'fyn1BVJQT-ChtK191R97N7:APA91bEdZ5IoNZ84vxsXqBZrewEXxrWdbZGRuMhgS_m-VRloiugsvjozpRhBGWgQ-Z5nknT2NTyPZF7hoXBMLo2O3J9815uXyg6MproSqFUgPemXiwv7o_M', 'fRQEDqwfRpeO1l-FDsqSXB:APA91bHtHT4FRQhxBsF0YFKdggzzVb5-sktDEuTPtNie1kIyPcsqML6ZGCG00JOIuMyvgYQpu4oVJd0J1xpH4Ijy59KJgnRBOJeRpQGY9aPOZJGNyrXCdHw'  ];

$tokens = array_column(getTokensAtivos(), 'token');

// print_r($tokens);
// die();

foreach ($tokens as $key =>$token) {
    $message['message']['token'] = $token;
    $message['message']['notification']['body'] = $body[$key] ?? 'Mensagem padrão';
    // print_r($message);
    // die();
    // Envia a mensagem
    $response[] = $httpClient->request('POST',
        "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
        [
            RequestOptions::JSON => $message
        ]
***REMOVED***;
}

// $response = $httpClient->request('POST',
//     "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
//     [
//         RequestOptions::JSON => $message
//     ]
// );


//criar um foreach para armazenar as respostas de cada token em um array
foreach ($response as $key => $value) {
    $decoded = json_decode($value->getBody(), true);
    $response[$key] = $decoded;

    if (isset($decoded['error']['status']) && $decoded['error']['status'] === 'NOT_FOUND') {
        // Token inválido, desativar no banco
        $tokenInvalido = $tokens[$key];
        $stmt = $connPDO->prepare("UPDATE tokens SET ativo = 0 WHERE token = :token");
        $stmt->execute(['token' => $tokenInvalido]);
    }
}

// Retorna a resposta
echo json_encode([
    'success' => true,
    'response' => json_encode($response, JSON_PRETTY_PRINT),
]);
