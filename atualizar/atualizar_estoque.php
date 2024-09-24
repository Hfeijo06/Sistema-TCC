<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$produto 	= $_REQUEST['txt_produto'];
$qtde 	= $_REQUEST['txt_qtde'];


$sql = "update tb_estoque set 
					pro_codigo = '$produto', 
					est_qtde = '$qtde' 
				where 
					est_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_estoque.php");

?>
