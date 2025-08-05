<?php
require __DIR__ . '/../vendor/autoload.php';
include_once "../funcoes.php";

use Google\Client;
use GuzzleHttp\RequestOptions;

if (getenv('SERVER_NAME') === 'webview.sophx.com.br') {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=/home/comsophxadm/firebase_credentials.json');
} else {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../firebase_credentials.json');
}

$client = new Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/firebase.messaging');
$httpClient = $client->authorize();

$projectId = $_ENV['FIREBASE_ID_PROJECT'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);
$title = $input['title'] ?? 'Notificação';

function enviarMensagensPendentes($connPDO, $httpClient, $projectId, $title = 'Notificação')
{
    $stmt = $connPDO->prepare("
        SELECT n.id AS mensagem_id, n.cpf, n.mensagem, t.token 
        FROM tbnotificacoes n
        JOIN tokens t ON n.cpf = t.cpf
        WHERE n.ativo = 1 AND t.ativo = 1
        ORDER BY n.id ASC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $agrupadas = [];
    foreach ($rows as $row) {
        $agrupadas[$row['mensagem_id']]['mensagem'] = $row['mensagem'];
        $agrupadas[$row['mensagem_id']]['cpf'] = $row['cpf'];
        $agrupadas[$row['mensagem_id']]['tokens'][] = $row['token'];
    }

    $resumo = ['enviadas' => 0, 'erros' => 0];
    $detalhes = [];

    foreach ($agrupadas as $mensagem_id => $dados) {
        $tokensComErro = 0;

        foreach ($dados['tokens'] as $token) {
            $message = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $dados['mensagem']
                    ],
                    'data' => [
                        'extra_info' => 'valor opcional'
                    ]
                ]
            ];

            try {
                $res = $httpClient->request(
                    'POST',
                    "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                    [RequestOptions::JSON => $message]
                );

                $decoded = json_decode($res->getBody(), true);
                $resumo['enviadas']++;

                if (isset($decoded['error'])) {
                    $resumo['erros']++;
                    $tokensComErro++;

                    $connPDO->prepare("UPDATE tokens SET ativo = 0 WHERE token = :token")
                        ->execute(['token' => $token]);

                    $detalhes[] = [
                        'status' => 'erro',
                        'mensagem_id' => $mensagem_id,
                        'token' => $token,
                        'firebase_response' => $decoded
                    ];
                } else {
                    $detalhes[] = [
                        'status' => 'success',
                        'mensagem_id' => $mensagem_id,
                        'token' => $token,
                        'firebase_response' => $decoded
                    ];
                }
            } catch (\Exception $e) {
                $erro = json_decode($e->getResponse()->getBody(), true);
                $resumo['erros']++;
                $tokensComErro++;

                $errosInvalidos = ['NOT_FOUND', 'UNREGISTERED', 'INVALID_ARGUMENT'];
                if (
                    isset($erro['error']['status']) &&
                    in_array($erro['error']['status'], $errosInvalidos)
                ) {
                    $connPDO->prepare("UPDATE tokens SET ativo = 0 WHERE token = :token")
                        ->execute(['token' => $token]);
                }

                $detalhes[] = [
                    'status' => 'erro',
                    'mensagem_id' => $mensagem_id,
                    'token' => $token,
                    'firebase_response' => $erro
                ];
            }
        }

        // Marcar como enviada se todos os tokens foram processados com sucesso
        if ($tokensComErro === 0) {
            $connPDO->prepare("UPDATE tbnotificacoes SET ativo = 0, enviado_em = NOW() WHERE id = :id")
                ->execute(['id' => $mensagem_id]);
        }
    }

    return ['resumo' => $resumo, 'detalhes' => $detalhes];
}

$resultado = enviarMensagensPendentes($connPDO, $httpClient, $projectId, $title);

echo json_encode([
    'success' => true,
    'resumo' => $resultado['resumo'],
    'detalhes' => $resultado['detalhes']
], JSON_PRETTY_PRINT);
