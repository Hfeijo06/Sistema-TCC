<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];
	
$cliente 		= $_REQUEST['txt_cliente'];
$item 	= $_REQUEST['txt_item'];
$tipo 	= $_REQUEST['txt_tipo'];
$nome 		= $_REQUEST['txt_nome'];
$obs 	= $_REQUEST['txt_obs'];
$data 		= $_REQUEST['txt_data'];

$sql = "update tb_vendas set 
					CLI_CODIGO = '$cliente', 
					ITE_CODIGO = '$item', 
					VEN_FORMA_PAG = '$tipo',
					VEN_NOME = '$nome',
					VEN_OBSERVACAO = '$obs',
					VEN_DATA_VENDA = '$data'
				where 
					PRO_CODIGO = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../html/consulta_produto.php");

?>
