<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$nome 	= $_REQUEST['txt_nome'];
$rg 	= $_REQUEST['txt_rg'];
$cpf	= $_REQUEST['txt_cpf'];
$cep 	= $_REQUEST['txt_cep'];
$endereco 	= $_REQUEST['txt_endereco'];
$telefone 	= $_REQUEST['txt_telefone'];
$celular 	= $_REQUEST['txt_celular'];

$sql = "update tb_clientes set 
					cli_nome = '$nome', 
					cli_rg = '$rg', 
					cli_cpf = '$cpf',
					cli_cep = '$cep',
					cli_endereco = '$endereco',
					cli_telefone = '$telefone',
					cli_celular = '$celular'
				where 
					cli_codigo = '$id'";
									
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_cliente.php");

?>
