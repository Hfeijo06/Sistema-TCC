<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$credito	= $_REQUEST['txt_credito'];
$valor 	= $_REQUEST['txt_valor'];
$vencimento 	= $_REQUEST['txt_vencimento'];
$status 	= $_REQUEST['txt_status'];

$sql = "update tb_contas_receber set 
					cre_codigo = '$credito', 
					cr_valor = '$valor', 
					cr_data_vencimento = '$vencimento',
                    cr_status = '$status'
				where 
					cr_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_receber.php");

?>
