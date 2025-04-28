<?php
    
require_once('../conexao/banco.php');

$id = $_REQUEST['ven_codigo'];

try {
    // Inicia a transação
    mysqli_begin_transaction($con);

    // Deleta os registros da tabela tb_fluxo_caixa relacionados às contas a receber desta venda
    $sql_fluxo_caixa = "DELETE FROM tb_fluxo_caixa WHERE cr_codigo IN (
        SELECT cr_codigo FROM tb_contas_receber WHERE parc_codigo IN (
            SELECT parc_codigo FROM tb_parcelas WHERE pag_codigo IN (
                SELECT pag_codigo FROM tb_pagamento WHERE ven_codigo = '$id'
            )
        )
    )";
    mysqli_query($con, $sql_fluxo_caixa) or throw new Exception("Erro ao deletar registros do fluxo de caixa!");

    // Deleta as contas a receber relacionadas a estas parcelas
    $sql_contas_receber = "DELETE FROM tb_contas_receber WHERE parc_codigo IN (
        SELECT parc_codigo FROM tb_parcelas WHERE pag_codigo IN (
            SELECT pag_codigo FROM tb_pagamento WHERE ven_codigo = '$id'
        )
    )";
    mysqli_query($con, $sql_contas_receber) or throw new Exception("Erro ao deletar contas a receber!");

    // Deleta as parcelas relacionadas aos pagamentos desta venda
    $sql_parcelas = "DELETE FROM tb_parcelas WHERE pag_codigo IN (
        SELECT pag_codigo FROM tb_pagamento WHERE ven_codigo = '$id'
    )";
    mysqli_query($con, $sql_parcelas) or throw new Exception("Erro ao deletar parcelas!");

    // Deleta os pagamentos relacionados a esta venda
    $sql_pagamento = "DELETE FROM tb_pagamento WHERE ven_codigo = '$id'";
    mysqli_query($con, $sql_pagamento) or throw new Exception("Erro ao deletar pagamentos!");

    // Deleta os itens da venda
    $sql_itens_venda = "DELETE FROM tb_itens_venda WHERE ven_codigo = '$id'";
    mysqli_query($con, $sql_itens_venda) or throw new Exception("Erro ao deletar itens da venda!");

    // Deleta a venda
    $sql_venda = "DELETE FROM tb_vendas WHERE ven_codigo = '$id'";
    mysqli_query($con, $sql_venda) or throw new Exception("Erro ao deletar venda!");

    // Confirma a transação
    mysqli_commit($con);

    // Redireciona após sucesso
    header("Location: ../consulta_venda.php");
    exit();
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    mysqli_rollback($con);

    // Mostra a mensagem de erro
    die("Erro na transação: " . $e->getMessage());
}
?>
