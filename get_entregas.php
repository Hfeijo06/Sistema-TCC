<?php
require_once('conexao/banco.php');

$query = "SELECT v.ven_codigo, v.ven_data_entrega, v.ven_status_entrega, 
                 c.cli_nome, c.cli_rua, c.cli_numero, c.cli_bairro, c.cli_cidade,
                 p.pro_descricao, i.itv_qtde
          FROM tb_vendas AS v
          INNER JOIN tb_clientes AS c ON c.cli_codigo = v.cli_codigo
          INNER JOIN tb_itens_venda AS i ON i.ven_codigo = v.ven_codigo
          INNER JOIN tb_produtos AS p ON p.pro_codigo = i.pro_codigo
          WHERE v.ven_data_entrega BETWEEN CONCAT(CURDATE(), ' 06:00:00') 
          AND CONCAT(CURDATE(), ' 23:59:59')
          AND v.ven_status_entrega != 'Entregue'";
$resultado = mysqli_query($con, $query);

if (!$resultado) {
    echo "<li>Erro na consulta: " . mysqli_error($con) . "</li>";
    exit;
}

if (mysqli_num_rows($resultado) == 0) {
    echo "<li>Nenhuma entrega agendada para hoje.</li>";
    exit;
}

$vendas = [];
while ($venda = mysqli_fetch_assoc($resultado)) {
    $ven_codigo = $venda['ven_codigo'];

    // Se ainda não existe uma entrada para essa venda, inicialize
    if (!isset($vendas[$ven_codigo])) {
        $vendas[$ven_codigo] = [
            'ven_data_entrega'   => $venda['ven_data_entrega'],
            'ven_status_entrega' => $venda['ven_status_entrega'],
            'cli_nome'           => $venda['cli_nome'],
            'cli_rua'            => $venda['cli_rua'],
            'cli_numero'         => $venda['cli_numero'],
            'cli_bairro'         => $venda['cli_bairro'],
            'cli_cidade'         => $venda['cli_cidade'],
            'produtos'           => []
        ];
    }

    // Adiciona o produto à lista de produtos da venda
    $vendas[$ven_codigo]['produtos'][] = [
        'pro_descricao'      => $venda['pro_descricao'],
        'itv_qtde' => $venda['itv_qtde']
    ];
}
    
// Gerar o HTML de saída
$output = '';
foreach ($vendas as $ven_codigo => $venda) {
    $output .= "
    <li>
        <div><strong>Cliente:</strong> {$venda['cli_nome']}</div>
        <div><strong>Endereço:</strong> {$venda['cli_rua']}, {$venda['cli_numero']} {$venda['cli_bairro']} {$venda['cli_cidade']}</div>
        <div><strong>Data:</strong> " . date('d/m/Y H:i', strtotime($venda['ven_data_entrega'])) . "</div>
        <div><strong>Status:</strong>
            <select class='status-entrega' data-id='{$ven_codigo}'>
                <option value='Pendente'" . ($venda['ven_status_entrega'] == 'Pendente' ? ' selected' : '') . ">Pendente</option>
                <option value='Em Trânsito'" . ($venda['ven_status_entrega'] == 'Em Trânsito' ? ' selected' : '') . ">Em Trânsito</option>
                <option value='Entregue'" . ($venda['ven_status_entrega'] == 'Entregue' ? ' selected' : '') . ">Entregue</option>
            </select>
        </div>
        <div><strong>Produtos:</strong>
            <ul>";

    // Lista os produtos dessa venda
    foreach ($venda['produtos'] as $produto) {
        $output .= "<li>{$produto['pro_descricao']} - Quantidade: {$produto['itv_qtde']}</li>";
    }

    $output .= "</ul>
        </div>
    </li>";
}

echo $output;

?>