<?PHP

require_once('../conexao/banco.php');

$venda 	= $_REQUEST['txt_venda'];
$valor 	= $_REQUEST['txt_valor'];
$vencimento 	= $_REQUEST['txt_vencimento'];
$status 	= $_REQUEST['txt_status'];

$sql = "insert into tb_contas_receber (ven_codigo, cr_valor, cr_vencimento, cr_status) 
								values ('$venda', '$valor', '$vencimento', '$status')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_receber.php");

?>



