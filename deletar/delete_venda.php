<?php

require_once('../conexao/banco.php');

$id = $_REQUEST['ven_codigo'];

try {
    // Inicia a transação
    mysqli_begin_transaction($con);

    // Deleta os registros da tabela tb_fluxo_caixa relacionados às contas a receber desta venda
    $sql_fluxo_caixa = "DELETE FROM tb_fluxo_caixa WHERE cr_codigo IN (SELECT cr_codigo FROM tb_contas_receber WHERE ven_codigo = '$id')";
    mysqli_query($con, $sql_fluxo_caixa) or throw new Exception("Erro ao deletar registros do fluxo de caixa!");

    // Deleta os itens da venda
    $sql_itens_venda = "DELETE FROM tb_itens_venda WHERE ven_codigo = '$id'";
    mysqli_query($con, $sql_itens_venda) or throw new Exception("Erro ao deletar itens da venda!");

    // Deleta as contas a receber relacionadas a esta venda
    $sql_contas_receber = "DELETE FROM tb_contas_receber WHERE ven_codigo = '$id'";
    mysqli_query($con, $sql_contas_receber) or throw new Exception("Erro ao deletar contas a receber!");

    // Deleta a venda
    $sql_venda = "DELETE FROM tb_vendas WHERE ven_codigo = '$id'";
    mysqli_query($con, $sql_venda) or throw new Exception("Erro ao deletar venda!");

    // Confirma a transação
    mysqli_commit($con);

    // Redireciona após sucesso
    header("Location: ../consulta_venda.php");
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    mysqli_rollback($con);

    // Mostra a mensagem de erro
    die("Erro na transação: " . $e->getMessage());
}

?>
