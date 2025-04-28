<?php
require_once('conexao/banco.php');

$pro_codigo = $_GET['pro_codigo'];

// Consulta os fornecedores relacionados ao produto
$sql = "
    SELECT f.for_codigo, f.for_nome, f.for_cnpj 
    FROM tb_fornecedores f
    INNER JOIN tb_produto_fornecedor pf ON f.for_codigo = pf.for_codigo
    WHERE pf.pro_codigo = '$pro_codigo'";

$result = mysqli_query($con, $sql);

$fornecedores = array();
while ($row = mysqli_fetch_assoc($result)) {
    $fornecedores[] = $row;
}

// Retorna os dados em formato JSON
echo json_encode($fornecedores);
?>
