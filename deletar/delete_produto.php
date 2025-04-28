<?php

require_once('../conexao/banco.php');

$id = $_REQUEST['pro_codigo'];

try {
    // Inicia a transação
    mysqli_begin_transaction($con);

    // Deleta os registros do estoque relacionados a este produto
    $sql_estoque = "DELETE FROM tb_estoque WHERE pro_codigo = '$id'";
    mysqli_query($con, $sql_estoque) or throw new Exception("Erro ao deletar registros do estoque!");

    // Deleta os itens da venda relacionados a este produto
    $sql_itens_venda = "DELETE FROM tb_itens_venda WHERE pro_codigo = '$id'";
    mysqli_query($con, $sql_itens_venda) or throw new Exception("Erro ao deletar itens da venda!");

    // Deleta os itens de compra relacionados a este produto
    $sql_itens_compra = "DELETE FROM tb_itens_compra WHERE pro_codigo = '$id'";
    mysqli_query($con, $sql_itens_compra) or throw new Exception("Erro ao deletar itens de compra!");

    // Deleta o produto
    $sql_produto = "DELETE FROM tb_produtos WHERE pro_codigo = '$id'";
    mysqli_query($con, $sql_produto) or throw new Exception("Erro ao deletar produto!");

    // Confirma a transação
    mysqli_commit($con);

    // Redireciona após sucesso
    header("Location: ../consulta_produto.php");
    exit();
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    mysqli_rollback($con);

    // Mostra a mensagem de erro
    die("Erro na transação: " . $e->getMessage());
}

?>
