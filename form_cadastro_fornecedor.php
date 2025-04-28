<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistema - Neo Enigma </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Favicon icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

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
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Fornecedores</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário Fornecedores</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Fornecedores</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                    <form name="frm_fornecedor" method="post" action="cadastro/cadastro_fornecedor.php">

                                        <div class="col-lg-12 mb-4">
                                            <label>Nome</label>
                                            <input type="text" class="form-control" name="txt_nome" id="txt_nome" placeholder="Digite o Nome do Fornecedor">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>CNPJ</label>
                                            <input onkeypress="mascaraCNPJ(this)" maxlength="18" type="text" class="form-control" name="txt_cnpj" id="txt_cnpj" placeholder="__.___.___/____-__">
                                        </div>

                                        <script>
                                            function mascaraCNPJ(input) {
                                                let value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos
                                                if (value.length <= 14) {
                                                    value = value.replace(/^(\d{2})(\d)/, '$1.$2'); // Adiciona ponto após os 2 primeiros dígitos
                                                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3'); // Adiciona ponto após o bloco de 5 dígitos
                                                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2'); // Adiciona barra após o bloco de 8 dígitos
                                                    value = value.replace(/(\d{4})(\d)/, '$1-$2'); // Adiciona hífen após o bloco de 12 dígitos
                                                }
                                                input.value = value;
                                            }
                                        </script>

                                        <div class="col-lg-12 mb-4">
                                            <label>Descrição</label>
                                            <textarea class="form-control" rows="4" name="txt_descricao" id="txt_descricao" placeholder="Faça uma Breve Descrição do Fornecedor"></textarea>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Digite o Email do Fornecedor">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Telefone</label>
                                            <input onkeypress="mascara(this, telefone)" maxlength="8" type="text" class="form-control" name="txt_telefone" id="txt_telefone" placeholder="____-____">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Celular</label>
                                            <input onkeypress="mascara(this, celular)" maxlength="15" type="text" class="form-control" name="txt_celular" id="txt_celular" placeholder="() _____-____">
                                        </div>

                                        <div class="button-container">
                                            <button type="button" id="btn_cancelar" class="btn btn-cancel">Cencelar</button>
                                            <button type="submit" id="btn_salvar1" class="btn btn-primary">Cadastrar</button>
                                        </div>

                                        <script>
                                            document.getElementById("btn_cancelar").addEventListener("click", function() {
                                                // Redireciona para um script PHP que configura a sessão
                                                window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                                document.forms["frm_fornecedor"].reset();
                                            });
                                        </script>

                                    </form>
                                    <script>
                                        function validarFormularioFornecedor() {
                                            let isValid = true;

                                            // Função para destacar campos vazios
                                            function destacarCampo(campo) {
                                                campo.style.borderColor = "red";
                                                campo.focus();
                                                isValid = false;
                                            }

                                            // Função para remover o destaque
                                            function removerDestaque(campo) {
                                                campo.style.borderColor = "";
                                            }

                                            // Campos obrigatórios
                                            const nome = document.getElementById('txt_nome');
                                            const cnpj = document.getElementById('txt_cnpj');
                                            const descricao = document.getElementById('txt_descricao');
                                            const email = document.getElementById('txt_email');
                                            const celular = document.getElementById('txt_celular');

                                            // Verificando se os campos estão preenchidos
                                            if (nome.value === "") {
                                                destacarCampo(nome);
                                            } else {
                                                removerDestaque(nome);
                                            }

                                            if (cnpj.value === "" || cnpj.value.length < 18) {
                                                destacarCampo(cnpj);
                                            } else {
                                                removerDestaque(cnpj);
                                            }

                                            if (descricao.value === "") {
                                                destacarCampo(descricao);
                                            } else {
                                                removerDestaque(descricao);
                                            }

                                            if (email.value === "") {
                                                destacarCampo(email);
                                            } else {
                                                removerDestaque(email);
                                            }

                                            if (celular.value === "" || celular.value.length < 15) {
                                                destacarCampo(celular);
                                            } else {
                                                removerDestaque(celular);
                                            }

                                            return isValid;  // Impede o envio se algum campo obrigatório estiver vazio
                                        }

                                        // Adicionando o evento ao botão de salvar
                                        document.getElementById('btn_salvar1').addEventListener('click', function(event) {
                                            event.preventDefault(); // Impede o envio do formulário até a verificação
                                            if (validarFormularioFornecedor()) {
                                                document.forms['frm_fornecedor'].submit(); // Submete o formulário se estiver válido
                                            }
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
    <script src="https://unpkg.com/imask"></script>
    <script src="js/mask.js"></script>
    
</body>

</html>