<?PHP
session_start();
require_once('../conexao/banco.php');

$nome 	= $_REQUEST['txt_nome'];
$login 	= $_REQUEST['txt_login'];
$senha 	= $_REQUEST['txt_senha'];
$email 	= $_REQUEST['txt_email'];

$sql = "insert into tb_login (log_nome, log_usuario, log_senha, log_email) 
								values ('$nome', '$login', '$senha', '$email')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

// Após o processamento do cadastro
$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../consulta_login.php"); // Redireciona para a página de destino
exit(); // Certifique-se de parar a execução do script após o redirecionamento

?>



