<?php
$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'];

// Salvar em banco de dados ou arquivo
file_put_contents("tokens.txt", $token . PHP_EOL, FILE_APPEND);
?>
