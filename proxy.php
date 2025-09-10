<?php
// Ativa log de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Log da requisição para debug
error_log("Requisição recebida: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);

// Se for uma requisição OPTIONS, retorna apenas os headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configurações do proxy
$api_base_url = 'https://cultura.rj.gov.br/desenvolve-cultura/api/';

// Pega o endpoint da query string
$endpoint = $_GET['endpoint'] ?? '';
unset($_GET['endpoint']); // Remove endpoint dos parâmetros GET

if (empty($endpoint)) {
    http_response_code(400);
    echo json_encode(['error' => 'Endpoint não especificado']);
    exit;
}

// Constrói a URL completa com os parâmetros GET restantes
$url = $api_base_url . $endpoint;
if (!empty($_GET)) {
    $url .= '?' . http_build_query($_GET);
}

// Log da URL para debug
error_log("URL da API: " . $url);

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
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        // Converte o formato do header (ex: HTTP_ACCEPT -> Accept)
        $header = str_replace('_', '-', substr($key, 5));
        $headers[] = "$header: $value";
    }
}
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers[] = 'Authorization: ' . $_SERVER['HTTP_AUTHORIZATION'];
}
$headers[] = 'Content-Type: application/json';
$headers[] = 'User-Agent: DesenvolveCultura-Proxy/1.0';

// Log dos headers para debug
error_log("Headers enviados: " . json_encode($headers));

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Executa a requisição
$response = curl_exec($ch);

// Log da resposta para debug
error_log("Resposta recebida. Status: " . curl_getinfo($ch, CURLINFO_HTTP_CODE));

// Pega o código de status HTTP
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Se houver erro no CURL
if ($response === false) {
    $error = curl_error($ch);
    error_log("Erro CURL: " . $error);
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao acessar a API',
        'details' => $error,
        'url' => $url
    ]);
    curl_close($ch);
    exit;
}

// Pega o content type da resposta
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// Fecha a conexão CURL
curl_close($ch);

// Define o código de status HTTP da resposta
http_response_code($http_code);

// Verifica se a resposta é JSON válido
$json_response = json_decode($response);
if ($json_response === null && json_last_error() !== JSON_ERROR_NONE) {
    error_log("Resposta não é JSON válido: " . $response);
    http_response_code(502);
    echo json_encode([
        'error' => 'Resposta inválida da API',
        'details' => 'A API retornou uma resposta que não é JSON válido',
        'content_type' => $content_type
    ]);
    exit;
}

// Retorna a resposta
header('Content-Type: ' . ($content_type ?: 'application/json'));
echo $response;
