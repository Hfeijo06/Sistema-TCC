<?PHP
session_start();
require_once('../conexao/banco.php');

$nome 	= $_REQUEST['txt_nome'];
$descricao 	= $_REQUEST['txt_descricao'];
$celular 	= $_REQUEST['txt_celular'];
$telefone 	= $_REQUEST['txt_telefone'];
$email 	= $_REQUEST['txt_email'];
$cnpj 	= $_REQUEST['txt_cnpj'];

$sql = "insert into tb_fornecedores (for_nome, for_descricao, for_celular, for_telefone, for_email, for_cnpj) 
								values ('$nome', '$descricao', '$celular', '$telefone', '$email', '$cnpj')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

// Após o processamento do cadastro
$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../consulta_fornecedor.php"); // Redireciona para a página de destino
exit(); // Certifique-se de parar a execução do script após o redirecionamento

?>


