<?php
require_once('conexao/banco.php');

$hoje_inicio = date('Y-m-d 00:00:00');
$hoje_fim = date('Y-m-d 23:59:59');
$query = "SELECT COUNT(*) AS entregas_hoje 
          FROM tb_vendas 
          WHERE (ven_data_entrega BETWEEN '$hoje_inicio' AND '$hoje_fim') 
          AND (ven_status_entrega != 'Entregue')";
$resultado = mysqli_query($con, $query);
$entregas_hoje = mysqli_fetch_assoc($resultado)['entregas_hoje'];

echo $entregas_hoje;
?>