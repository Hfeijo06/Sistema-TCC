<?PHP

require_once('../conexao/banco.php');

$id 	= $_REQUEST['des_codigo'];

$sql    = "delete from tb_despesas where des_codigo = '$id'";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../form_cadastro_compra.php");

?>


