<?PHP

require_once('../conexao/banco.php');

$id 	= $_REQUEST['est_codigo'];

$sql    = "delete from tb_estoque where est_codigo = '$id'";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_estoque.php");

?>


