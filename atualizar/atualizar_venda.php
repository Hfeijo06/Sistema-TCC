<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];
	
$credito 		= $_REQUEST['txt_credito'];
$tipo 	= $_REQUEST['txt_tipo_venda'];
$cliente 		= $_REQUEST['txt_cliente'];
$data_venda 	= $_REQUEST['txt_data_lanc'];
$data_entrega 		= $_REQUEST['txt_data_ent'];
$status 		= $_REQUEST['txt_status'];

$sql = "update tb_vendas set 
					cre_codigo = '$credito', 
					ven_tipo_venda = '$tipo', 
					cli_codigo = '$cliente',
					ven_data_lancamento = '$data_venda',
					ven_data_entrega = '$data_entrega',
					ven_status_entrega = '$status'
				where 
					ven_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../form_atualizar_itens_venda.php?venda=$id");

?>
