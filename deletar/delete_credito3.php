<?php

require_once('../conexao/banco.php');

if (isset($_REQUEST['cre_codigo']) && isset($_REQUEST['ven_codigo'])) {
    $id = $_REQUEST['cre_codigo'];
    $venda = $_REQUEST['ven_codigo'];

    $sql = "DELETE FROM tb_credito WHERE cre_codigo = '$id'";

    mysqli_query($con, $sql) or die("Erro na sql!");

    header("Location: ../form_atualizar_venda.php?venda=$venda");
} else {
    die("Parâmetros cre_codigo ou ven_codigo não foram passados corretamente.");
}

?>
