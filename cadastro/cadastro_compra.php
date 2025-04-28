<?PHP

require_once("../conexao/banco.php");

$fornecedor  = $_REQUEST['txt_fornecedor'];
$despesa 	= $_REQUEST['txt_despesa'];
$data 	= $_REQUEST['txt_data'];
$tipo_pag 	= $_REQUEST['txt_tipo_pag'];
	
$sql = "insert into tb_compra (for_codigo, com_data, com_tipo_pagamento, des_codigo) 
								values ('$fornecedor', '$data', '$tipo_pag', '$despesa')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;


$sql2 = "select * from tb_compra ORDER BY com_codigo DESC";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!");
$dados = mysqli_fetch_array($sql2);

$compra = $dados['com_codigo'];

header("Location: ../form_cadastro_compra.php?compra=$compra");


?>

