<?php
$server_name = $_SERVER['SERVER_NAME'];
$servername  = "localhost";
$username    = "root";
$password    = "";
$database    = "bdwebview";

if ($server_name == 'webview.sophx.com.br') {
  $username = "comsophxadm_userbd";
  $password = "wOJCqMYH=[i@";
  $database = "comsophxadm_bdwebview";
} 

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese', 'pt_BR.utf8');

try {
  $connPDO = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  exit("Connection failed");
}

?>