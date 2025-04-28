<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$despesa	= $_REQUEST['txt_despesa'];
$valor 	= $_REQUEST['txt_valor'];
$vencimento 	= $_REQUEST['txt_vencimento'];
$status 	= $_REQUEST['txt_status'];

$sql = "update tb_contas_pagar set 
					des_codigo = '$despesa', 
					cp_valor = '$valor', 
					cp_vencimento = '$vencimento',
                    cp_status = '$status'
				where 
					cp_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_pagar.php");

?>
