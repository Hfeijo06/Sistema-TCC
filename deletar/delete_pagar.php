<?php
require_once('../conexao/banco.php');

$id = $_REQUEST['cp_codigo'];

// Inicia uma transação
mysqli_begin_transaction($con);

try {
    // Deleta os registros da tabela tb_fluxo_caixa relacionados ao cr_codigo diretamente
    $sql_fluxo_caixa = "DELETE FROM tb_fluxo_caixa WHERE cp_codigo = '$id'";
    if (!mysqli_query($con, $sql_fluxo_caixa)) {
        throw new Exception("Erro ao deletar registros do fluxo de caixa: " . mysqli_error($con));
    }

    // Deleta o registro da tabela tb_contas_pagar
    $sql = "DELETE FROM tb_contas_pagar WHERE cp_codigo = '$id'";
    if (!mysqli_query($con, $sql)) {
        throw new Exception("Erro ao deletar a conta a pagar: " . mysqli_error($con));
    }

    // Confirma a transação
    mysqli_commit($con);

    // Redireciona para a página de consulta
    header("Location: ../consulta_pagar.php");
    exit();

} catch (Exception $e) {
    // Reverte a transação em caso de erro
    mysqli_rollback($con);

    // Exibe a mensagem de erro
    die("Erro: " . $e->getMessage());
}


