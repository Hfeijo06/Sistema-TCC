﻿<?PHP
session_start();
require_once('../conexao/banco.php');

$compra 	= $_REQUEST['txt_compra'];
$produto 	= $_REQUEST['txt_produto'];
$qtde 	= $_REQUEST['txt_qtde'];
$preco 	= $_REQUEST['txt_preco'];
$total 	= $_REQUEST['txt_total'];

$sql = "insert into tb_itens_compra (com_codigo, pro_codigo, itc_qtde, itc_preco, itc_total) 
								values ('$compra', '$produto', '$qtde', '$preco', '$total')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

// Após o processamento do cadastro
$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../form_cadastro_compra.php?compra=$compra"); // Redireciona para a página de destino
exit(); // Certifique-se de parar a execução do script após o redirecionamento

?>



