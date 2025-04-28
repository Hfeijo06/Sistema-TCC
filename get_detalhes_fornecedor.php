<?php
require_once('conexao/banco.php');

$for_codigo = intval($_GET['for_codigo']);
$sql = "SELECT * FROM tb_fornecedores WHERE for_codigo = $for_codigo";
$result = mysqli_query($con, $sql);

$detalhes = mysqli_fetch_assoc($result);

// Retorna os dados em formato JSON
echo json_encode($detalhes);
?>
