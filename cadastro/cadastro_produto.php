<?php
session_start();
require_once('../conexao/banco.php');

// Captura os dados do formulário
$fornecedores = $_REQUEST['txt_fornecedores']; // Agora um array de fornecedores
$nome = $_REQUEST['txt_nome'];
$tipo = $_REQUEST['txt_tipo'];
$preco = $_REQUEST['txt_preco'];
$descricao = $_REQUEST['txt_descricao'];
$validade = $_REQUEST['txt_validade'];

// Insere o produto na tabela tb_produtos
$sql_produto = "INSERT INTO tb_produtos (pro_nome, pro_tipo, pro_preco, pro_descricao, pro_validade) 
                VALUES ('$nome', '$tipo', '$preco', '$descricao', '$validade')";

if (mysqli_query($con, $sql_produto)) {
    // Recupera o código do produto recém-cadastrado
    $pro_codigo = mysqli_insert_id($con); // Captura o ID do produto inserido

    // Insere as associações do produto com os fornecedores na tabela intermediária
    foreach ($fornecedores as $for_codigo) {
        $sql_produto_fornecedor = "INSERT INTO tb_produto_fornecedor (pro_codigo, for_codigo) VALUES ('$pro_codigo', '$for_codigo')";
        mysqli_query($con, $sql_produto_fornecedor) or die("Erro ao associar fornecedor ao produto!");
    }

    // Mensagem de sucesso
    $_SESSION['success_message'] = "Cadastro realizado com sucesso!";
    header("Location: ../consulta_produto.php"); // Redireciona para a página de destino
    exit(); // Para a execução do script após o redirecionamento
} else {
    die("Erro na inserção do produto!");
}
?>
