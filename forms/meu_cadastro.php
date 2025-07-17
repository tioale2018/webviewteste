<?php
include_once "../conexao.php";
session_start();
// Não inclua login.php aqui, pois ele faz redirecionamento e lógica de login

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

 $cpfmei = '';
 $nascimento = '';

  $nome = test_input($_POST["nome"]) ?? '';
  $nomefantasia = test_input($_POST["nomeFantasia"]) ?? '';
  $cpfmei = test_input($_POST["cpfmei"]) ?? '';
  $nascimento = isset($_POST["nascimento"]) ? test_input($_POST["nascimento"]) : '0000-00-00';
  $rg  = test_input($_POST["rg"]) ?? '';
  $orgao = test_input($_POST["orgao"]) ?? '';
  $email = test_input($_POST["email"]) ?? '';
  $emailAlt = test_input($_POST["emailAlt"]) ?? '';
  $telefone = test_input($_POST["telefone"]) ?? '';
  $celular = test_input($_POST["celular"]) ?? '';
  $cep = test_input($_POST["cep"]) ?? '';
  $endereco = test_input($_POST["endereco"]) ?? '';
  $bairro = test_input($_POST["bairro"]) ?? '';
  $municipio = test_input($_POST["municipio"]) ?? '';
  $uf = test_input($_POST["uf"]) ?? '';
  $numero = test_input($_POST["numero"]) ?? '';
  $complemento = test_input($_POST["complemento"]) ?? '';
  $id_user = $_POST['id_user'];
    // Debug: Log the POST data
    
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';


        $stmt = $connPDO->prepare("UPDATE users SET nome = :nome, nomefantasia = :nomefantasia, cpfmei = :cpfmei, nascimento = :nascimento, rg = :rg, orgao = :orgao, email = :email, email_alternativo = :emailAlt, telefone = :telefone, celular = :celular, cep = :cep, endereco = :endereco, bairro = :bairro, municipio = :municipio, uf = :uf, numero = :numero, complemento = :complemento, cadcompleto = 1 WHERE id_user = :id_user");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':nomefantasia', $nomefantasia);
        $stmt->bindParam(':cpfmei', $cpfmei);
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':orgao', $orgao);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':emailAlt', $emailAlt);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':municipio', $municipio);
        $stmt->bindParam(':uf', $uf);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':id_user', $id_user);

        // echo '<pre>';
        // echo "QUERY: ".$stmt->queryString."\n";
        // echo "PARAMS:\n";
        // echo "nome: $nome\n";
        // echo "nomefantasia: $nomefantasia\n";
        // echo "cpfmei: $cpfmei\n";
        // echo "nascimento: $nascimento\n";
        // echo "rg: $rg\n";
        // echo "orgao: $orgao\n";
        // echo "email: $email\n";
        // echo "emailAlt: $emailAlt\n";
        // echo "telefone: $telefone\n";
        // echo "celular: $celular\n";
        // echo "cep: $cep\n";
        // echo "endereco: $endereco\n";
        // echo "bairro: $bairro\n";
        // echo "uf: $uf\n";
        // echo "numero: $numero\n";
        // echo "complemento: $complemento\n";
        // echo "id_user: ".$id_user."\n";
        // echo '</pre>';
        // die();
        if ($stmt->execute()) {
            header("Location: ../meu_cadastro.php?success=1");
            exit;
        } else {
            header("Location: ../meu_cadastro.php?error=1");
            exit;
        }
    } else {
        header("Location: ../meu_cadastro.php?error=1");
        exit;
    }

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}