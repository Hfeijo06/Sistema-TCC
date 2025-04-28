<?php

require_once('../conexao/banco.php');

$id = $_REQUEST['com_codigo'];

try {
    // Inicia a transação
    mysqli_begin_transaction($con);

    // Deleta os registros da tabela tb_fluxo_caixa relacionados às contas a pagar desta compra
    $sql_fluxo_caixa = "DELETE FROM tb_fluxo_caixa WHERE cp_codigo IN (SELECT cp_codigo FROM tb_contas_pagar WHERE com_codigo = '$id')";
    mysqli_query($con, $sql_fluxo_caixa) or throw new Exception("Erro ao deletar registros do fluxo de caixa!");

    // Deleta os itens da compra
    $sql_itens_compra = "DELETE FROM tb_itens_compra WHERE com_codigo = '$id'";
    mysqli_query($con, $sql_itens_compra) or throw new Exception("Erro ao deletar itens da compra!");

    // Deleta as contas a pagar relacionadas a esta compra
    $sql_contas_pagar = "DELETE FROM tb_contas_pagar WHERE com_codigo = '$id'";
    mysqli_query($con, $sql_contas_pagar) or throw new Exception("Erro ao deletar contas a pagar!");

    // Deleta a compra
    $sql_compra = "DELETE FROM tb_compra WHERE com_codigo = '$id'";
    mysqli_query($con, $sql_compra) or throw new Exception("Erro ao deletar compra!");

    // Confirma a transação
    mysqli_commit($con);

    // Redireciona após sucesso
    header("Location: ../consulta_compra.php");
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    mysqli_rollback($con);

    // Mostra a mensagem de erro
    die("Erro na transação: " . $e->getMessage());
}

?>
