<?php
require_once('../conexao/banco.php');
require_once '../vendor/autoload.php'; // Inclua a biblioteca Dompdf

use Dompdf\Dompdf;

$tipoRelatorio = $_GET['relatorio']; // Pode receber o tipo de relatório via GET (ex: ?relatorio=usuarios)



// Instancia o Dompdf
$dompdf = new Dompdf();
// Estilos CSS para o PDF
$html = '<style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
            }
            h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #4CAF50;
                color: white;
                text-align: center;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>';

// Switch para lidar com diferentes relatórios
switch ($tipoRelatorio) {
    case 'usuarios':
        $html .= '<h2>Relatório de Usuários</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Usuário</th>
                            <th>Senha</th>
                            <th>Data Cadastro</th>
                        </tr>
                    </thead>    
                    <tbody>';

        $sql = "SELECT * FROM tb_login";
        $result = mysqli_query($con, $sql);
        while ($data = mysqli_fetch_array($result)) {
            $html .= '<tr>
                        <td>' . $data['log_codigo'] . '</td>
                        <td>' . $data['log_nome'] . '</td>
                        <td>' . $data['log_email'] . '</td>
                        <td>' . $data['log_usuario'] . '</td>
                        <td>' . $data['log_senha'] . '</td>
                        <td>' . $data['log_data_cadastro'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'clientes':        
        $html .= '<h2>Relatório de Clientes</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>RG</th>
                            <th>CPF</th>
                            <th>Celular</th>
                            <th>CEP</th>
                            <th>Numero</th>
                            <th>Complemento</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['cli_codigo'] . '</td>
                        <td>' . $data['cli_nome'] . '</td>
                        <td>' . $data['cli_rg'] . '</td>
                        <td>' . $data['cli_cpf'] . '</td>
                        <td>' . $data['cli_celular'] . '</td>
                        <td>' . $data['cli_cep'] . '</td>
                        <td>' . $data['cli_numero'] . '</td>
                        <td>' . $data['cli_complemento'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'fornecedores':
        $html .= '<h2>Relatório de Fornecedores</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Celular</th>
                            <th>Email</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['for_codigo'] . '</td>
                        <td>' . $data['for_nome'] . '</td>
                        <td>' . $data['for_cnpj'] . '</td>
                        <td>' . $data['for_celular'] . '</td>
                        <td>' . $data['for_email'] . '</td>
                        <td>' . $data['for_descricao'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'produtos':
        $html .= '<h2>Relatório de Produtos</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                            <th>Descrição</th>
                            <th>Validade</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['pro_codigo'] . '</td>
                        <td>' . $data['pro_nome'] . '</td>
                        <td>' . $data['pro_tipo'] . '</td>
                        <td>' . $data['pro_preco'] . '</td>
                        <td>' . $data['pro_descricao'] . '</td>
                        <td>' . $data['pro_validade'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'estoque':
        $html .= '<h2>Relatório de Estoque</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Produto</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['est_codigo'] . '</td>
                        <td>' . $data['pro_nome'] . '</td>
                        <td>' . $data['est_qtde'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'vendas':
        $html .= '<h2>Relatório de Vendas</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo da Venda</th>
                            <th>Cliente</th>
                            <th>Data Lançamento</th>
                            <th>Data Entrega</th>
                            <th>Status da Entrega</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['ven_codigo'] . '</td>
                        <td>' . $data['ven_tipo_venda'] . '</td>
                        <td>' . $data['cli_nome'] . '</td>
                        <td>' . $data['ven_data_lancamento'] . '</td>
                        <td>' . $data['ven_data_entrega'] . '</td>
                        <td>' . $data['ven_status_entrega'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'compras':
        $html .= '<h2>Relatório de Compras</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Débito</th>
                            <th>Nome do Fornecedor</th>
                            <th>Data da Compra</th>
                            <th>Método de Pagamento</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['com_codigo'] . '</td>
                        <td>' . $data['des_nome'] . '</td>
                        <td>' . $data['for_nome'] . '</td>
                        <td>' . $data['com_data'] . '</td>
                        <td>' . $data['com_tipo_pagamento'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'contasrec':
        $html .= '<h2>Relatório de Contas a Receber</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Crédito</th>
                            <th>Cliente</th>
                            <th>CPF</th>
                            <th>Valor</th>
                            <th>Data de Vencimento</th>
                            <th>Data de Pagamento</th>                            
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['cr_codigo'] . '</td>
                        <td>' . $data['cre_nome'] . '</td>
                        <td>' . $data['cli_nome'] . '</td>
                        <td>' . $data['cli_cpf'] . '</td>
                        <td>' . $data['cr_valor'] . '</td>
                        <td>' . $data['cr_data_vencimento'] . '</td>
                        <td>' . $data['cr_data_pagamento'] . '</td>
                        <td>' . $data['cr_status'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    case 'contaspag':
        $html .= '<h2>Relatório de Contas a Pagar</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Débito</th>
                            <th>Nome Fornecedor</th>
                            <th>Descrição Fornecedor</th>
                            <th>Valor</th>
                            <th>Data de Vencimento</th>
                            <th>Data de Pagamento</th>
                            <th>Status</th>
                        </tr>
                    </thead>    
                    <tbody>';

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

                    $result = mysqli_query($con, $sql) or die("Erro na sql!");

                    while ($data = mysqli_fetch_array($result)) {
                        $html .= '<tr>
                                    <td>' . $data['cp_codigo'] . '</td>
                                    <td>' . $data['des_nome'] . '</td>
                                    <td>' . $data['for_nome'] . '</td>
                                    <td>' . $data['for_descricao'] . '</td>
                                    <td>' . $data['cp_valor'] . '</td>
                                    <td>' . $data['cp_vencimento'] . '</td>
                                    <td>' . $data['cp_data_pagamento'] . '</td>
                                    <td>' . $data['cp_status'] . '</td>
                                </tr>';
                    }
                    $html .= '</tbody></table>';

        break;

    case 'fluxo':
        $html .= '<h2>Relatório de Fluxo de Caixa</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>';

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
            $html .= '<tr>
                        <td>' . $data['fc_codigo'] . '</td>
                        <td>' . $data['fc_data'] . '</td>
                        <td>' . $data['fc_valor'] . '</td>
                        <td>' . $data['fc_tipo'] . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        break;

    // Adicione mais casos conforme necessário, como 'compras', 'contasrec', 'contaspag', 'fluxo' etc.

    default:
        $html .= '<h2>Relatório Padrão</h2>';
        $html .= '<p>Selecione um relatório válido.</p>';
}


// Define o conteúdo do PDF
$dompdf->loadHtml($html);

// Define o tamanho do papel e orientação
$dompdf->setPaper('A4', 'portrait');

// Renderiza o HTML como PDF
$dompdf->render();

// Envia o arquivo PDF para o browser
$dompdf->stream($tipoRelatorio . '.pdf', array('Attachment' => 1));
exit;
?>
