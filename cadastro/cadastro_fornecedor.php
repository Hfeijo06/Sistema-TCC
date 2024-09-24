<?PHP

require_once('../conexao/banco.php');

$nome 	= $_REQUEST['txt_nome'];
$descricao 	= $_REQUEST['txt_descricao'];
$celular 	= $_REQUEST['txt_celular'];
$telefone 	= $_REQUEST['txt_telefone'];
$email 	= $_REQUEST['txt_email'];

$sql = "insert into tb_fornecedores (for_nome, for_descricao, for_celular, for_telefone, for_email) 
								values ('$nome', '$descricao', '$celular', '$telefone', '$email')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_fornecedor.php");

?>


