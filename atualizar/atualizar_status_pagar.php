<?php
    require_once("../conexao/banco.php");

    if (isset($_GET['cp_codigo'])) {

        $cp_codigo = $_GET['cp_codigo'];
    
        $sql = "UPDATE tb_contas_pagar 
                SET cp_status = 'Pago', cp_data_pagamento = NOW() 
                WHERE cp_codigo = '$cp_codigo'";

        mysqli_query($con, $sql) or die("Erro ao atualizar o status!");
    
        header('Location: ../consulta_pagar.php');
        exit();
    }

?>
