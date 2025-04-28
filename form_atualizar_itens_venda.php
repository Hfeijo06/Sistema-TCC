<?PHP
session_start(); // Inicia a sessão
require_once('conexao/banco.php');

$venda = $_REQUEST['venda'];

$sql = "SELECT *
        FROM tb_clientes 
        WHERE cli_codigo != 1";
$sql = mysqli_query($con, $sql) or die("Erro na sql!");

$sql2 = "SELECT p.*, e.est_qtde
         FROM tb_produtos AS p 
         INNER JOIN tb_estoque AS e ON p.pro_codigo = e.pro_codigo
         WHERE e.est_qtde > 0";
$sql2 = mysqli_query($con, $sql2) or die("Erro na sql2!");

$sql3 = "select * 
			from tb_itens_venda as i
			inner join tb_produtos as p on (i.pro_codigo = p.pro_codigo)
		    where i.ven_codigo = '$venda'";
$sql3 = mysqli_query($con, $sql3) or die("Erro na sql3!");

$sql4 = "select *
          from tb_vendas as v
		  inner join tb_clientes as c on (v.cli_codigo = c.cli_codigo)
          inner join tb_credito as cr on (v.cre_codigo = cr.cre_codigo)
		  where v.ven_codigo = '$venda'";
$sql4 = mysqli_query($con, $sql4) or die("Erro na sql4!");
$dados4 = mysqli_fetch_array($sql4);

$sql5 = "SELECT *
         FROM tb_credito
        ";
$sql5 = mysqli_query($con, $sql5) or die("Erro na sql!");

$sql6 = "SELECT c.*, v.ven_codigo
         FROM tb_credito AS c
         LEFT JOIN tb_vendas AS v ON c.cre_codigo = v.cre_codigo
         GROUP BY c.cre_codigo";
$sql6 = mysqli_query($con, $sql6) or die("Erro na sql!");



?>


