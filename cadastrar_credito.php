<?php
include 'conexao/banco.php';

// Verifica se o campo 'nome_despesa' foi enviado
if (isset($_POST['nome_credito'])) {
    $nome_credito = mysqli_real_escape_string($con, $_POST['nome_credito']);

    // Verifica se o campo está vazio
    if (empty($nome_credito)) {
        echo 'error: Nome da despesa não pode ser vazio.';
        exit;
    }

    // Insere a nova despesa na tabela
    $sql = "INSERT INTO tb_credito (cre_nome) VALUES ('$nome_credito')";

    if (mysqli_query($con, $sql)) {
        echo 'success';
    } else {
        // Mostra o erro do MySQL para ajudar no debug
        echo 'error: ' . mysqli_error($con);
    }
} else {
    echo 'error: Dados não recebidos.';
}
?>

