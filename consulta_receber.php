<?PHP
session_start(); // Inicia a sessão
require_once('conexao/banco.php');

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



$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;

$sql2 = "SELECT *
         FROM tb_credito
        ";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql!") ;

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
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/icon.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Adicione a biblioteca do Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Biblioteca Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
<?php
    if (isset($_SESSION['success_message'])) {
        // Exibe a notificação com a mensagem da sessão
        echo "<script>
            $(document).ready(function () {
                toastr.success('" . $_SESSION['success_message'] . "', 'Sucesso', {
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                    closeButton: true,
                    progressBar: true
                });
            });
        </script>";
        // Limpa a mensagem da sessão após exibi-la
        unset($_SESSION['success_message']);
    }
    ?>

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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Contas a Receber</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Contas a Receber</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h4 class="card-header">
                                Consulta de Contas a Receber
                                <a href="form_cadastro_receber.php">
                                    <button name="btn_salvar" class="btn-cadastro mb-1 float-right">+</button>
                                </a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="contasrec">
                                        <input type="hidden" name="credito" value="<?php echo htmlspecialchars($credito); ?>">
                                        <input type="hidden" name="valor" value="<?php echo htmlspecialchars($nome); ?>">
                                        <input type="hidden" name="nome" value="<?php echo htmlspecialchars($cpf); ?>">
                                        <input type="hidden" name="vencimento" value="<?php echo htmlspecialchars($vencimento); ?>">
                                        <input type="hidden" name="pagamento" value="<?php echo htmlspecialchars($pagamento); ?>">
                                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">

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
                                                <select class="form-control flex-grow-1" name="credito" id="txt_credito">
                                                    <option value="" checked>Tipo crédito</option>
                                                    <?php while ($dados2 = mysqli_fetch_array($sql2)) { ?>
                                                        <option value="<?php echo $dados2['cre_nome']; ?>"><?php echo $dados2['cre_nome']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input name="nome" type="text" id="cliente" class="form-control" placeholder="Nome do cliente">
                                                <input name="cpf" oninput="aplicarMascaraCPF(this)" maxlength="14" type="text" id="cpf" class="form-control" placeholder="CPF">
                                                <script>
                                                    function aplicarMascaraCPF(elemento) {
                                                        elemento.value = cpf(elemento.value); // Aplica a máscara ao valor atual do campo
                                                    }

                                                    function cpf(valor) {
                                                        // Remove tudo que não for dígito
                                                        valor = valor.replace(/\D/g, "");      

                                                        // Adiciona pontos e hífen conforme o padrão CPF (000.000.000-00)
                                                        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
                                                        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
                                                        valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

                                                        return valor;
                                                    }
                                                </script>
                                                <input name="valor" type="text" id="valor" class="form-control" placeholder="Valor">
                                            </div>
                                        </div>  
                                        <div class="filter-form-content">
                                            <div class="filter-form-group">
                                                <div class="date-input-container">
                                                    <input name="vencimento" type="date" id="vencimento" class="form-control" placeholder="">
                                                    <span class="date-label">Data de Vencimento</span>
                                                </div>
                                                <div class="date-input-container">
                                                    <input name="pagamento" type="date" id="pagamento" class="form-control" placeholder="">
                                                    <span class="date-label">Data de Vencimento</span>
                                                </div>
                                                <select class="form-control" name="status" id="txt_status">
                                                    <option value="" checked>Status</option>
                                                    <option value="Pendente"> Pendente </option>
                                                    <option value="Pago"> Pago </option>
                                                </select>
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
                                                <th>Tipo Crédito</th>
                                                <th>Nome Cliente</th>
                                                <th>CPF</th>
                                                <th>Valor</th>
                                                <th>Vencimento</th>
                                                <th>Pagamento</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                            <tr>
                                                <th><?php echo $dados['cr_codigo']; ?></th>
                                                <td><?php echo $dados['cre_nome']; ?></td>
                                                <td><?php echo $dados['cli_nome']; ?></td>  
                                                <td><?php echo $dados['cli_cpf']; ?></td>
                                                <td><?php echo $dados['cr_valor']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($dados['cr_data_vencimento'])); ?></td>
                                                <td><?php echo $dados['cr_data_pagamento'] ? date('d/m/Y', strtotime($dados['cr_data_pagamento'])) : '—'; ?></td>
                                                <td><span class="badge <?php echo $dados['cr_status'] == 'Pago' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo $dados['cr_status']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                <span>
                                                <?php if ($dados['cr_status'] != 'Pago') { ?>
                                                    <a href="javascript:void(0);" class="mr-4" data-toggle="tooltip" data-placement="top" title="Marcar como pago" onclick="confirmPayment(<?php echo $dados['cr_codigo']; ?>);">
                                                        <i class="fa fa-check fa-lg color-success"></i>
                                                    </a>
                                                    <a href="form_atualizar_receber.php?cr_codigo=<?php echo $dados['cr_codigo']; ?>" class="mr-4" data-toggle="tooltip" data-placement="top" title="Editar">
                                                        <i class="fa fa-pencil fa-lg color-warning"></i> 
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['cr_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                        <i class="fa fa-close fa-lg color-danger"></i>
                                                    </a>
                                                    
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                        function confirmPayment(cr_codigo) {
                                                            Swal.fire({
                                                                title: 'Tem certeza que deseja marcar como pago?',
                                                                icon: 'info',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Sim, marcar como pago',
                                                                cancelButtonText: 'Cancelar'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    window.location.href = 'atualizar/atualizar_status_receber.php?cr_codigo=' + cr_codigo;
                                                                }
                                                            });
                                                        }

                                                        function confirmDelete(cr_codigo) {
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
                                                                    window.location.href = 'deletar/delete_receber.php?cr_codigo=' + cr_codigo;
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['cr_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                        <i class="fa fa-close fa-lg color-danger"></i>
                                                    </a>
                                                    
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                        function confirmDelete(cr_codigo) {
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
                                                                    window.location.href = 'deletar/delete_receber.php?cr_codigo=' + cr_codigo;
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                <?php } ?>
                                            </span>

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
    <script src="https://unpkg.com/imask"></script>
    <script src="js/mask.js"></script>

</body>

</html>