<script type="text/javascript">
    function valorUnitario() {
        var preco;
        var estoque;

        preco = document.frm_itens_venda.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-preco');
        estoque = document.frm_itens_venda.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-estoque');

        document.frm_itens_venda.txt_preco.value = preco;
        document.frm_itens_venda.txt_qtde.max = estoque; // Define o máximo de quantidade com base no estoque

        // Atualiza a quantidade disponível de estoque no elemento correspondente
        document.getElementById('quantidade-estoque').textContent = estoque;
    }

    function calculo() {
        var qtde = document.frm_itens_venda.txt_qtde.value;
        var valor = document.frm_itens_venda.txt_preco.value.replace(",", ".");
        var estoque = document.frm_itens_venda.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-estoque');

        if (parseInt(qtde) > parseInt(estoque)) {
            Swal.fire({
                icon: 'warning',
                title: 'Quantidade indisponível!',
                text: 'A quantidade selecionada excede o estoque disponível! Quantidade máxima foi ajustada.',
                confirmButtonText: 'OK'
            });
            document.frm_itens_venda.txt_qtde.value = estoque; // Ajusta a quantidade ao máximo disponível
            qtde = estoque;
        }

        var total = parseFloat(qtde) * parseFloat(valor);

        if (total > 0) {
            document.frm_itens_venda.txt_total.value = parseFloat(total);
        }
    }

    function validarFormulario() {
        var qtde = document.frm_itens_venda.txt_qtde.value;
        var estoque = document.frm_itens_venda.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-estoque');

        if (parseInt(qtde) > parseInt(estoque)) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Não é possível vender uma quantidade maior do que a disponível no estoque!',
                confirmButtonText: 'OK'
            });
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
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
    <link href="./css/icon.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Adicione a biblioteca do Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Biblioteca Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .button-container {
            display: flex;
            gap: 10px;
            /* Ajuste o valor conforme necessário */
            margin-left: 15px;
        }

        .btn-secondary {
            background-color: #4CAF50;
            /* Verde claro */
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #45a049;
            /* Verde escuro para efeito hover */
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

        .button-container {
            display: flex;
            gap: 10px;
            /* Ajuste o valor conforme necessário */
            margin-left: 15px;
        }

        .btn-cancel {
            background-color: red;
            /* Verde claro */
            border: none;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c70000;
            color: white;
            /* Verde escuro para efeito hover */
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
            height: 38px;
            /* Ajuste conforme necessário */
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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Vendas</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário de Atualização da
                                    Venda</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Atualização do Itens da Venda</h4>
                            </div>

                            <div class="card-body">
                                <div class="basic-form">

                                <form name="frm_itens_venda" method="post" action="atualizar/atualizar_itens_venda.php" onsubmit="return validarFormulario2();">

                                        <div class="col-lg-12 mb-4">
                                            <label>Código</label>
                                            <input type="text" class="form-control" placeholder="" readonly name="txt_venda" id="txt_codigo" value="<?php echo $dados4['ven_codigo']; ?>">                                           
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Produto</label>
                                                <select name="txt_produto" id="txt_produto" class="form-control" onclick="valorUnitario()">
                                                <option selected>Escolha Uma Opção</option>
                                                    <?php while ($dados2 = mysqli_fetch_array($sql2)) { ?>
                                                        <option value="<?php echo $dados2['pro_codigo'] ; ?>" data-preco="<?php echo $dados2['pro_preco'] ; ?>" data-estoque="<?php echo $dados2['est_qtde']; ?>"> <?php echo $dados2['pro_descricao']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                                <small id="estoque-disponivel" class="form-text text-muted mt-2">Quantidade disponível: <span id="quantidade-estoque">-</span></small>
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
                                            <button type="button" id="btn_salvar2" class="btn btn-secondary">Pagamento</button>
                                        </div>

                                    </form>
                                    
                                    <script type="text/javascript">
                                        function validarFormulario2() {
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
                                            const produto = document.getElementById("txt_produto");
                                            const preco = document.getElementById("txt_preco");
                                            const quantidade = document.getElementById("txt_qtde");

                                            // Verificando se os campos estão preenchidos
                                            if (produto.value === "Escolha Uma Opção") {
                                                destacarCampo(produto);
                                            } else {
                                                removerDestaque(produto);
                                            }

                                            if (preco.value === "" || preco <= 0) {
                                                destacarCampo(preco);
                                            } else {
                                                removerDestaque(preco);
                                            }

                                            if (quantidade.value === "" || quantidade <= 0) {
                                                destacarCampo(quantidade);
                                            } else {
                                                removerDestaque(quantidade);
                                            }

                                            return isValid; // Impede o envio se algum campo obrigatório estiver vazio
                                        }


                                        document.getElementById('btn_salvar2').addEventListener('click', function(event) {
                                            event.preventDefault(); // Impede o comportamento padrão de envio do formulário

                                            var vendaId = document.getElementById('txt_codigo').value; // Obtém o ID da venda

                                            // Redireciona para a página de cadastro de pagamento, incluindo o ID da venda na URL
                                            window.location.href = 'form_atualizar_pagamento.php?venda_id=' + vendaId;
                                        });

                                        document.getElementById("btn_cancelar").addEventListener("click", function() {
                                            // Redireciona para um script PHP que configura a sessão
                                            window.location.href = "set_session.php"; // Substitua com a URL do seu script PHP
                                            document.forms["frm_itens_venda"].reset();
                                        });

                                    </script>

                                    
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-responsive-sm" id="itens-venda-tabela">
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
                                                <td><?php echo $dados3['itv_preco']; ?></td>
                                                <td><?php echo $dados3['itv_qtde']; ?></td>
                                                <td><?php echo $dados3['itv_total']; ?></td>
                                                <td>
                                                    <span>
                                                        <a href="deletar/delete_itens_venda2.php?itv_codigo=<?php echo $dados3['itv_codigo']; ?>&&ven_codigo=<?php echo $dados3['ven_codigo']; ?>" data-toggle="tooltip" data-placement="top" title="Close">
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
                                    <script>
                                        function validarFormulario() {
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
                                                const credito = document.getElementById('txt_credito');
                                                const tipoVenda = document.getElementById('txt_tipo_venda');
                                                const cliente = document.getElementById('txt_cliente');
                                                const dataVenda = document.getElementById('txt_data');
                                                const statusEntrega = document.getElementsByName('txt_status')[0]; // Caso a entrega esteja visível

                                                // Verificando se os campos estão preenchidos
                                                if (credito.value === "") {
                                                    destacarCampo(credito);
                                                } else {
                                                    removerDestaque(credito);
                                                }

                                                if (tipoVenda.value === "") {
                                                    destacarCampo(tipoVenda);
                                                } else {
                                                    removerDestaque(tipoVenda);
                                                }

                                                if (cliente.value === "") {
                                                    destacarCampo(cliente);
                                                } else {
                                                    removerDestaque(cliente);
                                                }

                                                if (dataVenda.value === "") {
                                                    destacarCampo(dataVenda);
                                                } else {
                                                    removerDestaque(dataVenda);
                                                }

                                                // Verificando se a entrega foi selecionada e os campos correspondentes
                                                if (tipoVenda.value === "Entrega") {
                                                    const dataEntrega = document.getElementById('data_entrega');
                                                    if (dataEntrega.value === "") {
                                                        destacarCampo(dataEntrega);
                                                    } else {
                                                        removerDestaque(dataEntrega);
                                                    }

                                                    if (statusEntrega.value === "") {
                                                        destacarCampo(statusEntrega);
                                                    } else {
                                                        removerDestaque(statusEntrega);
                                                    }
                                                }

                                                return isValid;  // Impede o envio se algum campo obrigatório estiver vazio
                                            }

                                            function toggleEntrega() {
                                                var tipoVenda = document.getElementById("txt_tipo_venda").value;
                                                var entregaSection = document.getElementById("entrega_section");

                                                if (tipoVenda === "Entrega") {
                                                    entregaSection.style.display = "block";
                                                } else {
                                                    entregaSection.style.display = "none";
                                                }
                                            }
                                    </script>

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