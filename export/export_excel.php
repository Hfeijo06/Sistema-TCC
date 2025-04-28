<?php
require_once('../conexao/banco.php');
require '../vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$tipoRelatorio = $_GET['relatorio']; // Recebe o tipo de relatório via GET

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Cabeçalhos padrão    
$cabecalhos = [];
$dados = [];
$titulo = "";

// Switch para selecionar a tabela e os cabeçalhos
switch ($tipoRelatorio) {
    case 'usuarios':
        $titulo = "Relatório de Usuários";
        $cabecalhos = ['ID', 'Nome', 'Email', 'Usuário', 'Senha', 'Data de Cadastro'];
        $sql = "SELECT * FROM tb_login";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['log_codigo'], $data['log_nome'], $data['log_email'], $data['log_usuario'], $data['log_senha'], $data['log_data_cadastro']];
        }
        break;
    
    case 'clientes':
        $titulo = "Relatório de Clientes";
        $cabecalhos = ['ID', 'Nome', 'RG', 'CPF', 'Celular', 'CEP', 'Numero', 'Complemento'];

        $cliente = isset($_REQUEST['cli_nome']) ? $_REQUEST['cli_nome'] : '';
        $cpf = isset($_REQUEST['cli_cpf']) ? $_REQUEST['cli_cpf'] : '';
        $cep = isset($_REQUEST['cli_cep']) ? $_REQUEST['cli_cep'] : '';
        $rua = isset($_REQUEST['cli_rua']) ? $_REQUEST['cli_rua'] : '';
        $cidade = isset($_REQUEST['cli_cidade']) ? $_REQUEST['cli_cidade'] : '';
        $celular = isset($_REQUEST['cli_celular']) ? $_REQUEST['cli_celular'] : '';
        $bairro = isset($_REQUEST['cli_bairro']) ? $_REQUEST['cli_bairro'] : '';
        $numero = isset($_REQUEST['cli_numero']) ? $_REQUEST['cli_numero'] : '';
        $sql = "select * from tb_clientes where 
                cli_nome like '%".$cliente."%' AND
                cli_cpf like '%".$cpf."%' AND
                cli_cep like '%".$cep."%' AND   
                cli_rua like '%".$rua."%' AND
                cli_cidade like '%".$cidade."%' AND
                cli_celular like '%".$celular."%' AND
                cli_bairro like '%".$bairro."%' AND
                cli_numero like  '%".$numero."%' AND
                cli_codigo != 1
                ORDER BY cli_codigo DESC";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['cli_codigo'], $data['cli_nome'], $data['cli_rg'], $data['cli_cpf'], $data['cli_celular'], $data['cli_cep'], $data['cli_numero'], $data['cli_complemento']];
        }
        break;

    case 'fornecedores':
        $titulo = "Relatório de Fornecedores";
        $cabecalhos = ['ID', 'Nome', 'CNPJ', 'Celular', 'Email', 'Descrição'];
        $cliente = isset($_REQUEST['for_cliente']) ? $_REQUEST['for_cliente'] : '';
        $telefone = isset($_REQUEST['for_celular']) ? $_REQUEST['for_celular'] : '';
        $email = isset($_REQUEST['for_email']) ? $_REQUEST['for_email'] : '';
        $descricao = isset($_REQUEST['for_descricao']) ? $_REQUEST['for_descricao'] : '';
        $cnpj = isset($_REQUEST['for_cnpj']) ? $_REQUEST['for_cnpj'] : '';
        $sql = "select * from tb_fornecedores where for_nome like '%".$cliente."%' AND
                for_celular like '%".$telefone."%' AND
                for_email like '%".$email."%' AND
                for_descricao like '%".$descricao."%' AND
                for_cnpj like '%".$cnpj."%' AND
                for_codigo != 7
                ORDER BY for_codigo DESC";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['for_codigo'], $data['for_nome'], $data['for_cnpj'], $data['for_celular'], $data['for_email'], $data['for_descricao']];
        }
        break;

    case 'produtos':
        $titulo = "Relatório de Produtos";
        $cabecalhos = ['ID', 'Produto', 'Tipo', 'Preço', 'Descrição', 'Validade'];
        $cliente = isset($_REQUEST['pro_nome']) ? $_REQUEST['pro_nome'] : '';
                    $tipo = isset($_REQUEST['pro_tipo']) ? $_REQUEST['pro_tipo'] : '';
                    $preco = isset($_REQUEST['pro_preco']) ? $_REQUEST['pro_preco'] : '';
                    $descricao = isset($_REQUEST['pro_descricao']) ? $_REQUEST['pro_descricao'] : '';
                    $validade = isset($_REQUEST['pro_validade']) ? $_REQUEST['pro_validade'] : '';
                    $fornecedor = isset($_REQUEST['pro_fornecedor']) ? $_REQUEST['pro_fornecedor'] : '';
                    
                    // Consulta para obter todos os produtos e seus fornecedores
                    $sql = "
                        SELECT *, p.pro_codigo, p.pro_nome, p.pro_descricao, p.pro_tipo, p.pro_preco, p.pro_validade, f.for_nome
                        FROM tb_produtos AS p
                        LEFT JOIN tb_produto_fornecedor AS fp ON p.pro_codigo = fp.pro_codigo
                        LEFT JOIN tb_fornecedores AS f ON fp.for_codigo = f.for_codigo 
                        WHERE 
                        pro_nome like '%".$cliente."%' AND
                        pro_tipo like '%".$tipo."%' AND
                        pro_preco like '%".$preco."%' AND
                        pro_descricao like '%".$descricao."%' AND
                        pro_validade like '%".$validade."%' AND
                        for_nome like '%".$fornecedor."%'
                        ";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['pro_codigo'], $data['pro_nome'], $data['pro_tipo'], $data['pro_preco'], $data['pro_descricao'], $data['pro_validade'],];
        }
        break;

    case 'estoque':
        $titulo = "Relatório de Estoque";
        $cabecalhos = ['ID', 'Produto', 'Quantidade'];
        $produto = isset($_REQUEST['pro_codigo']) ? $_REQUEST['pro_codigo'] : '';
        $qtde = isset($_REQUEST['est_qtde']) ? $_REQUEST['est_qtde'] : '';
        
        $sql = "select * 
                    from tb_estoque as e
                    inner join tb_produtos as p on (e.pro_codigo = p.pro_codigo)
                    WHERE 
                    pro_descricao like '%".$produto."%' AND
                    est_qtde like '%".$qtde."%'            
                    ORDER BY est_codigo DESC";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['est_codigo'], $data['pro_nome'], $data['est_qtde']];
        }
        break;

    case 'vendas':
        $titulo = "Relatório de Vendas";
        $cabecalhos = ['ID', 'Tipo da Venda', 'Cliente', 'Data Lançamento', 'Data Entrega', 'Status'];
        $cliente = isset($_REQUEST['cli_codigo']) ? $_REQUEST['cli_codigo'] : '';
                    $tipo = isset($_REQUEST['ven_tipo_venda']) ? $_REQUEST['ven_tipo_venda'] : '';
                    $status = isset($_REQUEST['ven_status_entrega']) ? $_REQUEST['ven_status_entrega'] : '';
                    $lancamento = isset($_REQUEST['ven_data_lancamento']) ? $_REQUEST['ven_data_lancamento'] : '';
                    $entrega = isset($_REQUEST['ven_data_entrega']) ? $_REQUEST['ven_data_entrega'] : '';
                    
                    // Consulta de vendas
                    $sql = "select *
                            from tb_vendas AS v
                            INNER JOIN tb_clientes AS c ON (c.cli_codigo = v.cli_codigo)
                            WHERE 
                            cli_nome like '%".$cliente."%' AND
                            ven_tipo_venda like '%".$tipo."%' AND
                            ven_status_entrega like '%".$status."%' AND
                            ven_data_lancamento like '%".$lancamento."%' AND
                            ven_data_entrega like '%".$entrega."%' AND
                            ven_codigo != 7
                            ORDER BY ven_codigo DESC";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['ven_codigo'], $data['ven_tipo_venda'], $data['cli_nome'], $data['ven_data_lancamento'], $data['ven_data_entrega'], $data['ven_status_entrega']];
        }
        break;

    case 'compras':
        $titulo = "Relatório de Compras";
        $cabecalhos = ['ID', 'Tipo da Despesa', 'Fornecedor', 'Data', 'Metódo de Pagamento'];
        $nome = isset($_REQUEST['des_nome']) ? $_REQUEST['des_nome'] : '';
        $fornecedor = isset($_REQUEST['for_fornecedor']) ? $_REQUEST['for_fornecedor'] : '';
        $tipo_pagamento = isset($_REQUEST['tipo_pag']) ? $_REQUEST['tipo_pag'] : '';
        $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
        
        $sql = "SELECT *, DATE_FORMAT(c.com_data, '%d/%m/%Y') AS data, f.for_nome, d.des_nome
                FROM tb_compra AS c
                LEFT JOIN tb_despesas AS d ON d.des_codigo = c.des_codigo
                INNER JOIN tb_fornecedores AS f ON f.for_codigo = c.for_codigo
                WHERE 
                c.com_codigo != 15 AND
                com_tipo_pagamento like '%".$tipo_pagamento."%' AND
                for_nome like '%".$fornecedor."%' AND
                des_nome like '%".$nome."%' AND
                com_data like '%".$data."%' 
                ORDER BY com_codigo DESC";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
             $dados[] = [$data['com_codigo'], $data['des_nome'], $data['for_nome'], $data['com_data'], $data['com_tipo_pagamento']];
        }
        break;

    case 'contasrec':
        $titulo = "Relatório de Contas a Receber";
        $cabecalhos = ['ID', 'Tipo do Crédito','Nome Cliente', 'CPF', 'Valor', 'Data Vencimento', 'Data Pagamento', 'Status'];
        $credito = isset($_REQUEST['credito']) ? $_REQUEST['credito'] : '';
        $nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
        $cpf = isset($_REQUEST['cpf']) ? $_REQUEST['cpf'] : '';
        $valor = isset($_REQUEST['valor']) ? $_REQUEST['valor'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        $vencimento = isset($_REQUEST['vencimento']) ? $_REQUEST['vencimento'] : '';
        $pagamento = isset($_REQUEST['pagamento']) ? $_REQUEST['pagamento'] : '';
        
        $sql = "SELECT * 
                FROM 
                    tb_contas_receber AS cr
                INNER JOIN
                    tb_parcelas AS p ON cr.parc_codigo = p.parc_codigo
                LEFT JOIN 
                    tb_credito AS d ON cr.cre_codigo = d.cre_codigo
                INNER JOIN
                    tb_pagamento AS g ON p.pag_codigo = g.pag_codigo
                INNER JOIN 
                    tb_vendas AS v ON g.ven_codigo = v.ven_codigo
                INNER JOIN 
                    tb_clientes AS c ON c.cli_codigo = v.cli_codigo
                WHERE 
                    cre_nome LIKE '%" . $credito . "%' AND
                    cli_nome LIKE '%" . $nome . "%' AND
                    cli_cpf LIKE '%" . $cpf . "%' AND
                    cr_valor LIKE '%" . $valor . "%' AND
                    (cr_data_pagamento LIKE '%".$pagamento."%' OR '".$pagamento."' = '') AND
                    (cr_data_vencimento LIKE '%".$vencimento."%' OR '".$vencimento."' = '') AND
                    cr_status LIKE '%" . $status . "%' 
                ORDER BY cr_codigo DESC";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['cr_codigo'],$data['cre_nome'], $data['cli_nome'], $data['cli_cpf'], $data['cr_valor'], $data['parc_data_vencimento'], $data['cr_data_pagamento'], $data['cr_status']];
        }
        break;

    case 'contaspag':
        $titulo = "Relatório de Contas a Pagar";
        $cabecalhos = ['ID', 'Tipo do Débito', 'Nome do Fornecedor','Descrição', 'Valor', 'Data Vencimento', 'Data Pagamento', 'Status'];
        $despesa = isset($_REQUEST['despesa']) ? $_REQUEST['despesa'] : '';
                    $valor = isset($_REQUEST['valor']) ? $_REQUEST['valor'] : '';
                    $nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
                    $descricao = isset($_REQUEST['descricao']) ? $_REQUEST['descricao'] : '';
                    $vencimento = isset($_REQUEST['vencimento']) ? $_REQUEST['vencimento'] : '';
                    $pagamento = isset($_REQUEST['pagamento']) ? $_REQUEST['pagamento'] : '';
                    $status    = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
                    
                    $sql = "SELECT * 
                            FROM tb_contas_pagar AS p
                            LEFT JOIN tb_despesas AS d ON p.des_codigo = d.des_codigo
                            LEFT JOIN tb_compra AS c ON p.com_codigo = c.com_codigo
                            LEFT JOIN tb_fornecedores AS f ON c.for_codigo = f.for_codigo
                            WHERE des_nome like '%".$despesa."%' AND
                                cp_valor like '%".$valor."%' AND
                                for_nome like '%".$nome."%' AND
                                for_descricao like '%".$descricao."%' AND 
                                (cp_vencimento LIKE '%".$vencimento."%' OR '".$vencimento."' = '') AND
                                (cp_data_pagamento LIKE '%".$pagamento."%' OR '".$pagamento."' = '') AND
                                cp_status like '%".$status."%' 
                                ORDER BY cp_codigo DESC";  
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $dados[] = [$data['cp_codigo'], $data['des_nome'], $data['for_nome'], $data['for_descricao'], $data['cp_valor'], $data['cp_vencimento'], $data['cp_data_pagamento'], $data['cp_status']];
        }
        break;

