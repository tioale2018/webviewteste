<?php
$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
$is_production = ($server_name === 'webview.sophx.com.br');

$base_path = $is_production ? '/home/comsophxadm/webview.sophx.com.br' : __DIR__;
$autoload_path = $base_path . '/vendor/autoload.php';

if (!file_exists($autoload_path)) {
    die("Erro: O arquivo 'vendor/autoload.php' não foi encontrado. Execute 'composer install' no diretório: $base_path");
}

require $autoload_path;

use Dotenv\Dotenv;

// $env_path = $is_production ? '/home/comsophxadm' : __DIR__;
$env_path = 'home/comsophxadm';

if (!file_exists($env_path . '/.env')) {
    die("Erro: O arquivo .env não foi encontrado em: $env_path");
}

$dotenv = Dotenv::createImmutable($env_path);
$dotenv->load();
