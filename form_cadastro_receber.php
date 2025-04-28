<?php
require_once('conexao/banco.php');

$sql = "select * from tb_parcelas";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;
$dados = mysqli_fetch_array($sql);

$sql2 = "SELECT *
         FROM tb_credito
        ";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql!") ;

$sql6 = "SELECT *
         FROM tb_credito
        ";
$sql6 = mysqli_query($con, $sql6) or die ("Erro na sql!") ;

?>  
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistema - Neo Enigma </title>
    <link href="./css/icon.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Favicon icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    /* Estilo para o botão "Adicionar Despesa" */
    .btn-adicionar-despesa {
        white-space: nowrap;
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-adicionar-despesa:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    /* Estilo para o foco do botão */
    .btn-adicionar-despesa:focus,
    .btn-adicionar-despesa.focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
        background-color: #5a6268;
        border-color: #545b62;
    }

    /* Estilo para quando o botão está sendo clicado (estado ativo) */
    .btn-adicionar-despesa:active,
    .btn-adicionar-despesa.active {
        background-color: #545b62 !important;
        border-color: #4e555b !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
    }

    /* Remover outline padrão do navegador */
    .btn-adicionar-despesa::-moz-focus-inner {
        border: 0;
    }

    /* Estilos para o modal */
    .modal-despesa .modal-content {
        border-radius: 0.3rem;
    }

    .modal-despesa .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-despesa .modal-title {
        color: #495057;
    }

    .modal-despesa .modal-body {
        padding: 20px;
    }

    .modal-despesa .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    .modal-despesa .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .modal-despesa .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    /* Estilo para garantir que o select e o botão tenham a mesma altura */
    .form-group .d-flex {
        align-items: stretch;
    }

    .form-group .d-flex select,
    .form-group .d-flex .btn-adicionar-despesa {
        height: 38px; /* Ajuste conforme necessário */
    }

    /* Estilos para os botões do modal */
    .modal-despesa .btn-modal {
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
    }

    .modal-despesa .btn-modal-cancel {
        color: #fff;
        background-color: red;
        border-color: red;
    }

    .modal-despesa .btn-modal-cancel:hover {
        color: #fff;
        background-color: #c70000;
        border-color: #c70000;
    }

    /* Estilos para o foco dos botões do modal */
    .modal-despesa .btn-modal:focus,
    .modal-despesa .btn-modal.focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
    }

    .modal-despesa .btn-modal-save:focus,
    .modal-despesa .btn-modal-save.focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
    }

    /* Estilos para quando os botões do modal estão sendo clicados (estado ativo) */
    .modal-despesa .btn-modal:active,
    .modal-despesa .btn-modal.active {
        transform: translateY(1px);
    }

    .modal-despesa .btn-modal-cancel:active,
    .modal-despesa .btn-modal-cancel.active {
        background-color: #545b62 !important;
        border-color: #4e555b !important;
    }

    /* Remover outline padrão do navegador */
    .modal-despesa .btn-modal::-moz-focus-inner {
        border: 0;
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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Contas</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário Contas à Receber</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Contas à Receber</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                    <form name="frm_receber" method="post" action="cadastro/cadastro_conta_receber.php" id="frm_receber">
                                        <div class="col-lg-12 mb-4">
                                            <input type="text" hidden class="form-control" name="txt_parcela" id="txt_parcela" value="<?php echo $dados['parc_codigo']; ?>">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo de Crédito</label>
                                                <div class="d-flex">
                                                    <select class="form-control flex-grow-1" name="txt_credito" id="txt_credito">
                                                        <option selected disabled>Escolha Uma Opção</option>
                                                        <?php while ($dados2 = mysqli_fetch_array($sql2)) { ?>
                                                            <option value="<?php echo $dados2['cre_codigo']; ?>"><?php echo $dados2['cre_nome']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <button type="button" class="btn btn-secondary btn-adicionar-despesa ml-2" data-toggle="modal" data-target="#modalCadastrarDespesa">Adicionar Despesa</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Valor</label>
                                            <input type="number" class="form-control" name="txt_valor" id="txt_valor" placeholder="Digite o Valor da Conta à Receber">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Data de Vencimento</label>
                                            <input type="date" class="form-control" name="txt_data" id="txt_data" placeholder="">
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control" name="txt_status" id="txt_status">
                                                    <option checked>Escolha Uma Opção</option>
                                                    <option value="Pendente"> Pendente </option>
                                                    <option value="Pago"> Pago </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <button type="button" id="btn_cancelar" class="btn btn-cancel">Cencelar</button>
                                            <button type="submit" id="btn_salvar1" class="btn btn-primary">Cadastrar</button>
                                        </div>
                                        <script>
                                            document.getElementById("btn_cancelar").addEventListener("click", function() {
                                                // Redireciona para um script PHP que configura a sessão
                                                window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                                document.forms["frm_receber"].reset();
                                            });
                                        </script>

                                    </form>
                                    <script>
                                        document.getElementById("btn_salvar1").addEventListener("click", function(event) {
                                        event.preventDefault(); // Impede o envio automático do formulário

                                        // Obtém os valores dos campos
                                        var credito = document.getElementById("txt_credito");
                                        var valor = document.getElementById("txt_valor");
                                        var data = document.getElementById("txt_data");
                                        var status = document.getElementById("txt_status");

                                        var valid = true; // Para verificar se o formulário é válido

                                        // Função para adicionar borda vermelha e focar no campo
                                        function marcarErro(campo) {
                                            campo.style.borderColor = "red";
                                            if (valid) {
                                                campo.focus(); // Foca no primeiro campo inválido
                                                valid = false; // Define como inválido
                                            }
                                        }

                                        // Verifica se os campos obrigatórios estão preenchidos
                                        if (credito.value === "" || credito.value === "Escolha Uma Opção") {
                                            marcarErro(credito);
                                        } else {
                                            credito.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        if (valor.value === "" || valor.value <= 0) {
                                            marcarErro(valor);
                                        } else {
                                            valor.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        if (data.value === "") {
                                            marcarErro(data);
                                        } else {
                                            data.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        if (status.value === "" || status.value === "Escolha Uma Opção") {
                                            marcarErro(status);
                                        } else {
                                            status.style.border = ''; // Remove borda vermelha se estiver correto
                                        }

                                        // Se o formulário estiver válido, envia o formulário
                                        if (valid) {
                                            document.getElementById("frm_receber").submit();
                                        }
                                    });
                                        document.getElementById("btn_cancelar").addEventListener("click", function() {
                                            // Redireciona para um script PHP que configura a sessão
                                            window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                            document.forms["frm_receber"].reset();
                                        });

                                    </script>
                                    <!-- Modal para Cadastrar Despesa (Fora do Formulário) -->
                                    <div class="modal fade modal-despesa" id="modalCadastrarDespesa" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarDespesaLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalCadastrarDespesaLabel">Cadastrar Novo Crédito</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formCadastrarDespesa">
                                                        <div class="form-group">
                                                            <label for="nome_despesa">Nome do Crédito</label>
                                                            <input type="text" class="form-control" id="nome_credito" name="nome_credito" placeholder="Digite o nome da despesa" required>
                                                        </div>
                                                    </form>
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Nome</th>
                                                                <th>Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php while ($dados6 = mysqli_fetch_array($sql6)) { ?>
                                                            <tr>
                                                                <th><?php echo $dados6['cre_codigo']; ?></th>
                                                                <td><?php echo $dados6['cre_nome']; ?></td>
                                                                <td>
                                                                    <span>
                                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados6['cre_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                                        </a>
                                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                                        <script>
                                                                            function confirmDelete(cre_codigo) {
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
                                                                                        window.location.href = 'deletar/delete_credito2.php?cre_codigo=' + cre_codigo;
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
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-modal btn-modal-cancel" data-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-modal btn-primary" id="btnCadastrarDespesa">Salvar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <script>
                                        
                                        $(document).ready(function() {
                                            $('#btnCadastrarDespesa').click(function(e) {
                                                e.preventDefault(); // Impede o envio do formulário principal

                                                var nomecredito = $('#nome_credito').val();

                                                if (nomecredito.trim() === "") {
                                                    Swal.fire('Erro', 'Por favor, digite o nome do crédito.', 'error');
                                                    return;
                                                }

                                                $.ajax({
                                                    url: 'cadastrar_credito.php',
                                                    type: 'POST',
                                                    data: { nome_credito: nomecredito },
                                                    success: function(response) {
                                                        if (response.trim() === 'success') {
                                                            Swal.fire('Sucesso', 'Crédito cadastrado com sucesso!', 'success').then(function() {
                                                                $('#modalCadastrarDespesa').modal('hide');
                                                                $('#nome_credito').val('');
                                                                location.reload();
                                                            });
                                                        } else {
                                                            Swal.fire('Erro', 'Não foi possível cadastrar a despesa: ' + response, 'error');
                                                        }
                                                    },
                                                    error: function() {
                                                        Swal.fire('Erro', 'Ocorreu um erro na requisição.', 'error');
                                                    }
                                                });
                                            });
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
    
</body>

</html>