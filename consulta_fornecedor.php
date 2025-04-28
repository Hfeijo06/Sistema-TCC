<?PHP
session_start(); // Inicia a sessão

require_once('conexao/banco.php');

$cliente = isset($_REQUEST['for_cliente']) ? $_REQUEST['for_cliente'] : '';
$telefone = isset($_REQUEST['for_celular']) ? $_REQUEST['for_celular'] : '';
$email = isset($_REQUEST['for_email']) ? $_REQUEST['for_email'] : '';
$descricao = isset($_REQUEST['for_descricao']) ? $_REQUEST['for_descricao'] : '';
$cnpj = isset($_REQUEST['for_cnpj']) ? $_REQUEST['for_cnpj'] : '';

$sql = "select * from tb_fornecedores where for_nome like '%".$cliente."%' AND
        for_celular like '%".$telefone."%' AND
        for_email like '%".$email."%' AND
        for_descricao like '%".$descricao."%' AND
        for_cnpj like '%".$cnpj."%' AND
        for_codigo != 7
        ORDER BY for_codigo DESC";

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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Fornecedor</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Consulta Fornecedor</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h4 class="card-header">
                                Consulta de Fornecedores
                                <a href="form_cadastro_fornecedor.php">
                                    <button name="btn_salvar" class="btn-cadastro mb-1 float-right">+</button>
                                </a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="fornecedores">
                                        <input type="hidden" name="for_cliente" value="<?php echo htmlspecialchars($cliente); ?>">
                                        <input type="hidden" name="for_celular" value="<?php echo htmlspecialchars($telefone); ?>">
                                        <input type="hidden" name="for_email" value="<?php echo htmlspecialchars($email); ?>">
                                        <input type="hidden" name="for_descricao" value="<?php echo htmlspecialchars($descricao); ?>">
                                        <input type="hidden" name="for_cnpj" value="<?php echo htmlspecialchars($cnpj); ?>">

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
                                                <input name="for_cliente" type="text" id="cliente" class="form-control" placeholder="Nome do fornecedor">
                                                <input name="for_cnpj" onkeypress="mascaraCNPJ(this)" maxlength="18" type="text" id="CNPJ" class="form-control" placeholder="CNPJ">
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
                                                <input name="for_celular" onkeypress="mascara(this, celular)" maxlength="15" type="text" id="celular" class="form-control" placeholder="Celular">
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
                                                <input name="for_email" type="text" id="email" class="form-control" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="filter-form-group">
                                            <input name="for_descricao" type="text" id="descricao" class="form-control" placeholder="Descrição">   
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

                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>CNPJ</th>
                                                <th>Descrição</th>
                                                <th>Email</th>
                                                <th>Celular</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                            <tr>
                                                <th><?php echo $dados['for_codigo']; ?></th>
                                                <td><?php echo $dados['for_nome']; ?></td>
                                                <td><?php echo $dados['for_cnpj']; ?></td>
                                                <td><?php echo $dados['for_descricao']; ?></td>
                                                <td><?php echo $dados['for_email']; ?></td>
                                                <td><?php echo $dados['for_celular']; ?></td>
                                                <td>
                                                    <span>
                                                        <a href="form_atualizar_fornecedor.php?for_codigo=<?php echo $dados['for_codigo']; ?>" class="mr-4" data-toggle="tooltip" data-placement="top" title="Editar">
                                                            <i class="fa fa-pencil fa-lg color-warning"></i> 
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $dados['for_codigo']; ?>)" data-toggle="tooltip" data-placement="top" title="Deletar">
                                                            <i class="fa fa-close fa-lg color-danger"></i>
                                                        </a>
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            function confirmDelete(for_codigo) {
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
                                                                        window.location.href = 'deletar/delete_fornecedor.php?for_codigo=' + for_codigo;
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