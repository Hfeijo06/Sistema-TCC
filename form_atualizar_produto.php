<?php
require_once('conexao/banco.php');

$id = $_REQUEST['pro_codigo'];

// Captura os dados do produto
$sqlProduto = "SELECT * FROM tb_produtos WHERE pro_codigo = '$id'";
$sqlProdutoResult = mysqli_query($con, $sqlProduto) or die("Erro na sql!");
$dadosProduto = mysqli_fetch_array($sqlProdutoResult);

// Consulta para listar fornecedores, excluindo o fornecedor com código 7
$sql = "SELECT * FROM tb_fornecedores WHERE for_codigo != 7";
$sqlFornecedores = mysqli_query($con, $sql) or die("Erro na sql!");

// Captura os fornecedores já associados ao produto
$sqlFornecedoresProduto = "SELECT for_codigo FROM tb_produto_fornecedor WHERE pro_codigo = '$id'";
$sqlFornecedoresProdutoResult = mysqli_query($con, $sqlFornecedoresProduto) or die("Erro na sql!");
$fornecedoresAssociados = [];
while ($row = mysqli_fetch_array($sqlFornecedoresProdutoResult)) {
    $fornecedoresAssociados[] = $row['for_codigo'];
}
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
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Atualizar Produto</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Atualização de Produtos</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">
                                    <form name="frm_produto" method="post" action="atualizar/atualizar_produto.php">


                                    <div class="col-lg-12 mb-4">
                                        <div class="form-group">
                                            <label>Fornecedor</label>
                                            <?php while ($fornecedor = mysqli_fetch_array($sqlFornecedores)) { ?>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="fornecedor_<?php echo $fornecedor['for_codigo']; ?>" 
                                                        name="txt_fornecedor[]" 
                                                        value="<?php echo $fornecedor['for_codigo']; ?>"
                                                        <?php if (in_array($fornecedor['for_codigo'], $fornecedoresAssociados)) echo 'checked'; ?>>
                                                    <label class="form-check-label" for="fornecedor_<?php echo $fornecedor['for_codigo']; ?>">
                                                        <?php echo $fornecedor['for_descricao']; ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Nome</label>
                                            <input type="text" class="form-control" name="txt_nome" id="txt_nome" value="<?php echo $dadosProduto['pro_nome']; ?>" required>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Descrição</label>
                                            <textarea class="form-control" rows="4" name="txt_descricao" id="txt_descricao" required><?php echo $dadosProduto['pro_descricao']; ?></textarea>
                                        </div>

                                        <fieldset class="form-group">
                                            <label class="col-form-label col-sm-2 pt-0">Tipo do Produto</label>
                                            <div class="col-lg-12 mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="txt_tipo" id="tipo_agua" value="Água" 
                                                        <?php if ($dadosProduto['pro_tipo'] == 'Água') echo 'checked'; ?>>
                                                    <label class="form-check-label" for="tipo_agua">Água</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="txt_tipo" id="tipo_gas" value="Gás" 
                                                        <?php if ($dadosProduto['pro_tipo'] == 'Gás') echo 'checked'; ?>>
                                                    <label class="form-check-label" for="tipo_gas">Gás</label>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <div class="col-lg-12 mb-4">
                                            <label>Preço</label>
                                            <input type="text" class="form-control" name="txt_preco" id="txt_preco" value="<?php echo $dadosProduto['pro_preco']; ?>" required>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label>Validade</label>
                                            <input type="date" class="form-control" name="txt_validade" id="txt_validade" value="<?php echo $dadosProduto['pro_validade']; ?>" required>
                                        </div>

                                        <input type="hidden" name="txt_codigo" value="<?php echo $dadosProduto['pro_codigo']; ?>">

                                        <div class="button-container">
                                            <button type="button" id="btn_cancelar" class="btn btn-cancel">Cancelar</button>
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