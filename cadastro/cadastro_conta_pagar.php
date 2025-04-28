<?php
session_start();
require_once('../conexao/banco.php');

// Obtendo dados do formulário
$compra = $_REQUEST['txt_compra'];
$despesa = $_REQUEST['txt_despesa'];
$valor = $_REQUEST['txt_valor'];
$vencimento = $_REQUEST['txt_vencimento'];
$status = $_REQUEST['txt_status'];

// Verificando o status e definindo a data de pagamento
$data_pagamento = ($status === 'Pago') ? date('Y-m-d') : NULL;

// Query SQL para inserir a conta a pagar
$sql = "INSERT INTO tb_contas_pagar 
        (com_codigo, des_codigo, cp_valor, cp_vencimento, cp_status, cp_data_pagamento) 
        VALUES (?, ?, ?, ?, ?, ?)";

// Preparando e executando a consulta
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'iissss', $compra, $despesa, $valor, $vencimento, $status, $data_pagamento);
mysqli_stmt_execute($stmt) or die("Erro na SQL!");

// Mensagem de sucesso e redirecionamento
$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../consulta_pagar.php");
exit();
?>
