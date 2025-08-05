<?php
include_once "../conexao.php";
include_once "../funcoes.php";
header('Content-Type: application/json');
$editais = getEditaisAtivos();
echo json_encode($editais);
