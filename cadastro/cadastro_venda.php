<?php
require_once("../conexao/banco.php");

$credito = $_REQUEST['txt_credito'];
$cliente = $_REQUEST['txt_cliente'];
$data_lanc = $_REQUEST['txt_data_lanc'];
$data_ent = $_REQUEST['txt_data_ent'];
$tipo = $_REQUEST['txt_tipo_venda'];
$status = $_REQUEST['txt_status'];


// Insere os dados da venda
$sql = "INSERT INTO tb_vendas (cre_codigo, cli_codigo, ven_data_lancamento, ven_data_entrega, ven_tipo_venda, ven_status_entrega) 
        VALUES ('$credito', '$cliente', '$data_lanc','$data_ent', '$tipo', '$status')";

mysqli_query($con, $sql) or die("Erro ao cadastrar a venda!");

$sql2 = "select * from tb_vendas ORDER BY ven_codigo DESC";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!");
$dados = mysqli_fetch_array($sql2);

$venda = $dados['ven_codigo'];

// Mensagem de sucesso
$_SESSION['success_message'] = "Venda cadastrada com sucesso!";
header("Location: ../form_cadastro_venda.php?venda=$venda");
exit();
?>
