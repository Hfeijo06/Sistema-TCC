﻿<?PHP
session_start();
require_once("../conexao/banco.php");
	
$venda 	= $_REQUEST['txt_venda'];

$produto 	= $_REQUEST['txt_produto'];
$qtde 	= $_REQUEST['txt_qtde'];
$preco 	= $_REQUEST['txt_preco'];
$total 	= $_REQUEST['txt_total'];

	

$sql = "insert into tb_itens_venda (ven_codigo, pro_codigo, itv_qtde, itv_preco, itv_total) 
								values ('$venda', '$produto', '$qtde', '$preco', '$total')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

// Após o processamento do cadastro
$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../form_cadastro_venda.php?venda=$venda"); // Redireciona para a página de destino
exit(); // Certifique-se de parar a execução do script após o redirecionamento


?>

