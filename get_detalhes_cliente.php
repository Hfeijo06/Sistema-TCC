<?php
// Conectar ao banco de dados
require_once('conexao/banco.php');

// Verifica se o ID do cliente foi passado
if (isset($_GET['id'])) {
    $clienteId = intval($_GET['id']); // Converte para inteiro para segurança

    // Executar a consulta SQL para obter os detalhes do cliente
    $sql = "SELECT c.cli_nome, SUM(pr.parc_valor) AS valor_devido, MAX(pr.parc_data_vencimento) AS data_vencimento, c.cli_cpf, c.cli_celular
            FROM tb_clientes AS c
            JOIN tb_vendas AS v ON c.cli_codigo = v.cli_codigo
            JOIN tb_pagamento AS p ON v.ven_codigo = p.ven_codigo
            JOIN tb_parcelas AS pr ON p.pag_codigo = pr.pag_codigo
            JOIN tb_contas_receber AS cr ON pr.parc_codigo = cr.parc_codigo
            WHERE c.cli_codigo = $clienteId AND cr.cr_status = 'Pendente' AND pr.parc_data_vencimento < NOW()
            GROUP BY c.cli_codigo";


    $result = mysqli_query($con, $sql);

    if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
            // Retorna os dados em formato JSON
            header('Content-Type: application/json'); // Define o tipo de conteúdo
            echo json_encode([
                'nome' => $row['cli_nome'],
                'cpf' => $row['cli_cpf'],
                'celular' => $row['cli_celular'],
                'valor_devido' => $row['valor_devido'],
                'data_vencimento' => $row['data_vencimento'],
            ]);
        } else {
            // Se o cliente não for encontrado
            echo json_encode(['error' => 'Cliente não encontrado.']);
        }
    } else {
        // Se a consulta falhar
        echo json_encode(['error' => 'Erro na consulta: ' . mysqli_error($con)]);
    }
} else {
    // Se o ID não for passado
    echo json_encode(['error' => 'ID do cliente não fornecido.']);
}
?>
