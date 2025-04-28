<?PHP

require_once('conexao/banco.php');

$produto = isset($_REQUEST['pro_codigo']) ? $_REQUEST['pro_codigo'] : '';
$qtde = isset($_REQUEST['est_qtde']) ? $_REQUEST['est_qtde'] : '';

$sql = "select * 
			from tb_estoque as e
			inner join tb_produtos as p on (e.pro_codigo = p.pro_codigo)
            WHERE 
            pro_descricao like '%".$produto."%' AND
            est_qtde like '%".$qtde."%'            
            ORDER BY est_codigo DESC";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!");
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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Estoque</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Estoque</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Consulta de Estoque</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="estoque">
                                        <input type="hidden" name="pro_codigo" value="<?php echo htmlspecialchars($produto); ?>">
                                        <input type="hidden" name="est_qtde" value="<?php echo htmlspecialchars($qtde); ?>">

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
                                                <input name="pro_codigo" type="text" id="cliente" class="form-control" placeholder="Nome produto">
                                                <input name="est_qtde" type="text" id="CNPJ" class="form-control" placeholder="Quantidade">
                                            </div>
                                        </div>
                                        <div class="filter-form-buttons">
                                            <button id="botaoS" type="submit" class="btn-filter btn-filter-primary">Filtrar</button>
                                            <button id="botaoLimpar" type="submit" class="btn-filter btn-filter-secondary" onclick="limparPesquisa()">Limpar Filtro</button>
                                        </div>
                                    </form>
                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Produto</th>
                                                <th>Quantidade</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                            <tr>
                                                <th><?php echo $dados['est_codigo']; ?></th>
                                                <td><?php echo $dados['pro_descricao']; ?></td>
                                                <td><?php echo $dados['est_qtde']; ?></td>
                                                <td>
                                                    <span>
                                                        <a href="form_atualizar_estoque.php?est_codigo=<?php echo $dados['est_codigo']; ?>" class="mr-4" data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fa fa-pencil fa-lg color-warning"></i> 
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['est_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                        </a>
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            function confirmDelete(est_codigo) {
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
                                                                        window.location.href = 'deletar/delete_estoque.php?est_codigo=' + est_codigo;
                                                                    }
                                                                });
                                                            }
                                                        </script>
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
    



</body>

</html>