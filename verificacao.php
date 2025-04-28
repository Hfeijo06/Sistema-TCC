<?php
require_once('conexao/banco.php');

// Função para verificar se há produtos cadastrados
function verificarProdutos() {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM tb_produtos";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] > 0;
}

// Função para verificar se há clientes cadastrados
function verificarClientes() {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM tb_clientes";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] > 0;
}

// Função para verificar se há fornecedores cadastrados
function verificarFornecedores() {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM tb_fornecedores";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] > 0;
}

// Verifica qual página o usuário está tentando acessar
$pagina = $_GET['pagina'] ?? '';

switch ($pagina) {
    case 'vendas':
        if (!verificarProdutos() || !verificarClientes()) {
            header("Location: principal.php?erro=sem_produto_ou_cliente");
            exit();
        }
        break;
    case 'estoque':
        if (!verificarProdutos()) {
            header("Location: principal.php?erro=sem_produto");
            exit();
        }
        break;
    case 'compras':
        if (!verificarProdutos() || !verificarFornecedores()) {
            header("Location: principal.php?erro=sem_produto_ou_fornecedor");
            exit();
        }
        break;
    default:
        // Para páginas que não precisam de verificação específica, continue normalmente
        break;
}
?>
