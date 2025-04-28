<?php
// Conectar ao banco de dados
require_once('conexao/banco.php');

// Executar a consulta SQL
$sql = "SELECT c.cli_codigo, c.cli_nome, SUM(pr.parc_valor) AS valor_devido
        FROM tb_clientes AS c
        JOIN tb_vendas AS v ON c.cli_codigo = v.cli_codigo
        JOIN tb_pagamento AS p ON v.ven_codigo = p.ven_codigo
        JOIN tb_parcelas AS pr ON p.pag_codigo = pr.pag_codigo
        JOIN tb_contas_receber AS cr ON pr.parc_codigo = cr.parc_codigo
        WHERE cr.cr_status = 'Pendente' AND pr.parc_data_vencimento < NOW()
        GROUP BY c.cli_codigo
        ORDER BY valor_devido DESC";

$result = mysqli_query($con, $sql);
$clientes_devedores = [];

// Preenche o array com os dados
while ($row = mysqli_fetch_assoc($result)) {
    $clientes_devedores[] = [
        'id' => $row['cli_codigo'], // Código do cliente
        'nome' => $row['cli_nome'],
        'valor_devido' => $row['valor_devido'],
    ];
}

// Retorna os dados em formato JSON
header('Content-Type: application/json'); // Define o tipo de conteúdo
echo json_encode($clientes_devedores);
?>
