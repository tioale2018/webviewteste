<?php
include_once "../conexao.php";
include_once "../funcoes.php";
header('Content-Type: application/json');
$editais = getEditaisEncerrados();
echo json_encode($editais);
