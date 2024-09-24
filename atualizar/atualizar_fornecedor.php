<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$nome 	= $_REQUEST['txt_nome'];
$descricao 	= $_REQUEST['txt_descricao'];
$email 	= $_REQUEST['txt_email'];
$telefone 	= $_REQUEST['txt_telefone'];
$celular 	= $_REQUEST['txt_celular'];

$sql = "update tb_fornecedores set 
					for_nome = '$nome', 
					for_descricao = '$descricao', 
					for_email = '$email',
					for_telefone = '$telefone',
					for_celular = '$celular'
				where 
					for_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_fornecedor.php");

?>
