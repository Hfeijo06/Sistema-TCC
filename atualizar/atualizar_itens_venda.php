<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$item 	= $_REQUEST['txt_item'];
$estoque 	= $_REQUEST['txt_estoque'];
$valor 	= $_REQUEST['txt_valor'];
$qtde 	= $_REQUEST['txt_qtde'];
$total 	= $_REQUEST['txt_total'];

$sql = "update tb_itens_venda set 
					ITE_CODIGO = '$item',
					EST_CODIGO = '$estoque',
					ITE_VALOR_UNIT = '$valor', 
					ITE_QTDE = '$qtde', 
					ITE_TOTAL = '$total'
				where 
					ITE_CODIGO = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../html/consulta_itens.php");

?>
