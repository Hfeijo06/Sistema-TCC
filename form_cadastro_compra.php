<?PHP
session_start(); // Inicia a sessão
require_once('conexao/banco.php');

$compra = isset($_REQUEST['compra']) ? $_REQUEST['compra'] : '0';

$sql = "select * from tb_fornecedores where for_codigo != 7";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;

$sql2 = "SELECT p.*, e.est_qtde
         FROM tb_produtos AS p 
         INNER JOIN tb_estoque AS e ON p.pro_codigo = e.pro_codigo
        ";  
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!");
    
$sql3 = "select * 
			from tb_itens_compra as i
			inner join tb_produtos as p on (i.pro_codigo = p.pro_codigo)
		    where i.com_codigo = '$compra'";
$sql3 = mysqli_query($con, $sql3) or die ("Erro na sql3!");

$sql4 = "select c.com_codigo, c.com_data, c.com_tipo_pagamento, f.for_descricao, f.for_codigo, d.des_nome, d.des_codigo
          from tb_compra as c
		  inner join tb_fornecedores as f on (f.for_codigo = c.for_codigo)
          inner join tb_despesas as d on (c.des_codigo = d.des_codigo)
		  where c.com_codigo = '$compra' and f.for_codigo != 7";
$sql4 = mysqli_query($con, $sql4) or die ("Erro na sql4!");
$dados4 = mysqli_fetch_array($sql4);

$sql5 = "SELECT *
         FROM tb_despesas
        ";
$sql5 = mysqli_query($con, $sql5) or die ("Erro na sql!") ;

$sql6 = "SELECT *
         FROM tb_despesas
        ";
$sql6 = mysqli_query($con, $sql6) or die ("Erro na sql!") ;

?>


<script type="text/javascript">
function valorUnitario() {
    var preco;

    preco = document.frm_itens_compra.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-preco');
    
    document.frm_itens_compra.txt_preco.value = preco;
}

function calculo() {
    var qtde = document.frm_itens_compra.txt_qtde.value;
    var valor = document.frm_itens_compra.txt_preco.value.replace(",", ".");
    var estoque = document.frm_itens_compra.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-estoque');


    var total = parseFloat(qtde) * parseFloat(valor);

    if (total > 0) {
        document.frm_itens_compra.txt_total.value = parseFloat(total);
    }
}   

