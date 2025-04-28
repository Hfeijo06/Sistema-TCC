<?PHP

require_once('../conexao/banco.php');

$id = $_REQUEST['itc_codigo'];
$compra = $_REQUEST['com_codigo'];

$sql = "delete from tb_itens_compra where itc_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../form_cadastro_compra.php?compra=$compra");

?>


