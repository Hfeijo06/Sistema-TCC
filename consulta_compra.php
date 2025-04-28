<?PHP
require_once("conexao/banco.php");

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
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;

// Consulta para buscar todos os itens comprados agrupados por venda
$sql2 = "SELECT * 
         FROM tb_itens_compra AS i
         LEFT JOIN tb_produtos AS p ON (i.pro_codigo = p.pro_codigo)";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql3!");

$itens_comprados = [];
while ($item = mysqli_fetch_assoc($sql2)) {
    $itens_comprados[$item['com_codigo']][] = $item;
}

$sql5 = "SELECT *
         FROM tb_despesas
        ";
$sql5 = mysqli_query($con, $sql5) or die ("Erro na sql!") ;

?>  

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistema - Neo Enigma </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logocima.png">
    <!-- Custom Stylesheet -->
    <link href="./css/pesquisa.css" rel="stylesheet">
    <link href="./css/form.css" rel="stylesheet">
    <link href="./css/export.css" rel="stylesheet">
    <link href="./css/table.css" rel="stylesheet">
    <link href="./css/icon.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">


</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img class="logo-abbr" src="./images/logo.png" alt="">
                <img class="logo-compact" src="./images/logo-text.png" alt="">
                <img class="brand-title" src="./images/logo-text.png" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="logout.php" class="dropdown-item">
                                        <i class="icon-key"></i>
                                        <span class="ml-2">Sair</span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include("menu.php"); ?>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Compras</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Compras</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h4 class="card-header">
                                Consulta de Compras
                                <a href="form_cadastro_compra.php">
                                    <button name="btn_salvar" class="btn-cadastro mb-1 float-right">+</button>
                                </a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="compras">
                                        <input type="hidden" name="des_nome" value="<?php echo htmlspecialchars($nome); ?>">
                                        <input type="hidden" name="for_fornecedor" value="<?php echo htmlspecialchars($fornecedor); ?>">
                                        <input type="hidden" name="tipo_pag" value="<?php echo htmlspecialchars($tipo_pagamento); ?>">
                                        <input type="hidden" name="data" value="<?php echo htmlspecialchars($data); ?>">

                                        <div class="export-buttons mb-3">
                                            <button type="button" id="exportPdf" class="btn btn-danger">Exportar para PDF</button>
                                            <button type="button" id="exportExcel" class="btn btn-success">Exportar para Excel</button>
                                        </div>
                                    </form>

                                    <script>
                                        document.getElementById('exportPdf').addEventListener('click', function () {
                                            // Mostra o SweetAlert de confirmação para PDF
                                            Swal.fire({
                                                title: 'Tem certeza?',
                                                text: "Você deseja realmente exportar os dados para PDF?",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Sim, exportar!',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Se o usuário confirmar, envia o formulário para PDF
                                                    document.forms['frm_exportar'].action = "export/export_pdf.php";
                                                    document.forms['frm_exportar'].submit();
                                                }
                                            });
                                        });

                                        document.getElementById('exportExcel').addEventListener('click', function () {
                                            // Mostra o SweetAlert de confirmação para Excel
                                            Swal.fire({
                                                title: 'Tem certeza?',
                                                text: "Você deseja realmente exportar os dados para Excel?",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Sim, exportar!',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Se o usuário confirmar, envia o formulário para Excel
                                                    document.forms['frm_exportar'].action = "export/export_excel.php";
                                                    document.forms['frm_exportar'].submit();
                                                }
                                            }); 
                                        });
                                    </script>
                                    <form name="frm_consulta" method="GET" action="" class="filter-form">
                                        <div class="filter-form-content">
                                            <div class="filter-form-group">
                                                <select class="form-control" name="des_nome" id="tipo_despesa">
                                                    <option selected disabled>Tipo Despesa</option>
                                                        <?php while ($dados5 = mysqli_fetch_array($sql5)) { ?>
                                                            <option value="<?php echo $dados5['des_nome']; ?>"><?php echo $dados5['des_nome']; ?></option>
                                                        <?php } ?>
                                                </select>
                                                <input name="for_fornecedor" type="text" id="cliente" class="form-control" placeholder="Nome fornecedor">
                                                <select class="form-control" name="tipo_pag" id="tipo_pagamento" >
                                                    <option value="" checked>Método pagamento</option>
                                                    <option value="1">Dinheiro</option>
                                                    <option value="2">Cartão</option>
                                                    <option value="3">PIX</option>
                                                </select>
                                                <div class="date-input-container">
                                                    <input name="data" type="date" id="CNPJ" class="form-control" placeholder="">
                                                    <span class="date-label">Data da Compra</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="filter-form-buttons">
                                            <button id="botaoS" type="submit" class="btn-filter btn-filter-primary">Filtrar</button>
                                            <button id="botaoLimpar" type="submit" class="btn-filter btn-filter-secondary" onclick="limparPesquisa()">Limpar Filtro</button>
                                        </div>
                                        <script>
                                            function limparPesquisa() {
                                                document.querySelector("form[name='frm_consulta']").reset();  // Reseta os campos do formulário
                                            }
                                        </script>
                                    </form>
                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo da Despesa</th>
                                                <th>Fornecedor</th>
                                                <th>Tipo Pagamento</th>
                                                <th>Data da Compra</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                            <tr>
                                                <th><?php echo $dados['com_codigo']; ?></th>
                                                <td><?php echo $dados['des_nome']; ?></td>
                                                <td><?php echo $dados['for_nome']; ?></td>
                                                <td>
                                                    <?php
                                                        if ($dados['com_tipo_pagamento'] == 1) {
                                                            echo 'Dinheiro';
                                                        } elseif ($dados['com_tipo_pagamento'] == 2) {
                                                            echo 'Cartão';
                                                        } else {
                                                            echo 'PIX';
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($dados['com_data'])); ?></td>
                                                <td>
                                                    <span>
                                                        <a href="javascript:void(0)" class="mr-4" data-toggle="tooltip" data-placement="top" title="Consultar Detalhes" onclick="toggleDetails(<?php echo $dados['com_codigo']; ?>)">
                                                            <i class="fa fa-plus fa-lg color-primary"></i>
                                                        </a>
                                                        <a href="form_atualizar_compra.php?compra=<?php echo $dados['com_codigo']; ?>" class="mr-4" data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fa fa-pencil fa-lg color-warning"></i> 
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['com_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                        </a>
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            function confirmDelete(com_codigo) {
                                                                Swal.fire({
                                                                    title: 'Você tem certeza?',
                                                                    text: "Essa ação não poderá ser desfeita!",
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Sim, deletar!',
                                                                    cancelButtonText: 'Cancelar'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        window.location.href = 'deletar/delete_compra.php?com_codigo=' + com_codigo;
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </span>
                                                </td>
                                            </tr>
                                            
                                            <style>
                                                /* Estilo para a linha de detalhes da compra */
                                                tr.details-row {
                                                    background-color: #f9f9f9; /* Fundo claro para destacar os detalhes */
                                                }
                                                tr.details-row td {
                                                    padding: 15px; /* Espaçamento interno nas células */
                                                    border-top: 1px solid #ddd; /* Linha superior para separação */
                                                }
                                                /* Estilo para o conteúdo interno da linha de detalhes */
                                                .details-content {
                                                    background-color: #f7f7f7; /* Fundo para o container interno */
                                                    padding: 15px; /* Espaçamento interno */
                                                    border-radius: 8px; /* Bordas arredondadas */
                                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra leve para destaque */
                                                }
                                                .details-content h6 {
                                                    font-weight: bold; /* Título em negrito */
                                                    margin-bottom: 10px;
                                                }
                                                .details-content ul {
                                                    list-style-type: none; /* Remove marcadores da lista */
                                                    padding-left: 0;
                                                    margin-bottom: 10px;
                                                }
                                                .details-content ul li {
                                                    padding: 5px 0; /* Espaçamento entre os itens da lista */
                                                    border-bottom: 1px solid #eaeaea; /* Linha inferior para separação */
                                                }
                                                .details-content ul li:last-child {
                                                    border-bottom: none; /* Remove a última linha inferior */
                                                }
                                                .details-content p {
                                                    font-weight: bold; /* Negrito para o total */
                                                }
                                            </style>

                                            <!-- Linha expansível com os detalhes da compra -->
                                            <tr id="details-<?php echo $dados['com_codigo']; ?>" class="details-row" style="display: none;">
                                                <td colspan="5">
                                                    <div class="details-content">
                                                        <h6><strong>Itens Comprados:</strong></h6>
                                                        <ul>
                                                            <?php
                                                            $total_compra = 0;  // Inicializa o total da compra
                                                            if (!empty($itens_comprados[$dados['com_codigo']])) {
                                                                foreach ($itens_comprados[$dados['com_codigo']] as $item) {
                                                                    $subtotal = $item['itc_total'];  // Pega o subtotal do item
                                                                    $total_compra += $subtotal;  // Adiciona ao total da compra
                                                            ?>
                                                            <li>
                                                                <?php echo $item['pro_nome']; ?> - Quantidade: <?php echo $item['itc_qtde']; ?> - Preço Unitário: R$ <?php echo $item['itc_preco']; ?> - Subtotal: R$ <?php echo $subtotal; ?>
                                                            </li>
                                                            <?php } } else { ?>
                                                            <li>Sem itens cadastrados para esta compra.</li>
                                                            <?php } ?>
                                                        </ul>

                                                        <h6><strong>Total da Compra:</strong></h6>
                                                        <p>Total: R$ <?php echo number_format($total_compra, 2, ',', '.'); ?></p>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- JavaScript para exibir/ocultar detalhes -->
         <script>
            let activeRow = null;

            function toggleDetails(vendaId) {
                const currentRow = document.getElementById('details-' + vendaId);

                // Se houver uma linha aberta, fecha
                if (activeRow && activeRow !== currentRow) {
                    activeRow.style.display = 'none';
                }

                // Alterna a exibição da linha clicada
                if (currentRow.style.display === 'none') {
                    currentRow.style.display = 'table-row';
                    activeRow = currentRow;  // Define a nova linha ativa
                } else {
                    currentRow.style.display = 'none';
                    activeRow = null;  // Remove a referência da linha ativa
                }
            }
        </script>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Neo Enigma</a> 2024</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->

        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    



</body>

</html>