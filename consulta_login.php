<?PHP
session_start(); // Inicia a sessão

require_once('conexao/banco.php');
$sql = "select * from tb_login ORDER BY log_codigo DESC";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;

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
    <link href="./css/form.css" rel="stylesheet">
    <link href="./css/export.css" rel="stylesheet"> 
    <link href="./css/table.css" rel="stylesheet">
    <link href="./css/icon.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

     <!-- Adicione a biblioteca do Toastr -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Biblioteca Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>

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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Usuários</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Usuários</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h4 class="card-header">
                                Consulta de Usuários
                                <a href="form_cadastro_login.php">
                                    <button name="btn_salvar" class="btn-cadastro mb-1 float-right">+</button>
                                </a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                <!-- Botões para exportação -->
                                <div class="export-buttons mb-3">
                                    <a href="export/export_pdf.php?relatorio=usuarios" id="exportPdf" class="btn btn-danger">Exportar para PDF</a>
                                    <a href="export/export_excel.php?relatorio=usuarios" id="exportExcel" class="btn btn-success">Exportar para Excel</a>
                                </div>

                                <script>
                                    document.getElementById('exportExcel').addEventListener('click', function (e) {
                                        e.preventDefault(); // Evita o redirecionamento imediato
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
                                                window.location.href = e.target.href; // Redireciona para a exportação
                                            }
                                        })
                                    });

                                    document.getElementById('exportPdf').addEventListener('click', function (e) {
                                        e.preventDefault(); // Evita o redirecionamento imediato
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
                                                window.location.href = e.target.href; // Redireciona para a exportação
                                            }
                                        })
                                    });
                                </script>


                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Usuário</th>
                                                <th>Senha</th>
                                                <th>Data Cadastro</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                            <tr>
                                                <th><?php echo $dados['log_codigo']; ?></th>
                                                <td><?php echo $dados['log_nome']; ?></td>
                                                <td><?php echo $dados['log_email']; ?></td>
                                                <td><?php echo $dados['log_usuario']; ?></td>
                                                <td><?php echo str_repeat('*', strlen($dados['log_senha'])); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($dados['log_data_cadastro'])); ?></td>
                                                <td>
                                                    <span>
                                                        <a href="form_atualizar_login.php?log_codigo=<?php echo $dados['log_codigo']; ?>" class="mr-4" data-toggle="tooltip" data-placement="top" title="Editar">
                                                            <i class="fa fa-pencil fa-lg color-warning"></i> 
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['log_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                        </a>
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            function confirmDelete(log_codigo) {
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
                                                                        window.location.href = 'deletar/delete_login.php?log_codigo=' + log_codigo;
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