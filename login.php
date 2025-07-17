<?php
include_once 'conexao.php';
session_start();
if (isset($_POST['login'])) {
    // Get the form data 
    $documento = $_POST['documento'];
    $password = $_POST['senha'];

    // die($documento . ' ' . $password);

    // Debug: Verificar se dados do POST estão corretos
    error_log('POST documento: ' . $documento);
    error_log('POST senha: ' . $password);

    if (!isset($connPDO)) {
        error_log('ERRO: connPDO não está definido!');
        die('Erro de conexão com o banco de dados.');
    }

    // Prepare and bind the SQL statement 
    $stmt = $connPDO->prepare("SELECT id_user, cpf, nome, password, email, tipo_doc FROM users WHERE cpf = :documento");
    $stmt->bindParam(':documento', $documento);

    $executou = $stmt->execute();
    // Debug: Verificar se a query executou
    error_log('Query executada: ' . ($executou ? 'sim' : 'não'));
    echo "QUERY EXECUTADA";

    // Check if the user exists 
    if ($stmt->rowCount() > 0) {

        // Fetch the result 
        $row = $stmt->fetch();
        error_log('Usuário encontrado: ' . print_r($row, true));

        // Verify the password  (ASSUMING PASSWORD WILL BE RE-HASHED WITH PASSWORD_HASH)
        if (md5($password) === $row['password']) {
            // Set the session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['cpf'] = $row['cpf'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['tipo_doc'] = $row['tipo_doc'];
            error_log('Login OK, redirecionando...');

            // Redirect to the user's dashboard 
            header("Location: lista_editais.php");
            exit;
        } else {
            error_log('Senha incorreta!');            
            header("Location: index.php?error=1");
            exit;
        }
    } else {
        error_log('Usuário não encontrado!');
        header("Location: index.php?error=1");
        exit;
    }
} else {
    error_log('POST login não setado.');
}

?>