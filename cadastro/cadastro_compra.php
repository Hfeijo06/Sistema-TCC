<?PHP

require_once('../conexao/banco.php');

$fornecedor 	= $_REQUEST['txt_fornecedor'];
$data 	= $_REQUEST['txt_data'];
$tipo_pag 	= $_REQUEST['txt_tipo_pag'];

$sql = "insert into tb_compra (for_codigo, com_data, com_tipo_pagamento) 
								values ('$fornecedor', '$data', '$tipo_pag')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../html/consulta_compra.php");

?>



