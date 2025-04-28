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

        <script>
    
        function limpa_formulário_cep() {
                //Limpa valores do formulário de cep.
                document.getElementById('txt_rua').value=("");
                document.getElementById('txt_bairro').value=("");
                document.getElementById('txt_cidade').value=("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('txt_rua').value=(conteudo.logradouro);
                document.getElementById('txt_bairro').value=(conteudo.bairro);
                document.getElementById('txt_cidade').value=(conteudo.localidade);
            } //end if.
            else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }
            
        function pesquisacep(valor) {

            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if(validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('txt_rua').value="...";
                    document.getElementById('txt_bairro').value="...";
                    document.getElementById('txt_cidade').value="...";

                    //Cria um elemento javascript.
                    var script = document.createElement('script');

                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        };

        </script>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Clientes</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário de Clientes</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Clientes</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                    <form name="frm_clientes" method="post" action="cadastro/cadastro_cliente.php" onsubmit="return validarFormulario()">

                                        <div class="col-lg-12 mb-4">
                                            <label>Nome</label>
                                            <input type="text" class="form-control" name="txt_nome" id="txt_nome" placeholder="Digite o Nome do Cliente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>RG</label>
                                            <input onkeydown="const mask = IMask(rg, maskOptions);" maxlength="14" type="text" class="form-control" name="txt_rg" id="txt_rg" placeholder="Digite o RG do Cliente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>CPF</label>
                                            <input onkeypress="mascara(this, cpf)" maxlength="14" type="text" class="form-control" name="txt_cpf" id="txt_cpf" placeholder="Digite o CPF do Cliente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>CEP</label>
                                            <input onblur="pesquisacep(this.value)" onkeypress="mascara(this, cep)" maxlength="9" type="text" class="form-control" name="txt_cep" id="txt_cep" placeholder="Digite o CEP do Cliente">
                                        </div>  

                                        <div class="col-lg-12 mb-4">
                                            <label>Rua</label>
                                            <input type="text" class="form-control" name="txt_rua" id="txt_rua" placeholder="Digite a Rua do Cliente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Bairro</label>
                                            <input type="text" class="form-control" name="txt_bairro" id="txt_bairro" placeholder="Digite o Bairro do Cliente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Número</label>
                                            <input type="text" class="form-control" name="txt_numero" id="txt_numero" placeholder="Digite o Número da casa do Cliente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Cidade</label>
                                            <input type="text" class="form-control" name="txt_cidade" id="txt_cidade" placeholder="Digite a Cidade do CLiente">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Complemento</label>
                                            <input type="text" class="form-control" name="txt_complemento" id="txt_complemento" placeholder="Digite o Complemento">
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

                                    </form>
                                    <script>
                                        document.getElementById("btn_cancelar").addEventListener("click", function() {
                                            // Redireciona para um script PHP que configura a sessão
                                            window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                            document.forms["frm_cliente"].reset();
                                        });
                                        function validarFormulario() {
                                        let isValid = true;

                                        // Função auxiliar para destacar campos vazios
                                        function destacarCampo(campo) {
                                            campo.style.borderColor = "red";
                                            campo.focus();
                                            isValid = false;
                                        }

                                        // Função auxiliar para remover o destaque quando preenchido
                                        function removerDestaque(campo) {
                                            campo.style.borderColor = "";
                                        }

                                        // Campos obrigatórios
                                        const nome = document.getElementById('txt_nome');
                                        const rg = document.getElementById('txt_rg');
                                        const cpf = document.getElementById('txt_cpf');
                                        const cep = document.getElementById('txt_cep');
                                        const rua = document.getElementById('txt_rua');
                                        const bairro = document.getElementById('txt_bairro');
                                        const numero = document.getElementById('txt_numero');
                                        const cidade = document.getElementById('txt_cidade');
                                        const celular = document.getElementById('txt_celular');

                                        // Verificação se os campos obrigatórios estão preenchidos
                                        if (nome.value === "") {
                                            destacarCampo(nome);
                                        } else {
                                            removerDestaque(nome);
                                        }

                                        if (rg.value === "") {
                                            destacarCampo(rg);
                                        } else {
                                            removerDestaque(rg);
                                        }

                                        if (cpf.value === "") {
                                            destacarCampo(cpf);
                                        } else {
                                            removerDestaque(cpf);
                                        }

                                        if (cep.value === "") {
                                            destacarCampo(cep);
                                        } else {
                                            removerDestaque(cep);
                                        }

                                        if (rua.value === "") {
                                            destacarCampo(rua);
                                        } else {
                                            removerDestaque(rua);
                                        }

                                        if (bairro.value === "") {
                                            destacarCampo(bairro);
                                        } else {
                                            removerDestaque(bairro);
                                        }

                                        if (numero.value === "") {
                                            destacarCampo(numero);
                                        } else {
                                            removerDestaque(numero);
                                        }

                                        if (cidade.value === "") {
                                            destacarCampo(cidade);
                                        } else {
                                            removerDestaque(cidade);
                                        }

                                        if (celular.value === "") {
                                            destacarCampo(celular);
                                        } else {
                                            removerDestaque(celular);
                                        }

                                        return isValid;  // Retorna false se algum campo estiver vazio, impedindo o envio do formulário
                                    }
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