</script>



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
    <link href="./css/table.css" rel="stylesheet">
    <link href="./css/icon.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Adicione a biblioteca do Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Biblioteca Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
    .button-container {
        display: flex;
        gap: 10px; /* Ajuste o valor conforme necessário */
        margin-left: 15px;
    }
    .btn-secondary {
        background-color: #4CAF50; /* Verde claro */
        border: none;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #45a049; /* Verde escuro para efeito hover */
    }
    .btn-cancel {
        background-color: red; 
        border: none;
        color: white;
    }

    .btn-cancel:hover {
        background-color: #c70000;
        color: white; 
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
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Compras</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário de Compras</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Compras</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                <?PHP if($compra == 0) { ?>
                                    <form name="frm_compra" method="post" action="cadastro/cadastro_compra.php">
                                        
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo do Débito</label>
                                                <div class="d-flex">
                                                    <select class="form-control flex-grow-1" name="txt_despesa" id="txt_despesa" required>
                                                        <option selected disabled>Escolha Uma Opção</option>
                                                        <?php while ($dados5 = mysqli_fetch_array($sql5)) { ?>
                                                            <option value="<?php echo $dados5['des_codigo']; ?>"><?php echo $dados5['des_nome']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <button type="button" class="btn btn-secondary btn-adicionar-despesa ml-2" data-toggle="modal" data-target="#modalCadastrarDespesa">Adicionar Despesa</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Fornecedores</label>
                                                <select class="form-control" name="txt_fornecedor" id="txt_fornecedor" required>
                                                    <option checked>Escolha Uma Opção</option>
                                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                                    <option value="<?php echo $dados['for_codigo']; ?>"> <?php echo $dados['for_descricao']; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label class="text-label">Data da compra</label>
                                                <input type="date" name="txt_data" id="txt_data" class="form-control" placeholder="Digite a Data da compra" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo do Pagamento</label>
                                                <select class="form-control" name="txt_tipo_pag" id="txt_tipo_pag" required>
                                                    <option checked>Escolha Uma Opção</option>
                                                    <option value="1">Dinheiro</option>
                                                    <option value="2">Cartão</option>
                                                    <option value="3">PIX</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="button-container">
                                            <button type="button" id="btn_cancelar" class="btn btn-cancel">Cencelar</button>
                                            <button type="submit" id="btn_salvar1" class="btn btn-primary">Cadastrar</button>
                                        </div>  
                                        <script>
                                            document.getElementById("btn_cancelar").addEventListener("click", function() {
                                                // Redireciona para um script PHP que configura a sessão
                                                window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                                document.forms["frm_login"].reset();
                                            });
                                        </script>

                                    </form>
                                    <script>
                                        function validarFormularioCompra() {
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
                                            const despesa = document.getElementById('txt_despesa');
                                            const fornecedor = document.getElementById('txt_fornecedor');
                                            const dataCompra = document.getElementById('txt_data');
                                            const tipoPagamento = document.getElementById('txt_tipo_pag');

                                            // Verificando se os campos estão preenchidos
                                            if (despesa.value === "Escolha Uma Opção") {
                                                destacarCampo(despesa);
                                            } else {
                                                removerDestaque(despesa);
                                            }

                                            if (fornecedor.value === "Escolha Uma Opção") {
                                                destacarCampo(fornecedor);
                                            } else {
                                                removerDestaque(fornecedor);
                                            }

                                            if (dataCompra.value === "") {
                                                destacarCampo(dataCompra);
                                            } else {
                                                removerDestaque(dataCompra);
                                            }

                                            if (tipoPagamento.value === "Escolha Uma Opção") {
                                                destacarCampo(tipoPagamento);
                                            } else {
                                                removerDestaque(tipoPagamento);
                                            }

                                            return isValid; // Impede o envio se algum campo obrigatório estiver vazio
                                        }

                                        // Adicionando o evento ao botão de salvar
                                        document.getElementById('btn_salvar1').addEventListener('click', function(event) {
                                            event.preventDefault(); // Impede o envio do formulário até a verificação
                                            if (validarFormularioCompra()) {
                                                document.forms['frm_compra'].submit(); // Submete o formulário se estiver válido
                                            }
                                        });

                                    </script>
                                    <!-- Modal para Cadastrar Despesa (Fora do Formulário) -->
                                    <div class="modal fade modal-despesa" id="modalCadastrarDespesa" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarDespesaLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalCadastrarDespesaLabel">Cadastrar Nova Despesa</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formCadastrarDespesa">
                                                        <div class="form-group">
                                                            <label for="nome_despesa">Nome da Despesa</label>
                                                            <input type="text" class="form-control" id="nome_despesa" name="nome_despesa" placeholder="Digite o nome da despesa" required>
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
                                                                <th><?php echo $dados6['des_codigo']; ?></th>
                                                                <td><?php echo $dados6['des_nome']; ?></td>
                                                                <td>
                                                                    <span>
                                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados6['des_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                                        </a>
                                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                                        <script>
                                                                            function confirmDelete(des_codigo) {
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
                                                                                        window.location.href = 'deletar/delete_despesa.php?des_codigo=' + des_codigo;
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

                                                var nomeDespesa = $('#nome_despesa').val();

                                                if (nomeDespesa.trim() === "") {
                                                    Swal.fire('Erro', 'Por favor, digite o nome da despesa.', 'error');
                                                    return;
                                                }

                                                $.ajax({
                                                    url: 'cadastrar_despesa.php',
                                                    type: 'POST',
                                                    data: { nome_despesa: nomeDespesa },
                                                    success: function(response) {
                                                        if (response.trim() === 'success') {
                                                            Swal.fire('Sucesso', 'Despesa cadastrada com sucesso!', 'success').then(function() {
                                                                $('#modalCadastrarDespesa').modal('hide');
                                                                $('#nome_despesa').val('');
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
                                    <script>
                                        document.getElementById("btn_cancelar").addEventListener("click", function() {
                                            // Redireciona para um script PHP que configura a sessão
                                            window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                            document.forms["frm_compra"].reset();
                                        });
                                    </script>

                                <?PHP } else {  ?>

                                    <form name="frm_compra" method="post" action="cadastro/cadastro_compra.php">
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo do Débito</label>
                                                <select class="form-control" name="txt_despesa" id="txt_despesa" disabled>
                                                    <option value="<?php echo $dados4['des_codigo']; ?>"> <?php echo $dados4['des_nome']; ?> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Fornecedores</label>
                                                <select class="form-control" name="txt_fornecedor" id="txt_fornecedor" disabled>
                                                    <option value="<?php echo $dados4['for_codigo']; ?>"> <?php echo $dados4['for_descricao']; ?> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label class="text-label">Data da compra</label>
                                                <input type="date" name="txt_data" id="txt_data" class="form-control" value="<?php echo $dados4['com_data']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo do Pagamento</label>
                                                <select class="form-control" name="txt_tipo_pag" id="txt_tipo_pag" disabled>
                                                    <option checked>Escolha Uma Opção</option>
                                                    <option <?php if ($dados4['com_tipo_pagamento'] == "1") { echo "selected";} ?> > Dinheiro </option>
                                                    <option <?php if ($dados4['com_tipo_pagamento'] == "2") { echo "selected";} ?> > Cartão </option>
                                                    <option <?php if ($dados4['com_tipo_pagamento'] == "3") { echo "selected";} ?> > PIX </option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <form name="frm_itens_compra" method="post" action="cadastro/cadastro_itens_compra.php">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <input type="hidden" class="form-control" name="txt_compra" id="txt_compra" value="<?php echo $compra; ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Produto</label>
                                                <select name="txt_produto" id="txt_produto" class="form-control" onclick="valorUnitario()">
                                                <option selected>Escolha Uma Opção</option>
                                                    <?php while ($dados2 = mysqli_fetch_array($sql2)) { ?>
                                                        <option value="<?php echo $dados2['pro_codigo'] ; ?>" > <?php echo $dados2['pro_descricao']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                        </div>
                                        
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group row">
                                                <label>Valor Unitário</label>
                                                <input type="number" class="form-control" name="txt_preco" id="txt_preco" placeholder="Digite o Valor do Produto">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group row">
                                                <label>Qtde</label>
                                                <input type="number" class="form-control" name="txt_qtde" id="txt_qtde" placeholder="Digite a Quantidade do Produto" oninput="calculo()">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group row">
                                                <label>Total</label>
                                                <input type="number" class="form-control" name="txt_total" id="txt_total" placeholder="" readonly="true">
                                            </div>
                                        </div>

                                        <div class="button-container" style="margin-left: 0px;">
                                            <button type="submit" id="btn_salvar1" class="btn btn-primary">Cadastrar</button>
                                            <button type="button" id="btn_salvar2" class="btn btn-secondary">Finalizar</button>
                                        </div>

                                    </form>

                                    <script type="text/javascript">
                                        function validarFormularioItensCompra() {
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
                                            const produto = document.getElementById('txt_produto');
                                            const preco = document.getElementById('txt_preco');
                                            const quantidade = document.getElementById('txt_qtde');
                                            const total = document.getElementById('txt_total');

                                            // Verificando se os campos estão preenchidos
                                            if (produto.value === "Escolha Uma Opção") {
                                                destacarCampo(produto);
                                            } else {
                                                removerDestaque(produto);
                                            }

                                            if (preco.value === "" || preco.value <= 0) {
                                                destacarCampo(preco);
                                            } else {
                                                removerDestaque(preco);
                                            }

                                            if (quantidade.value === "" || quantidade.value <= 0) {
                                                destacarCampo(quantidade);
                                            } else {
                                                removerDestaque(quantidade);
                                            }

                                            if (total.value === "" || total.value <= 0) {
                                                destacarCampo(total);
                                            } else {
                                                removerDestaque(total);
                                            }

                                            return isValid; // Impede o envio se algum campo obrigatório estiver vazio
                                        }
                                        
                                        // Adicionando o evento ao botão de salvar
                                        document.getElementById('btn_salvar1').addEventListener('click', function(event) {
                                            event.preventDefault(); // Impede o envio do formulário até a verificação
                                            if (validarFormularioItensCompra()) {
                                                document.forms['frm_itens_compra'].submit(); // Submete o formulário se estiver válido
                                            }
                                        });

                                        document.getElementById('btn_salvar2').addEventListener('click', function(event) {
                                            event.preventDefault(); // Impede o comportamento padrão de envio do formulário
                                            window.location.href = 'consulta_pagar.php'; // Redireciona para a página desejada
                                        });
                                        
                                        document.getElementById("btn_cancelar").addEventListener("click", function() {
                                            // Redireciona para um script PHP que configura a sessão
                                            window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                            document.forms["frm_compra"].reset();
                                        });
                                    </script>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Produto</th>
                                                <th>Valor</th>
                                                <th>Qtde</th>
                                                <th>Total</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados3 = mysqli_fetch_array($sql3)) { ?>
                                            <tr>
                                                <th><?php echo $dados3['pro_codigo']; ?></th>
                                                <td><?php echo $dados3['pro_descricao']; ?></td>
                                                <td><?php echo $dados3['itc_preco']; ?></td>
                                                <td><?php echo $dados3['itc_qtde']; ?></td>
                                                <td><?php echo $dados3['itc_total']; ?></td>
                                                <td>
                                                    <span>
                                                        <a href="deletar/delete_itens_compra.php?itc_codigo=<?php echo $dados3['itc_codigo']; ?>&&com_codigo=<?php echo $dados3['com_codigo']; ?>" data-toggle="tooltip" data-placement="top" title="Close">
                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                        </a>
                                                    </span>
                                                </td>
                                                </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?PHP }  ?>

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