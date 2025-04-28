<?php
    require_once("../conexao/banco.php");

    if (isset($_GET['cr_codigo'])) {
        
        $cr_codigo = $_GET['cr_codigo'];
        
        // Atualiza o status para 'Pago' e registra a data atual
        $sql = "UPDATE tb_contas_receber 
                SET cr_status = 'Pago', cr_data_pagamento = NOW() 
                WHERE cr_codigo = '$cr_codigo'";
        
        mysqli_query($con, $sql) or die("Erro ao atualizar status!");
        
        header("Location: ../consulta_receber.php");
        exit();

        
    }

?>
