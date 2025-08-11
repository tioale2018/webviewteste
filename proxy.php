<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Se for uma requisição OPTIONS, retorna apenas os headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configurações do proxy
$api_base_url = 'http://cultura.rj.gov.br/desenvolve-cultura/api/';

// Pega o endpoint da query string
$endpoint = $_GET['endpoint'] ?? '';

if (empty($endpoint)) {
    http_response_code(400);
    echo json_encode(['error' => 'Endpoint não especificado']);
    exit;
}

// Constrói a URL completa
$url = $api_base_url . $endpoint;

// Inicializa o CURL
$ch = curl_init();

// Configura o CURL para a requisição
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Se for POST, configura o método e os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    
    // Pega o corpo da requisição
    $inputJSON = file_get_contents('php://input');
    if (!empty($inputJSON)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
    }
}

// Copia os headers da requisição original
$headers = [];
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers[] = 'Authorization: ' . $_SERVER['HTTP_AUTHORIZATION'];
}
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Executa a requisição
$response = curl_exec($ch);

// Pega o código de status HTTP
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Se houver erro no CURL
if ($response === false) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao acessar a API',
        'details' => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

// Fecha a conexão CURL
curl_close($ch);

// Define o código de status HTTP da resposta
http_response_code($http_code);

// Retorna a resposta
echo $response;
