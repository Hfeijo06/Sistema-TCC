<?PHP

require_once('../conexao/banco.php');

$nome 	= $_REQUEST['txt_nome'];
$rg 	= $_REQUEST['txt_rg'];
$cpf 	= $_REQUEST['txt_cpf'];
$cep 	= $_REQUEST['txt_cep'];
$endereco 	= $_REQUEST['txt_endereco'];
$celular 	= $_REQUEST['txt_celular'];
$telefone 	= $_REQUEST['txt_telefone'];


$sql = "insert into tb_clientes (cli_nome, cli_rg, cli_cpf, cli_cep, cli_endereco, cli_celular, cli_telefone) 
								values ('$nome', '$rg', '$cpf', '$cep', '$endereco', '$celular', '$telefone')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_cliente.php");

?>

