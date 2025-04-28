<?PHP

require_once('../conexao/banco.php');

$id 	= $_REQUEST['cre_codigo'];

$sql    = "delete from tb_credito where cre_codigo = '$id'";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../form_cadastro_receber.php");

?>


