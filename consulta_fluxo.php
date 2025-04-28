<?PHP
session_start(); // Inicia a sessão
require_once('conexao/banco.php');

$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

$sql = "SELECT f.*, c.cp_data_pagamento, r.cr_data_pagamento 
        FROM tb_fluxo_caixa AS f
        LEFT JOIN tb_contas_pagar AS c ON f.cp_codigo = c.cp_codigo
        LEFT JOIN tb_contas_receber AS r ON f.cr_codigo = r.cr_codigo
        ";

if (!empty($data_inicio) && !empty($data_fim)) {
    $sql .= " WHERE fc_data BETWEEN '$data_inicio' AND '$data_fim'";
}


$sql2 = "
        SELECT 
        SUM(CASE WHEN fc_tipo = 'entrada' THEN fc_valor ELSE 0 END) AS total_entradas,
        SUM(CASE WHEN fc_tipo = 'saida' THEN fc_valor ELSE 0 END) AS total_saidas,
        (SUM(CASE WHEN fc_tipo = 'entrada' THEN fc_valor ELSE 0 END) - 
        SUM(CASE WHEN fc_tipo = 'saida' THEN fc_valor ELSE 0 END)) AS saldo
        FROM tb_fluxo_caixa";

if (!empty($data_inicio) && !empty($data_fim)) {
    $sql2 .= " WHERE fc_data BETWEEN '$data_inicio' AND '$data_fim'";
}


$sql .= " ORDER BY f.fc_codigo DESC";

// Executa as consultas separadamente
$resultado1 = mysqli_query($con, $sql) or die("Erro na SQL 1!");
$resultado2 = mysqli_query($con, $sql2) or die("Erro na SQL 2!");

// Verificar se houve retorno de dados da segunda consulta
if ($row = mysqli_fetch_assoc($resultado2)) {
    $total_entradas = $row['total_entradas'];
    $total_saidas = $row['total_saidas'];
    $saldo = $row['saldo'];
} else {
    echo "Nenhum dado encontrado.";
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
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logocima.png">
    <!-- Custom Stylesheet -->
    <link href="./css/form.css" rel="stylesheet">
    <link href="./css/export.css" rel="stylesheet">
    <link href="./css/table.css" rel="stylesheet">
    <link href="./css/icon.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        .badge-success{
            color: white;
            background-color: #297F00;
        }
        .badge-danger{
            color: white;
            background-color: #EE3232;
        }

    </style>
    <style>
        .filter-form {
            background: #fff;
            padding: 0.2rem;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .filter-form-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            align-items: end;
            margin-bottom: 1.5rem;
        }

        .filter-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-form-group label {
            font-weight: 500;
            color: #374151;
            font-size: 0.87rem;
            margin-top: 0.5rem;
        }

        .filter-form-group input[type="date"] {
            width: 100%;
            padding: 0.55rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.87rem;
            color: #4b5563;
            background-color: #fff;
            transition: all 0.2s ease-in-out;
        }

        .filter-form-group input[type="date"]:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-form-buttons {
            display: flex;
            gap: 1rem;
            padding: 0.5rem;
            justify-content: flex-start;
        }

        .btn-filter {
            padding: 0.5rem 0.6rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.2s ease-in-out;
            min-width: 120px;
            max-width: 200px;
            border: none;
            cursor: pointer;
        }

        .btn-filter-primary {
            background-color: #2563eb;
            color: white;
        }

        .btn-filter-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-filter-secondary {
            background-color: red;
            color: white;
        }

        .btn-filter-secondary:hover {
            background-color: darkred;
        }

        @media (max-width: 640px) {
            .filter-form {
                padding: 1rem;
            }
            
            .filter-form-buttons {
                flex-direction: column;
            }
            
            .btn-filter {
                max-width: 100%;
            }
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

            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-arrow-up text-success border-success"></i>
                            </div>
                            <div class="stat-content d-inline-block">
                                <div class="stat-text">Entrada</div>
                                <div class="stat-digit"><?php echo number_format($total_entradas, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-arrow-down text-danger border-danger"></i>
                            </div>
                            <div class="stat-content d-inline-block">
                                <div class="stat-text">Saída</div>
                                <div class="stat-digit"><?php echo number_format($total_saidas, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-wallet text-primary border-primary"></i>
                            </div>
                            <div class="stat-content d-inline-block">
                                <div class="stat-text">Saldo</div>
                                <div class="stat-digit" 
                                    style="color: <?php echo ($saldo >= 0) ? 'green' : 'red'; ?>;">
                                    <?php echo number_format($saldo, 2, ',', '.'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


                <!-- row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Consulta de Fluxo de Caixa</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <form name="frm_exportar" method="GET">
                                        <input type="hidden" name="relatorio" value="fluxo">
                                        <input type="hidden" name="data_inicio" value="<?php echo htmlspecialchars($data_inicio); ?>">
                                        <input type="hidden" name="data_fim" value="<?php echo htmlspecialchars($data_fim); ?>">

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
                                                <label for="data_inicio">Data Inicial:</label>
                                                <input type="date" name="data_inicio" id="data_inicio">
                                            </div>
                                            
                                            <div class="filter-form-group">
                                                <label for="data_fim">Data Final:</label>
                                                <input type="date" name="data_fim" id="data_fim">
                                            </div>
                                        </div>
                                        
                                        <div class="filter-form-buttons">
                                            <button id="botaoS" type="submit" class="btn-filter btn-filter-primary">
                                                Filtrar
                                            </button>
                                            <button id="botaoLimpar" type="button" class="btn-filter btn-filter-secondary" onclick="limparPesquisa()">
                                                Limpar Filtro
                                            </button>
                                            <script>
                                                function limparPesquisa() {
                                                    document.querySelector("form[name='frm_consulta']").reset();
                                                    window.location.href = window.location.pathname;
                                                }
                                            </script>
                                        </div>
                                    </form>
                                    <table class="table student-data-table m-t-20">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo da Despesa</th>
                                                <th>Valor</th>
                                                <th>Data Vencimento</th>
                                                <th>Data Pagamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($dados = mysqli_fetch_array($resultado1)) { ?>
                                            <tr>
                                                <td><?php echo $dados['fc_codigo']; ?></td>
                                                <td><span class="badge <?php echo $dados['fc_tipo'] == 'Entrada' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo $dados['fc_tipo']; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $dados['fc_valor']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($dados['fc_data'])); ?></td>
                                                <td><?php
                                                    if ($dados['fc_tipo'] == 'Entrada' && $dados['cr_data_pagamento']) {
                                                        echo date('d/m/Y', strtotime($dados['cr_data_pagamento']));
                                                    } elseif ($dados['fc_tipo'] == 'Saída' && $dados['cp_data_pagamento']) {
                                                        echo date('d/m/Y', strtotime($dados['cp_data_pagamento']));
                                                    } else {
                                                        echo '—';
                                                    }
                                                    ?> 
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
    



</body>

</html>