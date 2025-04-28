<?PHP

require_once('conexao/banco.php');

$cliente = isset($_REQUEST['cli_nome']) ? $_REQUEST['cli_nome'] : '';
$cpf = isset($_REQUEST['cli_cpf']) ? $_REQUEST['cli_cpf'] : '';
$cep = isset($_REQUEST['cli_cep']) ? $_REQUEST['cli_cep'] : '';
$rua = isset($_REQUEST['cli_rua']) ? $_REQUEST['cli_rua'] : '';
$cidade = isset($_REQUEST['cli_cidade']) ? $_REQUEST['cli_cidade'] : '';
$celular = isset($_REQUEST['cli_celular']) ? $_REQUEST['cli_celular'] : '';
$bairro = isset($_REQUEST['cli_bairro']) ? $_REQUEST['cli_bairro'] : '';
$numero = isset($_REQUEST['cli_numero']) ? $_REQUEST['cli_numero'] : '';
$sql = "select * from tb_clientes where 
        cli_nome like '%".$cliente."%' AND
        cli_cpf like '%".$cpf."%' AND
        cli_cep like '%".$cep."%' AND   
        cli_rua like '%".$rua."%' AND
        cli_cidade like '%".$cidade."%' AND
        cli_celular like '%".$celular."%' AND
        cli_bairro like '%".$bairro."%' AND
        cli_numero like  '%".$numero."%' AND
        cli_codigo != 1
        ORDER BY cli_codigo DESC";
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
    <link href="./css/pesquisa.css" rel="stylesheet">
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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Clientes</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Clientes</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h4 class="card-header">
                                Consulta de Clientes
                                <a href="form_cadastro_cliente.php">
                                    <button name="btn_salvar" class="btn-cadastro mb-1 float-right">+</button>
                                </a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="clientes">
                                        <input type="hidden" name="cli_nome" value="<?php echo htmlspecialchars($cliente); ?>">
                                        <input type="hidden" name="cli_cpf" value="<?php echo htmlspecialchars($cpf); ?>">
                                        <input type="hidden" name="cli_cep" value="<?php echo htmlspecialchars($cep); ?>">
                                        <input type="hidden" name="cli_rua" value="<?php echo htmlspecialchars($rua); ?>">
                                        <input type="hidden" name="cli_cidade" value="<?php echo htmlspecialchars($cidade); ?>">
                                        <input type="hidden" name="cli_celular" value="<?php echo htmlspecialchars($celular); ?>">
                                        <input type="hidden" name="cli_bairro" value="<?php echo htmlspecialchars($bairro); ?>">
                                        <input type="hidden" name="cli_numero" value="<?php echo htmlspecialchars($numero); ?>">

                                        <div class="export-buttons mb-3">
                                            <button type="button" id="exportPdf" class="btn btn-danger">Exportar para PDF</button>
                                            <button type="button" id="exportExcel" class="btn btn-success">Exportar para Excel</button>
                                        </div>
                                    </form>

                                    <script>
                                        document.getElementById('exportPdf').addEventListener('click', function () {
                                            // Mostra o SweetAlert de confirmação para PDF
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
                                                    // Se o usuário confirmar, envia o formulário para PDF
                                                    document.forms['frm_exportar'].action = "export/export_pdf.php";
                                                    document.forms['frm_exportar'].submit();
                                                }
                                            });
                                        });

                                        document.getElementById('exportExcel').addEventListener('click', function () {
                                            // Mostra o SweetAlert de confirmação para Excel
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
                                                    // Se o usuário confirmar, envia o formulário para Excel
                                                    document.forms['frm_exportar'].action = "export/export_excel.php";
                                                    document.forms['frm_exportar'].submit();
                                                }
                                            });
                                        });
                                    </script>

                                <form name="frm_consulta" method="GET" action="" class="filter-form">
                                        <div class="filter-form-content">
                                            <div class="filter-form-group">
                                                <input name="cli_nome" type="text" id="cliente" class="form-control" placeholder="Nome do cliente">
                                                <input name="cli_cpf" onkeypress="mascara(this, cpf)" maxlength="14" type="text" id="CNPJ" class="form-control" placeholder="CPF">
                                                <input name="cli_celular" onkeypress="mascara(this, celular)" maxlength="15" type="text" id="celular" class="form-control" placeholder="Celular">
                                                <script>
                                                    // Aplica a função de máscara diretamente no evento 'input'
                                                    document.getElementById("celular").addEventListener("input", function () {
                                                        this.value = celular(this.value);  // Chama a função celular a cada input
                                                    });

                                                    // Função de máscara para celular
                                                    function celular(v) {
                                                        v = v.replace(/\D/g, ""); // Remove tudo que não é dígito
                                                        v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); // Parênteses nos dois primeiros dígitos
                                                        v = v.replace(/(\d{5})(\d)/, "$1-$2"); // Hífen entre o quinto e sexto dígitos
                                                        return v;
                                                    }   
                                                </script> 
                                                <input name="cli_cep" onkeypress="mascara(this, cep)" maxlength="9" type="text" id="descricao" class="form-control" placeholder="CEP">
                                            </div>
                                        </div>
                                            <div class="filter-form-group">
                                                <input name="cli_rua" type="text" id="rua" class="form-control" placeholder="Rua">
                                                <input name="cli_numero" type="text" id="numero" class="form-control" placeholder="Número da casa">
                                                <input name="cli_bairro" type="text" id="bairro" class="form-control" placeholder="Bairro">
                                                <input name="cli_cidade" type="text" id="cidade" class="form-control" placeholder="Cidade">    
                                            </div>
                                        <div class="filter-form-buttons">
                                            <button id="botaoS" type="submit" class="btn-filter btn-filter-primary">Filtrar</button>
                                            <button id="botaoLimpar" type="submit" class="btn-filter btn-filter-secondary" onclick="limparPesquisa()">Limpar Filtro</button>
                                        </div>
                                    <script>
                                        function limparPesquisa() {
                                            document.querySelector("form[name='frm_consulta']").reset();  // Reseta os campos do formulário
                                        }
                                    </script>
                                </form>

                                <!-- Tabela de clientes -->
                                <table class="table table-responsive-sm cliente-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>CPF</th>
                                            <th>CEP</th>
                                            <th>Rua</th>
                                            <th>Bairro</th>
                                            <th>Número</th>
                                            <th>Cidade</th>
                                            <th>Celular</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                        <tr>
                                            <th><?php echo $dados['cli_codigo']; ?></th>
                                            <td><?php echo $dados['cli_nome']; ?></td>
                                            <td><?php echo $dados['cli_cpf']; ?></td>
                                            <td><?php echo $dados['cli_cep']; ?></td>
                                            <td><?php echo $dados['cli_rua']; ?></td>
                                            <td><?php echo $dados['cli_bairro']; ?></td>
                                            <td><?php echo $dados['cli_numero']; ?></td>
                                            <td><?php echo $dados['cli_cidade']; ?></td>
                                            <td><?php echo $dados['cli_celular']; ?></td>
                                            <td>
                                                <span>
                                                    <a href="form_atualizar_cliente.php?cli_codigo=<?php echo $dados['cli_codigo']; ?>" class="mr-4" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fa fa-pencil fa-lg color-warning"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['cli_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                        <i class="fa fa-close fa-lg color-danger"></i>
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <!-- Script de confirmação com SweetAlert2 -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    function confirmDelete(cli_codigo) {
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
                                                window.location.href = 'deletar/delete_cliente.php?cli_codigo=' + cli_codigo;
                                            }
                                        });
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="js/mask.js"></script>


</body>

</html>