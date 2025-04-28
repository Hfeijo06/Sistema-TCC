<?php
require_once('conexao/banco.php');

$sql = "SELECT * 
        FROM tb_estoque AS e
        INNER JOIN tb_produtos AS p ON (p.pro_codigo = e.pro_codigo)
        WHERE e.est_qtde < 25";
$result = mysqli_query($con, $sql);

$produtos = array();
while ($row = mysqli_fetch_assoc($result)) {
    $produtos[] = $row;
}
    
// Retorna os dados em formato JSON
echo json_encode($produtos);
?>