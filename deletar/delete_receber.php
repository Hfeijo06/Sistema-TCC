<?PHP

require_once('../conexao/banco.php');

$id 	= $_REQUEST['cr_codigo'];

$sql    = "delete from tb_contas_receber where cr_codigo = '$id'";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_receber.php");

?>


