<?PHP

require_once("../conexao/banco.php");

$cliente  = $_REQUEST['txt_cliente'];
$data 	= $_REQUEST['txt_data'];
$tipo_pag 	= $_REQUEST['txt_tipo_pag'];
	
$sql = "insert into tb_vendas (cli_codigo, ven_data, ven_tipo_pagamento) 
								values ('$cliente', '$data', '$tipo_pag')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;


$sql2 = "select * from tb_vendas ORDER BY ven_codigo DESC";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!");
$dados = mysqli_fetch_array($sql2);

$venda = $dados['ven_codigo'];

header("Location: ../form_cadastro_venda.php?venda=$venda");


?>

