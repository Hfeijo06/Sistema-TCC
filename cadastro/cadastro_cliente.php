<?PHP
session_start();	
require_once('../conexao/banco.php');

$nome 	= $_REQUEST['txt_nome'];
$rg 	= $_REQUEST['txt_rg'];
$cpf 	= $_REQUEST['txt_cpf'];
$cep 	= $_REQUEST['txt_cep'];
$celular 	= $_REQUEST['txt_celular'];
$telefone 	= $_REQUEST['txt_telefone'];
$cidade 	= $_REQUEST['txt_cidade'];
$bairro 	= $_REQUEST['txt_bairro'];
$numero 	= $_REQUEST['txt_numero'];
$rua 	= $_REQUEST['txt_rua'];
$complemento 	= $_REQUEST['txt_complemento'];


$sql = "insert into tb_clientes (cli_nome, cli_rg, cli_cpf, cli_cep, cli_celular, cli_telefone, cli_cidade, cli_bairro, cli_numero, cli_rua, cli_complemento) 
								values ('$nome', '$rg', '$cpf', '$cep', '$celular', '$telefone', '$cidade', '$bairro', '$numero', '$rua', '$complemento')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;


// Após o processamento do cadastro
$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../consulta_cliente.php"); // Redireciona para a página de destino
exit(); // Certifique-se de parar a execução do script após o redirecionamento

?>

