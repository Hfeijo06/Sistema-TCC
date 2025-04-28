<?php

require_once('conexao/banco.php');

$id = $_REQUEST['cr_codigo'];
$sql = "select * from tb_contas_receber where cr_codigo = '$id'";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;
$dados = mysqli_fetch_array($sql);

$sql4 = "select *
          from tb_contas_receber as r
          inner join tb_credito as cr on (r.cre_codigo = cr.cre_codigo)
		  where r.cr_codigo = '$id'";
$sql4 = mysqli_query($con, $sql4) or die("Erro na sql4!");
$dados4 = mysqli_fetch_array($sql4);

$sql5 = "SELECT *
         FROM tb_credito
        ";
$sql5 = mysqli_query($con, $sql5) or die("Erro na sql!");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistema - Neo Enigma </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logocima.png">
    <!-- Custom Stylesheet -->
    <link href="./css/style.css" rel="stylesheet">
    <style>
    .button-container {
        display: flex;
        gap: 10px; /* Ajuste o valor conforme necessário */
        margin-left: 15px;
    }
    .btn-cancel {
        background-color: red; /* Verde claro */
        border: none;
        color: white;
    }

    .btn-cancel:hover {
        background-color: #c70000;
        color: white; /* Verde escuro para efeito hover */
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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Atualizar</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Atualizar Contas a Receber</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Atualização de Contas à Receber</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                    <form name="frm_receber" method="post" action="atualizar/atualizar_receber.php">

                                        <div class="col-lg-12 mb-4">
                                            <label>Código</label>
                                            <input type="text" class="form-control" name="txt_codigo" id="txt_codigo" placeholder="" readonly value="<?php echo $dados['cr_codigo']; ?>">                                           
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo de Crédito</label>
                                                <div class="d-flex">
                                                    <select class="form-control flex-grow-1" name="txt_credito" id="txt_credito">
                                                        <option value="<?php echo $dados4['cre_codigo']; ?>"> <?php echo $dados4['cre_nome']; ?> </option>
                                                        <?php while ($dados5 = mysqli_fetch_array($sql5)) { ?>
                                                            <option value="<?php echo $dados5['cre_codigo']; ?>"><?php echo $dados5['cre_nome']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Valor</label>
                                            <input type="number" class="form-control" name="txt_valor" id="txt_valor" placeholder="" value="<?php echo $dados['cr_valor']; ?>">    
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Vencimento</label>
                                            <input type="date" class="form-control" name="txt_vencimento" id="txt_vencimento" placeholder="" value="<?php echo $dados['cr_data_vencimento']; ?>">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control" name="txt_status" id="txt_status" >
                                                    <option <?php if ($dados['cr_status'] == "pendente") { echo "selected";} ?> > Pendente </option>
                                                    <option <?php if ($dados['cr_status'] == "pago") { echo "selected";} ?> > Pago </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <button type="button" id="btn_cancelar" class="btn btn-cancel">Cencelar</button>
                                            <button type="submit" id="btn_salvar" class="btn btn-primary">Atualizar</button>
                                        </div>

                                        <script>
                                            document.getElementById("btn_cancelar").addEventListener("click", function() {
                                                // Redireciona para um script PHP que configura a sessão
                                                window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                                document.forms["frm_login"].reset();
                                            });
                                        </script>

                                    </form>
                                </div>
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
    
</body>

</html>