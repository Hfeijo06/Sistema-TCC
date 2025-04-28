<?php
require_once("conexao/banco.php");
    
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
$sql = mysqli_query($con, $sql) or die ("Erro na sql!");

// Consulta para buscar todas as parcelas agrupadas por venda
$sql2 = "SELECT *
         FROM tb_contas_receber AS c
         INNER JOIN tb_parcelas AS p ON (c.parc_codigo = p.parc_codigo)
         INNER JOIN tb_pagamento AS pg ON (p.pag_codigo = pg.pag_codigo)";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!");

// Consulta para buscar todos os itens vendidos agrupados por venda
$sql3 = "SELECT * 
         FROM tb_itens_venda AS i
         LEFT JOIN tb_produtos AS p ON (i.pro_codigo = p.pro_codigo)";
$sql3 = mysqli_query($con, $sql3) or die ("Erro na sql3!");

// Transformar os resultados das parcelas e itens em arrays
$parcelas = [];
while ($parcela = mysqli_fetch_assoc($sql2)) {
    $parcelas[$parcela['ven_codigo']][] = $parcela;
}

$itens_vendidos = [];
while ($item = mysqli_fetch_assoc($sql3)) {
    $itens_vendidos[$item['ven_codigo']][] = $item;
}

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
    <link href="./css/icon.css" rel="stylesheet">
    <link href="./css/table.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        .badge-success{
            color: white;
            background-color: #297F00;
        }
        .badge-danger{
            color: white;
            background-color: #EE3232;
        }
    </style>

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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Vendas</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Vendas</a></li>
                        </ol>
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h4 class="card-header">
                                Consulta de Vendas
                                <a href="form_cadastro_venda.php">
                                    <button name="btn_salvar" class="btn-cadastro mb-1 float-right">+</button>
                                </a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="vendas">
                                        <input type="hidden" name="cli_codigo" value="<?php echo htmlspecialchars($cliente); ?>">
                                        <input type="hidden" name="ven_tipo_venda" value="<?php echo htmlspecialchars($tipo); ?>">
                                        <input type="hidden" name="ven_status_entrega" value="<?php echo htmlspecialchars($status); ?>">
                                        <input type="hidden" name="ven_data_lancamento" value="<?php echo htmlspecialchars($lancamento); ?>">
                                        <input type="hidden" name="ven_data_entrega" value="<?php echo htmlspecialchars($entrega); ?>">

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
                                                <select class="form-control" name="ven_tipo_venda">
                                                    <option checked value="">Tipo venda</option>
                                                    <option value="Entrega">Entrega</option>
                                                    <option value="Presencial">Presencial</option>
                                                </select>
                                                <input name="cli_codigo" type="text" id="CNPJ" class="form-control" placeholder="Nome do cliente">
                                                <select class="form-control" name="ven_status_entrega">
                                                    <option checked value="">Status entrega</option>
                                                    <option value="Pendente">Pendente</option>
                                                    <option value="transito">Em Trânsito</option>
                                                    <option value="Entregue">Entregue</option>
                                                </select>
                                                <div class="date-input-container">
                                                    <input name="ven_data_lancamento" type="date" id="CNPJ" class="form-control" placeholder="">
                                                    <span class="date-label">Data da Venda</span>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="filter-form-group">
                                                <div class="date-input-container">
                                                    <input name="ven_data_entrega" type="date" id="CNPJ" class="form-control" placeholder="">
                                                    <span class="date-label">Data da Entrega</span>
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
                                                <th>Tipo da Venda</th>
                                                <th>Cliente</th>
                                                <th>Data da Venda</th>
                                                <th>Data da Entrega</th>
                                                <th>Status da Entrega</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_assoc($sql)) { ?>
                                            <tr>
                                                <th><?php echo $dados['ven_codigo']; ?></th>
                                                <td><?php echo $dados['ven_tipo_venda']; ?></td>
                                                <td><?php echo $dados['cli_nome']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($dados['ven_data_lancamento'])); ?></td>
                                                <td>
                                                    <?php 
                                                        if ($dados['ven_tipo_venda'] == 'Presencial') {
                                                            echo '-';
                                                        } else {
                                                            echo date('d/m/Y', strtotime($dados['ven_data_entrega']));
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($dados['ven_tipo_venda'] == 'Presencial'): ?>
                                                        <span>-</span>
                                                    <?php else: ?>
                                                        <span class="badge 
                                                            <?php 
                                                                if ($dados['ven_status_entrega'] == 'Entregue') {
                                                                    echo 'badge-success';  // Verde
                                                                } elseif ($dados['ven_status_entrega'] == 'Em Trânsito') {
                                                                    echo 'badge-warning';  // Amarelo
                                                                } else {
                                                                    echo 'badge-danger';  // Vermelho
                                                                }
                                                            ?>">
                                                            <?php 
                                                                echo $dados['ven_status_entrega'] == 'transito' ? 'Em Trânsito' : $dados['ven_status_entrega']; 
                                                            ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <span>
                                                        <a href="javascript:void(0)" class="mr-4" data-toggle="tooltip" data-placement="top" 
                                                        title="Consultar Detalhes" onclick="toggleDetails(<?php echo $dados['ven_codigo']; ?>)">
                                                            <i class="fa fa-plus fa-lg color-primary"></i>
                                                        </a>

                                                        <a href="comprovantepdf.php?venda=<?php echo $dados['ven_codigo']; ?>" target="_blank" class="mr-4" 
                                                        data-toggle="tooltip" data-placement="top" title="Comprovante de Venda">
                                                            <i class="fa fa-file-invoice fa-lg color-invoice"></i>
                                                        </a>

                                                        <?php 
                                                        // Verifica se todas as parcelas estão pagas
                                                        $todasPagas = true;
                                                        if (!empty($parcelas[$dados['ven_codigo']])) {
                                                            foreach ($parcelas[$dados['ven_codigo']] as $parcela) {
                                                                if ($parcela['cr_status'] != 'Pago') {
                                                                    $todasPagas = false;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>

                                                        <?php if (!$todasPagas): ?>
                                                        <a href="form_atualizar_venda.php?venda=<?php echo $dados['ven_codigo']; ?>" class="mr-4" 
                                                        data-toggle="tooltip" data-placement="top" title="Editar">
                                                            <i class="fa fa-pencil fa-lg color-warning"></i>
                                                        </a>
                                                        <?php endif; ?>

                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['ven_codigo']; ?>)" 
                                                        data-toggle="tooltip" data-placement="top" title="Deletar">
                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                        </a>

                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            function confirmDelete(ven_codigo) {
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
                                                                        window.location.href = 'deletar/delete_venda.php?ven_codigo=' + ven_codigo;
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </span>
                                                </td>

                                            </tr>

                                            <style>
                                                /* Estilo para a linha de detalhes da venda */
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

                                            <!-- Linha expansível com os detalhes da venda -->
                                            <tr id="details-<?php echo $dados['ven_codigo']; ?>" class="details-row" style="display: none;">
                                            <td colspan="5">
                                                <div class="details-content">
                                                    <h6><strong>Itens Vendidos:</strong></h6>
                                                    <ul>
                                                        <?php
                                                        $total_venda = 0;
                                                        if (!empty($itens_vendidos[$dados['ven_codigo']])) {
                                                            foreach ($itens_vendidos[$dados['ven_codigo']] as $item) {
                                                                $subtotal = $item['itv_total'];
                                                                $total_venda += $subtotal;
                                                        ?>
                                                        <li>
                                                            <?php echo $item['pro_nome']; ?> - Quantidade: <?php echo $item['itv_qtde']; ?> - Preço Unitário: R$ <?php echo $item['itv_preco']; ?> - Subtotal: R$ <?php echo $subtotal; ?>
                                                        </li>
                                                        <?php } } else { ?>
                                                        <li>Sem itens cadastrados para esta venda.</li>
                                                        <?php } ?>
                                                    </ul>

                                                    <h6><strong>Parcelas:</strong></h6>
                                                    <ul>
                                                        <?php if (!empty($parcelas[$dados['ven_codigo']])) {
                                                            foreach ($parcelas[$dados['ven_codigo']] as $parcela) { ?>
                                                            <li>Parcela <?php echo $parcela['parc_numero_parcela']; ?> - Valor: R$ <?php echo $parcela['parc_valor']; ?> - Vencimento: <?php echo date('d/m/Y', strtotime($parcela['parc_data_vencimento'])); ?> - Status: <?php echo $parcela['cr_status']; ?></li>
                                                        <?php } } else { ?>
                                                        <li>Sem parcelas cadastradas para esta venda.</li>
                                                        <?php } ?>
                                                    </ul>

                                                    <h6><strong>Total da Venda:</strong></h6>
                                                    <p>Total: R$ <?php echo number_format($total_venda, 2, ',', '.'); ?></p>
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