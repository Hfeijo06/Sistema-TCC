<?php
require_once("../conexao/banco.php");

$id = $_REQUEST['txt_codigo'];

// Captura os dados do formulário
$fornecedoresSelecionados = $_REQUEST['txt_fornecedor'] ?? []; // Captura fornecedores selecionados
$nome = $_REQUEST['txt_nome'];
$tipo = $_REQUEST['txt_tipo'];
$preco = $_REQUEST['txt_preco'];
$descricao = $_REQUEST['txt_descricao'];
$validade = $_REQUEST['txt_validade'];

// Atualiza o produto
$sql = "UPDATE tb_produtos SET
            pro_nome = '$nome', 
            pro_descricao = '$descricao',
            pro_tipo = '$tipo',
            pro_preco = '$preco',
            pro_validade = '$validade'
        WHERE 
            pro_codigo = '$id'";

mysqli_query($con, $sql) or die("Erro na atualização do produto!");

// Agora, atualiza os fornecedores associados ao produto
// Primeiro, vamos remover todos os fornecedores atuais para o produto
$sqlDelete = "DELETE FROM tb_produto_fornecedor WHERE pro_codigo = '$id'";
mysqli_query($con, $sqlDelete) or die("Erro ao remover fornecedores antigos!");

// Agora, adiciona os novos fornecedores selecionados
foreach ($fornecedoresSelecionados as $fornecedor) {
    $sqlInsert = "INSERT INTO tb_produto_fornecedor (for_codigo, pro_codigo) VALUES ('$fornecedor', '$id')";
    mysqli_query($con, $sqlInsert) or die("Erro ao associar fornecedor ao produto!");
}

// Redireciona após a atualização
header("Location: ../consulta_produto.php");
exit();
?>
