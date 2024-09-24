<?PHP

require_once('conexao/banco.php');

$venda = isset($_REQUEST['venda']) ? $_REQUEST['venda'] : '0';

$sql = "select * from tb_clientes";
$sql = mysqli_query($con, $sql) or die ("Erro na sql!") ;

$sql2 = "select * from tb_produtos";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql2!");
    
$sql3 = "select * 
			from tb_itens_venda as i
			inner join tb_produtos as p on (i.pro_codigo = p.pro_codigo)
		where i.ven_codigo = '$venda'";
$sql3 = mysqli_query($con, $sql3) or die ("Erro na sql3!");

$sql4 = "select v.ven_codigo, v.ven_data, v.ven_tipo_pagamento, c.cli_nome, c.cli_codigo
          from tb_vendas as v
		  inner join tb_clientes as c on (v.cli_codigo = c.cli_codigo)
		  where v.ven_codigo = '$venda'";
$sql4 = mysqli_query($con, $sql4) or die ("Erro na sql4!");
$dados4 = mysqli_fetch_array($sql4);

?>


<script type="text/javascript">

function valorUnitario() {
	
	var preco;
	
		preco = document.frm_itens_venda.txt_produto.options[txt_produto.selectedIndex].getAttribute('data-preco');
		document.frm_itens_venda.txt_preco.value = preco;
}

function calculo(){

var qtde;
var valor;
var total;

qtde 	= document.frm_itens_venda.txt_qtde.value;
valor 	= document.frm_itens_venda.txt_preco.value.replace(",",".");

total 	= parseFloat(qtde) * parseFloat(valor);

	if (total > 0) {
		document.frm_itens_venda.txt_total.value = parseFloat(total);
	}

}


</script>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Focus - Bootstrap Admin Dashboard </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
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
                            <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <div class="dropdown-menu p-0 m-0">
                                    <form>
                                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-bell"></i>
                                    <div class="pulse-css"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="list-unstyled">
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-user"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Martin</strong> has added a <strong>customer</strong> Successfully
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="primary"><i class="ti-shopping-cart"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Jennifer</strong> purchased Light Dashboard 2.0.</p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="danger"><i class="ti-bookmark"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Robin</strong> marked a <strong>ticket</strong> as unsolved.
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="primary"><i class="ti-heart"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>David</strong> purchased Light Dashboard 1.0.</p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-image"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong> James.</strong> has added a<strong>customer</strong> Successfully
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                    </ul>
                                    <a class="all-notification" href="#">See all notifications <i
                                            class="ti-arrow-right"></i></a>
                                </div>
                            </li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="./app-profile.html" class="dropdown-item">
                                        <i class="icon-user"></i>
                                        <span class="ml-2">Profile </span>
                                    </a>
                                    <a href="./email-inbox.html" class="dropdown-item">
                                        <i class="icon-envelope-open"></i>
                                        <span class="ml-2">Inbox </span>
                                    </a>
                                    <a href="./page-login.html" class="dropdown-item">
                                        <i class="icon-key"></i>
                                        <span class="ml-2">Logout </span>
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
                        <div class="welcome-text">
                            <h4>Hi, welcome back!</h4>
                            <span class="ml-1">Element</span>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Element</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulário de Vendas</h4>
                            </div> 

                            <div class="card-body">
                                <div class="basic-form">

                                <?PHP if($venda == 0) { ?>
                                    <form name="frm_venda" method="post" action="cadastro/cadastro_venda.php">

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Cliente</label>
                                                <select class="form-control" name="txt_cliente" id="txt_cliente" required>
                                                    <option checked>Escolha Uma Opção</option>
                                                        <?php while ($dados = mysqli_fetch_array($sql)) { ?>
                                                    <option value="<?php echo $dados['cli_codigo']; ?>"> <?php echo $dados['cli_nome']; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label class="text-label">Data da Venda*</label>
                                                <input type="date" name="txt_data" id="txt_data" class="form-control" placeholder="Digite a Data da Venda" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo do Pagamento</label>
                                                <select class="form-control" name="txt_tipo_pag" id="txt_tipo_pag" required>
                                                    <option checked>Escolha Uma Opção</option>
                                                    <option value="1">Dinheiro</option>
                                                    <option value="2">Cartão</option>
                                                    <option value="3">Crédiario</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <button type="submit" id="btn_salvar" class="btn btn-primary">Cadastrar</button>
                                        </div>

                                    </form>

                                <?PHP } else {  ?>

                                    <form name="frm_venda" method="post" action="cadastro/cadastro_venda.php">
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Cliente</label>
                                                <select class="form-control" name="txt_cliente" id="txt_cliente" disabled>
                                                    <option value="<?php echo $dados4['cli_codigo']; ?>"> <?php echo $dados4['cli_nome']; ?> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label class="text-label">Data da Venda*</label>
                                                <input type="date" name="txt_data" id="txt_data" class="form-control" value="<?php echo $dados4['ven_data']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Tipo do Pagamento</label>
                                                <select class="form-control" name="txt_tipo_pag" id="txt_tipo_pag" disabled>
                                                    <option checked>Escolha Uma Opção</option>
                                                    <option <?php if ($dados4['ven_tipo_pagamento'] == "1") { echo "selected";} ?> > Dinheiro </option>
                                                    <option <?php if ($dados4['ven_tipo_pagamento'] == "2") { echo "selected";} ?> > Cartão </option>
                                                    <option <?php if ($dados4['ven_tipo_pagamento'] == "3") { echo "selected";} ?> > Crediário </option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <form name="frm_itens_venda" method="post" action="cadastro/cadastro_itens_venda.php">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <input type="hidden" class="form-control" name="txt_venda" id="txt_venda" value="<?php echo $venda; ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label>Produto</label>
                                                <select name="txt_produto" id="txt_produto" class="form-control" onclick="valorUnitario()">
                                                <option selected>Escolha Uma Opção</option>
                                                    <?php while ($dados2 = mysqli_fetch_array($sql2)) { ?>
                                                        <option value="<?php echo $dados2['pro_codigo'] ; ?>" data-preco="<?php echo $dados2['pro_preco'] ; ?>"> <?php echo $dados2['pro_descricao']; ?> </option>
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
                                                <input type="number" class="form-control" name="txt_qtde" id="txt_qtde" placeholder="Digite a Quantidade do Produto" onblur="calculo()">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group row">
                                                <label>Total</label>
                                                <input type="number" class="form-control" name="txt_total" id="txt_total" placeholder="" readonly="true">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <button type="submit" id="btn_salvar" class="btn btn-primary">Cadastrar</button>
                                        </div>

                                    </form>
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
                                                <td><?php echo $dados3['itv_preco']; ?></td>
                                                <td><?php echo $dados3['itv_qtde']; ?></td>
                                                <td><?php echo $dados3['itv_total']; ?></td>
                                                <td>
                                                    <span>
                                                        <a href="deletar/delete_itens_venda.php?itv_codigo=<?php echo $dados3['itv_codigo']; ?>&&ven_codigo=<?php echo $dados3['ven_codigo']; ?>" data-toggle="tooltip" data-placement="top" title="Close">
                                                            <i class="fa fa-close color-danger"></i>
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
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p>
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