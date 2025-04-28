<?php
require 'vendor/autoload.php'; // Autoload gerado pelo Composer
use Dompdf\Dompdf;

require_once('conexao/banco.php'); // Inclui a conexão ao banco

// Obtém o código da venda via GET ou POST
$codigo_da_venda = $_REQUEST['venda']; // Exemplo para pegar via URL, ajuste conforme necessário

// Consulta as informações da venda no banco de dados
$sql = "SELECT v.ven_data_lancamento, c.cli_nome AS nome_cliente, p.pro_descricao, iv.itv_qtde, iv.itv_preco, c.*
        FROM tb_vendas v
        JOIN tb_clientes c ON v.cli_codigo = c.cli_codigo
        JOIN tb_itens_venda iv ON v.ven_codigo = iv.ven_codigo
        JOIN tb_produtos p ON iv.pro_codigo = p.pro_codigo
        WHERE v.ven_codigo = $codigo_da_venda";

$result = $con->query($sql);


// Verifica se há resultados
if ($result->num_rows > 0) {
    // Inicializa as variáveis para a montagem do HTML
    $data_venda = "";
    $nome_cliente = "";
    $itens_html = "";
    $total = 0;

    // Itera pelos resultados e monta o HTML
    while($row = $result->fetch_assoc()) {
        if (!$data_venda) {
            $data_venda = date('d/m/Y', strtotime($row['ven_data_lancamento']));  // Data da venda (primeira linha)
            $nome_cliente = $row['nome_cliente'];  // Nome do cliente (primeira linha)
            $cpf = $row['cli_cpf'];  // CPF ou Código do cliente
            $endereco = $row['cli_rua'] . ', ' . $row['cli_numero'] . ', ' . $row['cli_bairro'] . ', ' . $row['cli_cidade'];
            $celular = $row['cli_celular'];  // Número de celular do cliente
        }

        $valor = $row['itv_qtde'] * $row['itv_preco'];
        // Adiciona os itens da venda ao HTML
        $itens_html .= "<tr>
                            <td>{$row['itv_qtde']}</td>
                            <td>{$row['pro_descricao']}</td>
                            <td>R$ " . number_format($row['itv_preco'], 2, ',', '.') . "</td>
                            <td>R$ " . number_format($valor, 2, ',', '.') . "</td>
                        </tr>";
        // Calcula o total
        $total += $row['itv_qtde'] * $row['itv_preco'];
    }

    // Monta o HTML do comprovante
    $html = "
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            background-color: #006f4c;
            color: white;
            padding: 15px 0;
            margin-bottom: 20px;
            border-radius: 20px;
        }

        h1, h2, h3 {
            margin: 0;
            padding: 5px 0;
        }

        h2 {
            color: #006f4c;
        }

        h3 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        p {
            margin: 0;
            padding: 5px 0;
        }

        .client, .employee, .company {
            padding: 10px;
            margin-bottom: 10px;
        }

        .client {
            float: left;
            width: 45%;
            margin-right: 10px;
        }

        .date {
            float: right;
            width: 45%;
            text-align: right;
        }

        .value {
            clear: both;
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
            background-color: #006f4c;
            color: white;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            clear: both;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        thead {
            background-color: #006f4c;
            color: white;
        }

        tfoot td {
            font-weight: bold;
        }

        .employee, .company {
            float: left;
            width: 45%;
            margin-right: 10px;
        }

        .clearfix::after {
            content: '';
            clear: both;
            display: table;
        }

        footer {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #04622B;
            color: #04622B;
        }

        @media print {
            body {
                width: 100%;
            }

            .client, .date, .employee, .company {
                float: none;
                width: 100%;
            }
        }
        .sale {
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px; /* Bordas arredondadas */
            padding: 10px 20px; /* Espaçamento interno */
            width: 90px;
            margin-top: 10px; /* Espaçamento entre título e data */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombras leves */
            font-weight: bold;
            margin-left: 155px;
        }

    </style>
    <div class='title'>
        <h1>Comprovante de Venda</h1>
    </div>
    <div class='clearfix'>
        <div class='client'>
            <h2> Dados do Cliente </h2>
            <h3> Nome: $nome_cliente </h3>
            <p> CPF: $cpf </p>
            <p> Endereço: $endereco </p>
            <p> Celular: $celular </p>
        </div>
        <div class='date'>
            <h2> Data da Venda: </h2>
            <div class='sale'>$data_venda</div>
        </div>
    </div>
    <div class='clearfix'>
        <div class='value'>
            <p>Valor da Venda: R$ " . number_format($total, 2, ',', '.') . "</p>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Quantidade</th>
                <th>Descrição</th>
                <th>Preço Por Unidade (R$)</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            $itens_html
        </tbody>
        <tfoot>
            <tr>
                <td colspan='3'>Total:</td>
                <td>R$ " . number_format($total, 2, ',', '.') . "</td>
            </tr>
        </tfoot>
    </table>
    
    <div class='clearfix'>
        <div class='employee'>
            <h2> Dados do Funcionário </h2>
            <h3> Nome: Bianca da Silva </h3>
            <p> CPF: 743.985.246-78 </p>
            <p> Celular: (19) 994375-9754 </p>
            <p> Email: edigas@gmail.com </p>
        </div>
        <div class='company'>
            <h2> Dados da Empresa </h2>
            <h3> Nome: Édi Gás e Água </h3>
            <p> CNPJ: 96.234.233/0001-89 </p>
            <p> Endereço: Av. Abdo Najar, 668 - Cidade Jardim I, Americana </p>
            <p> Telefone: (19) 3461-9105 </p>
        </div>
    </div>

    <footer>
        Édi Gás e Água
    </footer>
    ";

    // Instancia o DOMPDF e gera o PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Salva o PDF para verificar se está gerando corretamente
    file_put_contents("temp_comprovante_$codigo_da_venda.pdf", $dompdf->output());

    // Exibe o PDF no navegador
    $dompdf->stream("comprovante_venda_$codigo_da_venda.pdf", array("Attachment" => false));
    
} else {
    echo "Nenhuma venda encontrada com o código fornecido.";
}

// Fecha a conexão com o banco de dados
$con->close();
?>
