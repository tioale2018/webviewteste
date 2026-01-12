<?php
$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
$is_production = ($server_name === 'webview.sophx.com.br' );

$base_path = $is_production ? '/home/comsophxadm/webview.sophx.com.br' : __DIR__;
$autoload_path = $base_path . '/vendor/autoload.php';

if (!file_exists($autoload_path)) {
    die("Erro: O arquivo 'vendor/autoload.php' não foi encontrado. Execute 'composer install' no diretório: $base_path");
}

require $autoload_path;

use Dotenv\Dotenv;

// Definir o caminho do .env baseado no ambiente
if ($is_production) {
    $env_path = '/home/comsophxadm';
    $env_file = '.env';
} else {
    $env_path = __DIR__;
    // Usar .env.local em desenvolvimento, fallback para .env
    $env_file = file_exists(__DIR__ . '/.env.local') ? '.env.local' : '.env';
}

if (!file_exists($env_path . '/' . $env_file)) {
    die("Erro: O arquivo $env_file não foi encontrado em: $env_path");
}

$dotenv = Dotenv::createImmutable($env_path, $env_file);
$dotenv->load();
