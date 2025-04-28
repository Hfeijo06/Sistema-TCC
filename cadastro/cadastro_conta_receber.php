<?php
session_start();
require_once('../conexao/banco.php');

// Obtendo dados do formulário
$parcela = $_REQUEST['txt_parcela'];
$credito = $_REQUEST['txt_credito'];
$valor = $_REQUEST['txt_valor'];
$vencimento = $_REQUEST['txt_data'];
$status = $_REQUEST['txt_status'];

// Verificando o status e definindo a data de pagamento
$data_pagamento = ($status === 'Pago') ? date('Y-m-d') : NULL;

// Query SQL com a data de pagamento
$sql = "INSERT INTO tb_contas_receber 
        (parc_codigo, cre_codigo, cr_valor, cr_data_vencimento, cr_status, cr_data_pagamento) 
        VALUES ('$parcela', '$credito', '$valor', '$vencimento', '$status', ?)";

// Preparando e executando a consulta para evitar SQL Injection
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 's', $data_pagamento);
mysqli_stmt_execute($stmt) or die("Erro na SQL!");

$_SESSION['success_message'] = "Cadastro realizado com sucesso!";
header("Location: ../consulta_receber.php");
exit();
?>




