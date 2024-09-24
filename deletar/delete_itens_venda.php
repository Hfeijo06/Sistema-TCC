<?PHP

require_once('../conexao/banco.php');

$id = $_REQUEST['itv_codigo'];
$venda = $_REQUEST['ven_codigo'];

$sql = "delete from tb_itens_venda where itv_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../form_cadastro_venda.php?venda=$venda");

?>


