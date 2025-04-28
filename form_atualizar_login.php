<?php

require_once('conexao/banco.php');

$id = $_REQUEST['log_codigo'];
$sql = "select * from tb_login where log_codigo = '$id'";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;
$dados = mysqli_fetch_array($sql);

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
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Atualizar Login</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Atualização de Usuários</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                    <form name="frm_login" method="post" action="atualizar/atualizar_login.php">

                                        <div class="col-lg-12 mb-4">
                                            <label>Código</label>
                                            <input type="text" class="form-control" placeholder="" readonly name="txt_codigo" id="txt_codigo" value="<?php echo $dados['log_codigo']; ?>">                                           
                                        </div>
                                    
                                        <div class="col-lg-12 mb-4">
                                            <label>Nome</label>
                                            <input type="text" class="form-control" name="txt_nome" id="txt_nome" placeholder="Digite seu Nome" value="<?php echo $dados['log_nome']; ?>"> 
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Digite seu email" value="<?php echo $dados['log_email']; ?>"> 
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Usuário</label>
                                            <input type="text" class="form-control" name="txt_login" id="txt_login" placeholder="Digite seu usuário" value="<?php echo $dados['log_usuario']; ?>">    
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Senha</label>
                                            <input type="password" class="form-control" name="txt_senha" id="txt_senha" placeholder="Digite sua Senha" value="<?php echo $dados['log_senha']; ?>">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <button type="submit" id="btn_salvar" class="btn btn-primary">Atualizar</button>
                                        </div>

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