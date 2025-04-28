<?PHP
session_start(); // Inicia a sessão
require_once('conexao/banco.php');

$venda_id = isset($_GET['venda_id']) ? $_GET['venda_id'] : null;

$sql = "select * 
        from tb_vendas as v
        inner join tb_clientes as c on v.cli_codigo = c.cli_codigo
        where v.ven_codigo =  '$venda_id'";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;
$dados = mysqli_fetch_array($sql);

$sql2 = "SELECT SUM(itv_total) AS total_itens
         FROM tb_itens_venda
         WHERE ven_codigo = '$venda_id'";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!") ;
$dados2 = mysqli_fetch_array($sql2);
$total_itens = $dados2['total_itens'];

$sql4 = "SELECT v.ven_codigo, c.cli_nome, c.cli_codigo, p.pag_codigo
          FROM tb_vendas AS v
          INNER JOIN tb_clientes AS c ON v.cli_codigo = c.cli_codigo
          INNER JOIN tb_pagamento AS p ON v.ven_codigo = p.ven_codigo  -- Usando ven_codigo se existir na tb_pagamento
          WHERE v.ven_codigo = '$venda_id'";
$sql4 = mysqli_query($con, $sql4) or die ("Erro na sql4!");
$dados4 = mysqli_fetch_array($sql4);

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
    <!-- Adicione a biblioteca do Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Biblioteca Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">


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
    .campo-obrigatorio {
        border-color: red; /* Destaca campos não preenchidos */
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
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Formulário de Pagamento</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Pagamento</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                <form name="frm_pagamento" method="post" action="cadastro/cadastro_pagamento.php">
                                    <input type="hidden" name="txt_venda" value="<?php echo $venda_id; ?>">

                                    <div class="col-lg-12 mb-4">
                                        <div class="form-group">
                                            <label>Cliente</label>
                                            <select class="form-control" name="txt_cliente" id="txt_cliente" disabled>
                                                <option value="<?php echo $dados['ven_codigo']; ?>"> <?php echo $dados['cli_nome']; ?> </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div class="form-group">
                                            <label>Valor Total</label>
                                            <input type="number" class="form-control" name="txt_total" id="txt_total" readonly value="<?php echo $total_itens; ?>" >
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div class="form-group">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control" name="txt_descricao" id="txt_descricao" placeholder="Digite uma breve descrição">
                                        </div>
                                    </div>  
                                    
                                    <!-- Tipo de Pagamento -->
                                    <div class="col-lg-12 mb-4">
                                        <div class="form-group">
                                            <label for="tipo_pagamento">Tipo do Pagamento</label>
                                            <select class="form-control" name="txt_tipo_pag" id="tipo_pagamento" required>
                                                <option value="0" checked>Escolha Uma Opção</option>
                                                <option value="1">Dinheiro</option>
                                                <option value="2">Cartão</option>
                                                <option value="3">Crédiario</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="data_vencimento"></div>

                                    
                                    
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group" id="parcelas_div" style="display:none;">
                                            <label class="text-label" for="num_parcelas">Número de Parcelas</label>
                                            <input class="form-control" type="number" id="num_parcelas" name="txt_num_parc" required min="1" max="12" oninput="mostrarCamposParcelas()" >
                                        </div>
                                    </div>

                                    <div style="display: flex;">
                                        <span class="form-group col-lg-6" style="margin-left:-12px;" id="datas_parcelas_div"></span>
                                        <span class="form-group col-lg-6" style="margin-left:-24%;" id="valores_parcelas_div"></span>
                                    </div>
                                    <script>
                                        document.getElementById('tipo_pagamento').addEventListener('change', function() {
                                            var tipoPagamento = this.value;
                                            var dataVencimento = document.getElementById('data_vencimento');
                                            var parcelasDiv = document.getElementById('parcelas_div');
                                            var numParcelas = document.getElementById('num_parcelas');

                                            // Limpa os campos anteriores
                                            dataVencimento.innerHTML = '';
                                            parcelasDiv.style.display = 'none';
                                            document.getElementById('datas_parcelas_div').innerHTML = '';
                                            document.getElementById('valores_parcelas_div').innerHTML = '';

                                            if (tipoPagamento === '3') {
                                                // Mostra campo de parcelas e torna obrigatório
                                                parcelasDiv.style.display = 'block';
                                                numParcelas.setAttribute('required', 'required');
                                            } else {
                                                // Remove o atributo required se não for Crédiario
                                                numParcelas.removeAttribute('required');

                                                if (tipoPagamento !== '0') {
                                                    // Para Dinheiro e Cartão, adiciona um campo de data única
                                                    var divCol = document.createElement('div');
                                                    divCol.className = 'col-lg-12 mb-4'; 
                                                    var label = document.createElement('label');
                                                    label.textContent = 'Data de Vencimento';
                                                    var input = document.createElement('input');
                                                    input.type = 'date';
                                                    input.name = 'txt_data_vencimento';
                                                    input.className = 'form-control';
                                                    input.required = true;

                                                    divCol.appendChild(label);
                                                    divCol.appendChild(input);
                                                    dataVencimento.appendChild(divCol);
                                                }
                                            }
                                        });

                                        function mostrarCamposParcelas() {
                                            var numParcelas = document.getElementById('num_parcelas').value;
                                            var totalValor = parseFloat(document.getElementById('txt_total').value) || 0;
                                            var valorParcela = (totalValor / numParcelas).toFixed(2);
                                            var datasParcelasDiv = document.getElementById('datas_parcelas_div');
                                            var valoresParcelasDiv = document.getElementById('valores_parcelas_div');

                                            // Limpa os campos anteriores
                                            datasParcelasDiv.innerHTML = '';
                                            valoresParcelasDiv.innerHTML = '';

                                            for (var i = 1; i <= numParcelas; i++) {
                                                // Campo de data
                                                var divColData = document.createElement('div');
                                                divColData.className = 'col-lg-6 mb-2'; 
                                                var labelData = document.createElement('label');
                                                labelData.textContent = `Data de Vencimento da Parcela ${i}`;
                                                var inputData = document.createElement('input');
                                                inputData.type = 'date';
                                                inputData.name = 'txt_data_vencimento[]';
                                                inputData.className = 'form-control';
                                                inputData.required = true;

                                                divColData.appendChild(labelData);
                                                divColData.appendChild(inputData);
                                                datasParcelasDiv.appendChild(divColData);

                                                // Campo de valor
                                                var divColValor = document.createElement('div');
                                                divColValor.className = 'col-lg-6 mb-2'; 
                                                var labelValor = document.createElement('label');
                                                labelValor.textContent = `Valor da Parcela ${i}`;
                                                var inputValor = document.createElement('input');
                                                inputValor.type = 'number';
                                                inputValor.name = 'txt_valor[]';
                                                inputValor.className = 'form-control';
                                                inputValor.value = valorParcela;
                                                inputValor.required = true;
                                                inputValor.step = '0.01';

                                                divColValor.appendChild(labelValor);
                                                divColValor.appendChild(inputValor);
                                                valoresParcelasDiv.appendChild(divColValor);
                                            }
                                        }

                                        function verificarTotal() {
                                            const valores = document.querySelectorAll('input[name="txt_valor[]"]');
                                            const totalInput = parseFloat(document.getElementById('txt_total').value) || 0;
                                            const somaParcelas = Array.from(valores).reduce((acc, input) => acc + (parseFloat(input.value) || 0), 0);

                                            const margemErro = 0.01; // 1 centavo de margem para precisão
                                            if (Math.abs(somaParcelas - totalInput) > margemErro) {
                                                swal({
                                                    title: "Erro!",
                                                    text: "A soma dos valores das parcelas deve ser igual ao valor total.",
                                                    icon: "error",
                                                    button: "OK",
                                                });
                                                return false; // Falha na verificação
                                            }
                                            return true; // Verificação bem-sucedida
                                        }

                                        document.getElementById('btn_salvar1').addEventListener('click', function(event) {
                                            var tipoPagamento = document.getElementById('tipo_pagamento').value;
                                            if (tipoPagamento === '3' && !verificarTotal()) {
                                                event.preventDefault(); // Evita o envio se a validação falhar
                                                return; // Sai da função
                                            }

                                            // Se a validação passar, submete o formulário
                                            document.forms['frm_pagamento'].submit();
                                        });
                                    </script>




                                    <div class="button-container">
                                        <button type="submit" id="btn_salvar1" class="btn btn-primary">Finalizar</button>
                                    </div>

                                </form>
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