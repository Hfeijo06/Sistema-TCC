<?php
require_once('conexao/banco.php');

$id = $_POST['id'];
$status = $_POST['status'];

$query = "UPDATE tb_vendas SET ven_status_entrega = '$status' WHERE ven_codigo = $id";
$resultado = mysqli_query($con, $query);

if ($resultado) {
    echo 'success';
} else {
    echo 'error';
}
?>