case 'fluxo':
    $titulo = "Relatório do Fluxo de Caixa";
    $cabecalhos = ['ID', 'Tipo da Despesa', 'Valor', 'Data Vencimento', 'Data Pagamento'];
    $data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
    $data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
    
    $sql = "SELECT f.*, c.cp_data_pagamento, r.cr_data_pagamento 
            FROM tb_fluxo_caixa AS f
            LEFT JOIN tb_contas_pagar AS c ON f.cp_codigo = c.cp_codigo
            LEFT JOIN tb_contas_receber AS r ON f.cr_codigo = r.cr_codigo
            ";
    
    if (!empty($data_inicio) && !empty($data_fim)) {
        $sql .= " WHERE fc_data BETWEEN '$data_inicio' AND '$data_fim'";
    }
    $sql .= " ORDER BY f.fc_codigo DESC";
    $result = mysqli_query($con, $sql);
    while ($data = mysqli_fetch_array($result)) {
        // Definindo a data de pagamento condicionalmente
        if ($data['fc_tipo'] == 'Entrada' && $data['cr_data_pagamento']) {
            $dataPagamento = date('d/m/Y', strtotime($data['cr_data_pagamento']));
        } elseif ($data['fc_tipo'] == 'Saída' && $data['cp_data_pagamento']) {
            $dataPagamento = date('d/m/Y', strtotime($data['cp_data_pagamento']));
        } else {
            $dataPagamento = '—'; // Coloca um traço se não houver data de pagamento
        }

        // Preenchendo os dados na planilha
        $dados[] = [
            $data['fc_codigo'], 
            $data['fc_tipo'], 
            $data['fc_valor'], 
            date('d/m/Y', strtotime($data['fc_data'])), // Data de vencimento formatada
            $dataPagamento // Data de pagamento condicional
        ];
    }
    break;

    

    default:
        die('Relatório inválido.');
}

// Preencher os cabeçalhos na planilha
$coluna = 'A';
foreach ($cabecalhos as $cabecalho) {
    $sheet->setCellValue($coluna . '1', $cabecalho);
    $coluna++;
}

// Aplicar estilo aos cabeçalhos
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4CAF50']
    ],
];

$sheet->getStyle('A1:' . chr(ord('A') + count($cabecalhos) - 1) . '1')->applyFromArray($headerStyle);

// Ajustar automaticamente a largura das colunas
foreach (range('A', chr(ord('A') + count($cabecalhos) - 1)) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Preencher os dados
$row = 2;
foreach ($dados as $linhaDados) {
    $coluna = 'A';
    foreach ($linhaDados as $dado) {
        $sheet->setCellValue($coluna . $row, $dado);
        $coluna++;
    }
    $row++;
}

// Aplicar bordas para as células preenchidas
$sheet->getStyle('A1:' . chr(ord('A') + count($cabecalhos) - 1) . ($row - 1))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
        ],
    ],
]);

// Centralizar os textos das células
$sheet->getStyle('A2:A' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B2:' . chr(ord('A') + count($cabecalhos) - 1) . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Exportar para Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"{$titulo}.xlsx\"");
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
