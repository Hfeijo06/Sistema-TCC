<?php
session_start();
require_once("../conexao/banco.php");

$venda = $_REQUEST['txt_venda'];
$tipo_pag = $_REQUEST['txt_tipo_pag'];
$descricao = $_REQUEST['txt_descricao'];

$sql = "INSERT INTO tb_pagamento (ven_codigo, pag_tipo_pag, pag_descricao) 
        VALUES ('$venda', '$tipo_pag', '$descricao')";

mysqli_query($con, $sql) or die("Erro ao cadastrar a venda!");

// Recupera o ID da venda inserida
$pag_id = mysqli_insert_id($con);

if ($tipo_pag == 3) { // Se for crediário, insere as parcelas
    $datas_parcelas = $_REQUEST['txt_data_vencimento']; // Um array de datas
    $valores_parcelas = $_REQUEST['txt_valor']; // Um array de valores

    for ($i = 0; $i < count($datas_parcelas); $i++) {
        $data_venc = $datas_parcelas[$i];
        $valor_parcela = $valores_parcelas[$i]; // Agora está pegando o valor correto de cada parcela

        $sql_parcela = "INSERT INTO tb_parcelas (pag_codigo, parc_numero_parcela, parc_data_vencimento, parc_valor) 
                        VALUES ('$pag_id', '".($i+1)."', '$data_venc', '$valor_parcela')";

        mysqli_query($con, $sql_parcela) or die("Erro ao cadastrar parcela!");
    }
} else{
    $data = $_REQUEST['txt_data_vencimento']; 
    $valor = $_REQUEST['txt_total']; 

    $sql_vista = "INSERT INTO tb_parcelas (pag_codigo, parc_numero_parcela, parc_data_vencimento, parc_valor) 
    VALUES ('$pag_id', '1', '$data', '$valor')";

    mysqli_query($con, $sql_vista) or die("Erro ao cadastrar a vista!");
}

// Mensagem de sucesso
$_SESSION['success_message'] = "Pagamento realizado com sucesso!";
header("Location: ../consulta_receber.php");
exit();

?>