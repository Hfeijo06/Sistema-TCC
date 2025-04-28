<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];

$nome 	= $_REQUEST['txt_nome'];
$rg 	= $_REQUEST['txt_rg'];
$cpf	= $_REQUEST['txt_cpf'];
$cep 	= $_REQUEST['txt_cep'];
$telefone 	= $_REQUEST['txt_telefone'];
$celular 	= $_REQUEST['txt_celular'];
$cidade 	= $_REQUEST['txt_cidade'];
$bairro 	= $_REQUEST['txt_bairro'];
$numero 	= $_REQUEST['txt_numero'];
$rua 	= $_REQUEST['txt_rua'];
$complemento 	= $_REQUEST['txt_complemento'];

$sql = "update tb_clientes set 
					cli_nome = '$nome', 
					cli_rg = '$rg', 
					cli_cpf = '$cpf',
					cli_cep = '$cep',
					cli_telefone = '$telefone',
					cli_celular = '$celular',
					cli_cidade = '$cidade',
					cli_bairro = '$bairro',
					cli_numero = '$numero',
					cli_rua = '$rua',
					cli_complemento = '$complemento'
				where 
					cli_codigo = '$id'";
									
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_cliente.php");

?>
