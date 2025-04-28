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
    <link href="./css/style.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">


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

                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Usuários</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário de Usuários</a></li>
                        </ol>
                    </div>
                </div>
                
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Usuários</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                <form name="frm_login" method="post" action="cadastro/cadastro_login.php" id="frm_login">
                                    <div class="col-lg-12 mb-4">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" name="txt_nome" id="txt_nome" placeholder="Digite seu Nome">
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <label>Email</label>
                                        <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Digite seu email">
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <label>Usuário</label>
                                        <input type="text" class="form-control" name="txt_login" id="txt_login" placeholder="Digite seu usuário">
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <label>Senha</label>
                                        <input type="password" class="form-control" name="txt_senha" id="txt_senha" placeholder="Digite sua Senha">
                                    </div>

                                    <div class="button-container">
                                        <button type="button" id="btn_cancelar" class="btn btn-cancel">Cencelar</button>
                                        <button type="submit" id="btn_salvar1" class="btn btn-primary">Cadastrar</button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById("btn_salvar1").addEventListener("click", function(event) {
                                        event.preventDefault(); // Impede o envio automático do formulário

                                        // Obtém os valores dos campos
                                        var nome = document.getElementById("txt_nome");
                                        var email = document.getElementById("txt_email");
                                        var login = document.getElementById("txt_login");
                                        var senha = document.getElementById("txt_senha");

                                        var valid = true; // Para verificar se o formulário é válido

                                        // Função para adicionar borda vermelha e focar no campo
                                        function marcarErro(campo) {
                                            campo.style.borderColor = 'red'; // Adiciona borda vermelha
                                            if (valid) {
                                                campo.focus(); // Foca no primeiro campo inválido
                                                valid = false; // Define como inválido
                                            }
                                        }

                                        // Verifica se os campos obrigatórios estão preenchidos
                                        if (nome.value === "") {
                                            marcarErro(nome);
                                        } else {
                                            nome.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        if (email.value === "" || !email.value.includes("@")) {
                                            marcarErro(email);
                                        } else {
                                            email.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        if (login.value === "") {
                                            marcarErro(login);
                                        } else {
                                            login.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        if (senha.value === "") {
                                            marcarErro(senha);
                                        } else {
                                            senha.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        // Se o formulário estiver válido, envia o formulário
                                        if (valid) {
                                            document.getElementById("frm_login").submit();
                                        }
                                    });

                                    document.getElementById("btn_cancelar").addEventListener("click", function() {
                                        // Redireciona para um script PHP que configura a sessão
                                        window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                        document.forms["frm_login"].reset();
                                    });
                                </script>



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
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    
</body>

</html>