<?PHP
// Inicia a sessão
session_start();

// Verifica se a variável de sessão para mostrar o toast está definida
$showToast = isset($_SESSION['showToast']) ? $_SESSION['showToast'] : false;

// Remove a variável de sessão após o uso
if ($showToast) {
    unset($_SESSION['showToast']);
}

require_once('conexao/banco.php');

//Produtos em Falta
$sql2 = "SELECT p.pro_descricao, e.est_qtde  
            FROM tb_produtos AS p
            INNER JOIN tb_estoque AS e ON (p.pro_codigo = e.pro_codigo)
            WHERE e.est_qtde < 25  
            ORDER BY e.est_qtde ASC";
$sql2 = mysqli_query($con, $sql2) or die ("Erro na sql4!");


$produtos_em_falta = 0;
while ($dados2 = mysqli_fetch_array($sql2)) {
    $produtos_em_falta++;
}


// clientes Devedores
$sql_devedores = "SELECT COUNT(DISTINCT c.cli_codigo) AS total_devedores
                    FROM tb_clientes AS c
                    JOIN tb_vendas AS v ON c.cli_codigo = v.cli_codigo
                    JOIN tb_pagamento AS p ON v.ven_codigo = p.ven_codigo
                    JOIN tb_parcelas AS pr ON p.pag_codigo = pr.pag_codigo
                    JOIN tb_contas_receber AS cr ON pr.parc_codigo = cr.parc_codigo
                    WHERE cr.cr_status = 'Pendente' 
                    AND pr.parc_data_vencimento < NOW();
                    ";

$query_devedores = mysqli_query($con, $sql_devedores) or die("Erro na consulta de devedores!");

$dados_devedores = mysqli_fetch_array($query_devedores);
$total_devedores = $dados_devedores['total_devedores'];

$query = "SELECT COUNT(*) AS entregas_hoje 
          FROM tb_vendas 
          WHERE (ven_data_entrega BETWEEN CONCAT(CURDATE(), ' 06:00:00') AND CONCAT(CURDATE(), ' 23:59:59'))
          AND (ven_status_entrega != 'Entregue')";
$resultado = mysqli_query($con, $query);
$entregas_hoje = mysqli_fetch_assoc($resultado)['entregas_hoje'];


?>

<!DOCTYPE html>

<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistema - Neo Enigma </title>
    <!-- Font Awesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logocima.png">
    
    <!-- Vendor CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css' />
    <link href="./vendor/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <link href="./vendor/chartist/css/chartist.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">



    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style> 
        /* Estilo geral do modal */
        #modal-produtos-falta {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Fundo semi-transparente */
            overflow: auto;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Conteúdo do modal */
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%; /* Ajuste conforme necessário */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Sombra */
            animation: fadeIn 0.3s ease-out; /* Animação suave ao abrir */
        }

        /* Animação para o modal aparecer suavemente */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Estilização do título */
        .modal-content h4 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #f1f1f1; /* Linha decorativa abaixo do título */
            padding-bottom: 10px;
        }

        /* Lista de clientes */
        #lista-produtos {
            list-style: none;
            padding: 0;
        }

        #lista-produtos li {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 6px;
            border-left: 5px solid red; /* Borda para destaque */
            color: black;
        }

        /* Botões de ação */
        #lista-produtos button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #lista-produtos button:hover {
            background-color: #0056b3; /* Cor ao passar o mouse */
        }

        /* Detalhes do cliente */
        #detalhes-produto {
            margin-top: 20px;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 6px;
            border-left: 5px solid #007bff; /* Borda para destaque */
        }

        #detalhes-produto h5 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        #detalhes-produto p {
            font-size: 16px;
            margin: 5px 0;
            color: black;
        }

        /* Botão fechar modal */
        .modal-footer {
            text-align: right;
            margin-top: 20px;
        }

        .modal-close {
            background-color: #d9534f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-close:hover {
            background-color: #c9302c;
        }
	/* Botão padrão */
            button {
                font-family: Arial, sans-serif;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                font-size: 14px;
            }

            button:hover {
                background-color: #0056b3;
            }

	/* Ajuste no espaçamento do botão "Ver mais" */
            div#detalhes-produto button {
                margin-left: 15px;
                font-size: 14px;
            }

	/* Botão de comprar com estilo verde */
            #comprar-produto {
                background-color: #28a745;
                margin-top: 10px;
                margin-bottom: 10px;
                font-size: 16px;
            }

            #comprar-produto:hover {
                background-color: #218838;
            }

            /* Media query para dispositivos móveis */
            @media (max-width: 768px) {
                .modal-content {
                    width: 90%; /* Ajusta a largura da caixa do modal para dispositivos móveis */
                }
            }

    </style>

    <style>
        /* Estilo geral do modal */
        #modal-clientes-devedores {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Fundo semi-transparente */
            overflow: auto;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Conteúdo do modal */
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%; /* Ajuste conforme necessário */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Sombra */
            animation: fadeIn 0.3s ease-out; /* Animação suave ao abrir */
        }

        /* Animação para o modal aparecer suavemente */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Estilização do título */
        .modal-content h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #f1f1f1; /* Linha decorativa abaixo do título */
            padding-bottom: 10px;
        }

        /* Lista de clientes */
        #lista-clientes {
            list-style: none;
            padding: 0;
        }

        #lista-clientes li {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 6px;
            border-left: 5px solid red; /* Borda para destaque */
            color: black;
        }

        /* Botões de ação */
        #lista-clientes button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #lista-clientes button:hover {
            background-color: #0056b3; /* Cor ao passar o mouse */
        }

        /* Detalhes do cliente */
        #detalhes-cliente {
            margin-top: 20px;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 6px;
            border-left: 5px solid #007bff; /* Borda para destaque */
        }

        #detalhes-cliente h5 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        #detalhes-cliente p {
            font-size: 16px;
            margin: 5px 0;
            color: black;
        }

        /* Botão fechar modal */
        .modal-footer {
            text-align: right;
            margin-top: 20px;
        }

        .modal-close {
            background-color: #d9534f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-close:hover {
            background-color: #c9302c;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
            }
        }

        /* Modal styles */
        .modal-dialog {
            max-width: 800px; /* Modal mais largo */
            margin: 30px auto;
        }

        .modal-content {
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2); /* Sombra mais suave e profunda */
        }

        .modal .modal-header {
            background-color: white !important;
            color: white !important;
            padding: 16px 24px !important;
            border-radius: 12px 12px 0 0 !important;
        }

        .modal-title {
            font-weight: bold;
            font-size: 20px;
        }

        /* Botão de Fechar (X) */
        .modal-header .close {
            font-size: 22px;
            font-weight: bold;
            color: red;
            opacity: 0.9;
            transition: opacity 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
        }

        .modal-header .close:hover {
            opacity: 1; /* Efeito hover para destaque */
        }

        .modal-body {
            padding: 24px;
            font-size: 16px;
            color: #333;
        }

        /* Timeline styles */
        .timeline {
            list-style-type: none;
            position: relative;
            margin: 0;
        }

        .timeline:before {
            content: '';
            background: #d4d9df;
            position: absolute;
            left: 15px;
            width: 3px;
            height: 100%;
            z-index: 1;
        }

        .timeline > li {
            margin: 30px 0;
            position: relative;
            padding-left: 30px;
        }

        .timeline > li:before {
            content: '';
            background: white;
            position: absolute;
            border-radius: 50%;
            border: 4px solid #22c0e8;
            left: 5px;
            width: 24px;
            height: 24px;
            z-index: 2;
        }

        /* Delivery item styles */
        .timeline > li > div {
            margin-bottom: 10px;
        }

        .timeline > li strong {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        /* Status dropdown styles */
        .status-entrega {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 14px;
        }

        /* Ajustes responsivos */
        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 90%; /* Mais adaptado para telas pequenas */
            }

            .timeline {
                padding-left: 20px;
            }

            .timeline:before {
                left: 8px;
            }

            .timeline > li {
                padding-left: 20px;
            }

            .timeline > li:before {
                left: -4px;
            }
        }

    </style>


</head>

<body>

    <!-- Scripts necessários -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        <?php if ($showToast): ?>
            // Configura as opções de notificação
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };
            // Exibe a notificação em amarelo
            toastr.warning("Operação cancelada!", "Atenção");
        <?php endif; ?>
    </script>

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
                                    <a href="manual/Manual_Sistema_NeoEnigma.pdf" class="dropdown-item" target="_blank">
                                        <i class="fa fa-book"></i>
                                        <span class="ml-2">Manual</span>
                                    </a>
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

                    <!--Card 1 -->
                    <div class="col-lg-4 col-sm-6">
                        <div id="card-devedores" class="card-devedores" style="background-color: #ffffff; border-left: 5px solid <?php 
                            echo ($total_devedores == 0) ? '#5cb85c' : 'red'; // Verde se não houver devedores, vermelho se houver
                        ?>; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="stat-widget-one card-body d-flex align-items-center">
                                <div class="stat-icon d-inline-block">
                                    <i class="fas fa-user <?php echo ($total_devedores == 0) ? 'text-success' : 'text-danger'; ?>" style="font-size: 36px;"></i>
                                </div>
                                <div class="stat-content d-inline-block ml-3">
                                    <?php if ($total_devedores == 0): ?>
                                        <div class="stat-text" style="font-size: 16px; font-weight: bold; color: #333;">Nenhum cliente devedor</div>
                                        <div class="stat-digit" style="font-size: 20px; color: #5cb85c;">Tudo ok!</div>
                                    <?php else: ?>
                                        <div class="stat-text" style="font-size: 16px; font-weight: bold; color: #333;">Clientes Devedores</div>
                                        <div class="stat-digit" style="font-size: 20px; color: red;">
                                            <?php echo $total_devedores; ?> <!-- Mostra a quantidade de devedores -->
                                        </div>
                                        <a href="#" id="btn-ver-mais" style="color: blue; font-size: 14px;">Ver mais</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Clientes Devedores -->
                    <div id="modal-clientes-devedores" class="modal" style="display:none;">
                        <div class="modal-content">
                            <h4>Clientes Devedores</h4>
                            <ul id="lista-clientes">
                                <!-- Lista será preenchida via AJAX -->
                            </ul>
                            <div id="detalhes-cliente">
                                <!-- Detalhes e informações do cliente selecionado -->
                            </div>
                            <!-- Área dos botões -->
                            <div class="modal-footer">
                                <button id="modal-close" class="modal-close">Fechar</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        let clienteAberto = null; // Guardar o ID do cliente cujos detalhes estão abertos

                        // Abrir o modal ao clicar no card
                        document.getElementById('btn-ver-mais').addEventListener('click', function(event) {
                            event.preventDefault(); // Evitar comportamento padrão do link
                            document.getElementById('modal-clientes-devedores').style.display = 'block'; // Exibe o modal

                            // Carrega a lista de clientes devedores via AJAX
                            fetch('get_clientes_devedores.php') // O arquivo PHP que retorna os clientes devedores
                                .then(response => response.json())
                                .then(data => {
                                    const lista = document.getElementById('lista-clientes');
                                    lista.innerHTML = ''; // Limpa a lista
                                    data.forEach(cliente => {
                                        const li = document.createElement('li');
                                        li.innerHTML = `
                                            <span>${cliente.nome} (Valor Devido: R$ ${cliente.valor_devido})</span>
                                            <button id="btn-${cliente.id}" onclick="toggleDetalhesCliente(${cliente.id})">Ver Detalhes</button>
                                        `;
                                        lista.appendChild(li);
                                    });
                                });
                        });

                        // Função para alternar a exibição dos detalhes do cliente selecionado
                        function toggleDetalhesCliente(clienteId) {
                            const detalhes = document.getElementById('detalhes-cliente');
                            
                            // Se já existe um cliente aberto e é diferente do atual, feche-o
                            if (clienteAberto && clienteAberto !== clienteId) {
                                document.getElementById(`btn-${clienteAberto}`).innerText = 'Ver Detalhes';
                                detalhes.innerHTML = ''; // Limpa os detalhes anteriores
                            }

                            // Verificar se os detalhes do mesmo cliente estão abertos
                            if (clienteAberto === clienteId) {
                                detalhes.innerHTML = ''; // Oculta os detalhes
                                document.getElementById(`btn-${clienteId}`).innerText = 'Ver Detalhes';
                                clienteAberto = null; // Resetar o cliente aberto
                            } else {
                                // Carregar os detalhes via AJAX
                                fetch(`get_detalhes_cliente.php?id=${clienteId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        // Formatar a data de vencimento
                                        const dataVencimento = new Date(data.data_vencimento);
                                        const dataVencimentoFormatada = dataVencimento.toLocaleDateString('pt-BR'); // Formato brasileiro

                                        // Limpar a máscara do celular para o link do WhatsApp
                                        const celularSemMascara = data.celular.replace(/\D/g, '');

                                        detalhes.innerHTML = `<h5>Detalhes do Cliente</h5>
                                            <p><strong>Nome:</strong> ${data.nome}</p>
                                            <p><strong>CPF:</strong> ${data.cpf}</p>
                                            <p><strong>Celular:</strong> ${data.celular}
                                                <a href="https://wa.me/${celularSemMascara}" target="_blank" style="margin-left: 5px;">
                                                    <i class="fab fa-whatsapp" style="color: green; font-size: 20px;"></i>
                                                </a>
                                            </p>
                                            <p><strong>Valor Devido:</strong> R$ ${data.valor_devido}</p>
                                            <p><strong>Data de Vencimento:</strong> ${dataVencimentoFormatada}</p>`; // Usar a data formatada
                                        document.getElementById(`btn-${clienteId}`).innerText = 'Ver Menos'; // Alterar o botão para "Ver Menos"
                                        clienteAberto = clienteId; // Guardar o ID do cliente atual
                                    })
                                    .catch(err => console.error('Erro ao carregar detalhes do cliente:', err));
                            }
                        }



                        // Função para fechar o modal e resetar o estado
                        function fecharModalClientes() {
                            // Fecha o modal
                            document.getElementById('modal-clientes-devedores').style.display = 'none';

                            // Limpa os detalhes ao fechar
                            const detalhes = document.getElementById('detalhes-cliente');
                            detalhes.innerHTML = ''; 

                            // Resetar o botão do cliente aberto
                            if (clienteAberto) {
                                document.getElementById(`btn-${clienteAberto}`).innerText = 'Ver Detalhes';
                                clienteAberto = null; // Resetar o cliente aberto
                            }
                        }

                        // Adicionar o evento de fechamento ao botão
                        document.getElementById('modal-close').addEventListener('click', fecharModalClientes);

                        // Fechar o modal ao clicar fora do conteúdo do modal
                        window.onclick = function(event) {
                            const modal = document.getElementById('modal-clientes-devedores');
                            if (event.target === modal) {
                                fecharModalClientes();
                            }
                        }
                    </script>



                    <!--Card 2 -->
                    <div class="col-lg-4 col-sm-6">
                        <div class="card" id="card-estoque" style="background-color: #ffffff; border-left: 5px solid <?php 
                            echo ($produtos_em_falta == 0) ? '#5cb85c' : 
                                (($produtos_em_falta > 0) ? 'red' : '#f0ad4e'); 
                        ?>; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="stat-widget-one card-body d-flex align-items-center">
                                <div class="stat-icon d-inline-block">
                                    <i class="ti-package <?php echo ($produtos_em_falta == 0) ? 'text-success' : (($produtos_em_falta <= 100) ? 'text-danger' : 'text-warning'); ?>" style="font-size: 36px;"></i>
                                </div>
                                <div class="stat-content d-inline-block ml-3">
                                    <?php if ($produtos_em_falta == 0): ?>
                                        <div class="stat-text" style="font-size: 16px; font-weight: bold; color: #333;">Nenhum produto em falta</div>
                                        <div class="stat-digit" style="font-size: 20px; color: #5cb85c;">Tudo ok!</div>
                                    <?php else: ?>
                                        <div class="stat-text" style="font-size: 16px; font-weight: bold; color: #333;">Produtos com baixo estoque</div>
                                        <div class="stat-digit" style="font-size: 20px; color: <?php echo ($produtos_em_falta <= 100) ? '#d9534f' : '#f0ad4e'; ?>;">
                                            <?php echo $produtos_em_falta; ?>
                                        </div>
                                        <a href="#" data-toggle="modal" data-target="#modalDevedores" style="color: blue; font-size: 14px;">Ver mais</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>


                   <!-- Modal Produtos em Falta -->
                    <div id="modal-produtos-falta" class="modal">
                        <div class="modal-content">
                            <h4>Produtos em Falta</h4>
                            <ul id="lista-produtos">
                                <!-- Lista será preenchida via AJAX -->
                            </ul>
                            <div id="detalhes-produto">
                                <!-- Detalhes e fornecedores do produto selecionado -->
                            </div>
                            <!-- Área dos botões -->
                            <div class="modal-footer">
                                <button class="modal-close" onclick="fecharModal()">Fechar</button>
                            </div>
                        </div>
                    </div>

                    <script>

                        // Abrir o modal ao clicar no card
                            document.getElementById('card-estoque').addEventListener('click', function() {
                                document.getElementById('modal-produtos-falta').style.display = 'block'; // Exibe o modal

                                // Carrega a lista de produtos em falta via AJAX
                                fetch('get_produtos_falta.php') // O arquivo PHP que retorna os produtos em falta
                                    .then(response => response.json())
                                    .then(data => {
                                        const lista = document.getElementById('lista-produtos');
                                        lista.innerHTML = ''; // Limpa a lista
                                        data.forEach(produto => {
                                            const li = document.createElement('li');
                                            li.innerHTML = `
                                                <span>${produto.pro_nome} (Quantidade: ${produto.est_qtde})</span>
                                                <button onclick="verFornecedores(${produto.pro_codigo})">Ver Fornecedores</button>
                                            `;
                                            lista.appendChild(li);
                                        });
                                    });
                            });

                            // Função para ver fornecedores do produto selecionado
                            function verFornecedores(produtoCodigo) {
                                fetch(`get_fornecedores_produto.php?pro_codigo=${produtoCodigo}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const detalhes = document.getElementById('detalhes-produto');
                                        detalhes.innerHTML = '<h5>Fornecedores</h5>';
                                        if (data.length === 0) {
                                            detalhes.innerHTML += '<p>Nenhum fornecedor encontrado para este produto.</p>';
                                        } else {
                                            data.forEach(fornecedor => {
                                                const fornecedorContainer = document.createElement('div');
                                                fornecedorContainer.innerHTML = `
                                                    <p>
                                                        <strong>${fornecedor.for_nome}</strong> (CNPJ: ${fornecedor.for_cnpj})
                                                        <button onclick="verMaisDetalhes(${fornecedor.for_codigo}, this)">Ver mais</button>
                                                    </p>
                                                    <div id="detalhes-fornecedor-${fornecedor.for_codigo}" style="display: none;"></div>
                                                `;
                                                detalhes.appendChild(fornecedorContainer);
                                            });
                                        }
                                                                            // Adicionando o botão de comprar
                                    const comprarButton = document.createElement('button');
                                    comprarButton.innerHTML = 'Comprar';
                                    comprarButton.id = 'comprar-produto';
                                    comprarButton.onclick = function() {
                                        window.location.href = `form_cadastro_compra.php`;
                                    };
                                    detalhes.appendChild(comprarButton);
                                    
                                    });
                            }

                            // Função para ver mais detalhes do fornecedor
                            function verMaisDetalhes(fornecedorCodigo, button) {
                                const detalhesDiv = document.getElementById(`detalhes-fornecedor-${fornecedorCodigo}`);

                                if (detalhesDiv.style.display === 'none') {
                                    // Se os detalhes não estão visíveis, busca os detalhes via AJAX
                                    fetch(`get_detalhes_fornecedor.php?for_codigo=${fornecedorCodigo}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            detalhesDiv.innerHTML = `
                                                <p><strong>Descrição: </strong>${data.for_descricao}</p>
                                                <p><strong>Celular: </strong>${data.for_celular}
                                                    <a href="https://wa.me/${data.for_celular.replace(/\D/g, '')}" target="_blank" style="margin-left: 5px;">
                                                        <i class="fab fa-whatsapp" style="color: green; font-size: 20px;"></i>
                                                    </a>
                                                </p>
                                                <p><strong>Telefone: </strong>${data.for_telefone}</p>
                                                <p><strong>Email: </strong>${data.for_email}</p>
                                            `;
                                            detalhesDiv.style.display = 'block'; // Mostra os detalhes
                                            button.innerText = 'Ver menos'; // Altera o texto do botão
                                        });
                                } else {
                                    // Se os detalhes já estão visíveis, esconde a div e altera o texto do botão
                                    detalhesDiv.style.display = 'none';
                                    button.innerText = 'Ver mais';
                                }
                            }




                            // Função para redirecionar para o formulário de compra
                            function comprar(codigoFornecedor) {
                                window.location.href = `form_cadastro_compra.php?fornecedor=${codigoFornecedor}`;
                            }

                            // Função para fechar o modal
                            function fecharModal() {
                                document.getElementById('modal-produtos-falta').style.display = 'none';
                            }


                    </script>

                    <div class="col-lg-4 col-sm-6">
                        <div class="card" id="entregas-card" style="background-color: #ffffff; border-left: 5px solid #5cb85c; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                    <i class="fas fa-truck text-success"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-text" style="font-size: 16px; font-weight: bold; color: #333;">Entregas Para Hoje</div>
                                    <div class="stat-digit" id="entregas-counter" style="font-size: 20px; color: #5cb85c;"><?php echo $entregas_hoje; ?></div>
                                    <a href="#" data-toggle="modal" data-target="#timelineModal" style="color: blue; font-size: 14px;">Ver mais</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="timelineModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 12px;">
                                <div class="modal-header" style="background-color: #5cb85c; color: white; border-radius: 12px 12px 0 0;">
                                    <h5 class="modal-title">Entregas do Dia</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="padding: 20px;">
                                    <ul id="timeline" class="timeline"></ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $("#entregas-card").click(function() {
                            $("#timelineModal").modal('show');
                            carregarTimeline();
                        });

                        function carregarTimeline() {
                            $.ajax({
                                url: 'get_entregas.php',
                                method: 'GET',
                                success: function(data) {
                                    $("#timeline").html(data);
                                }
                            });
                        }

                        $(document).on('change', '.status-entrega', function() {
                            var status = $(this).val();
                            var vendaId = $(this).data('id');
                            var listItem = $(this).closest('li');
                            
                            $.ajax({
                                url: 'atualizar_status.php',
                                method: 'POST',
                                data: {id: vendaId, status: status},
                                success: function(response) {
                                    if (response === 'success') {
                                        if (status === 'Entregue') {
                                            // Remove o item da lista
                                            listItem.fadeOut(300, function() {
                                                $(this).remove();
                                                
                                                // Verifica se a lista está vazia
                                                if ($('#timeline li').length === 0) {
                                                    $('#timeline').html('<li>Não há entregas pendentes.</li>');
                                                }
                                                
                                                // Atualiza o contador de entregas
                                                atualizarContadorEntregas();
                                            });
                                        }
                                        // SweetAlert para exibir mensagem de sucesso
                                        Swal.fire({
                                            title: 'Sucesso!',
                                            text: 'Status atualizado com sucesso!',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        });
                                    } else {
                                        // SweetAlert para exibir mensagem de erro
                                        Swal.fire({
                                            title: 'Erro',
                                            text: 'Erro ao atualizar o status.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                },
                                error: function() {
                                    // SweetAlert para erro na comunicação com o servidor
                                    Swal.fire({
                                        title: 'Erro',
                                        text: 'Erro na comunicação com o servidor.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });

                            function atualizarContadorEntregas() {
                                $.ajax({
                                    url: 'get_contador_entregas.php',
                                    method: 'GET',
                                    success: function(data) {
                                        // Atualiza o contador no card na página principal
                                        $('#entregas-counter').text(data);
                                    }
                                });
                            }
                        });
                    </script>




                </div>
                <h2 class="mb-4">Agenda</h2>

<span id="msg"></span>

<div id='calendar'></div>

<!-- Modal Visualizar -->
<div class="modal fade" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="visualizarModalLabel">Visualizar o Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <dl class="row">

                    <dt class="col-sm-3">ID: </dt>
                    <dd class="col-sm-9" id="visualizar_id"></dd>

                    <dt class="col-sm-3">Título: </dt>
                    <dd class="col-sm-9" id="visualizar_title"></dd>

                    <dt class="col-sm-3">Data: </dt>
                    <dd class="col-sm-9" id="visualizar_start"></dd>

                    <dt class="col-sm-3">Descrição: </dt>
                    <dd class="col-sm-9" id="visualizar_descricao"></dd>

                </dl>
                <button type="button" class="btn btn-danger" id="btnApagarEvento">Apagar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar -->
<div class="modal fade" id="cadastrarModal" tabindex="-1" aria-labelledby="cadastrarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cadastrarModalLabel">Cadastrar o Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <span id="msgCadEvento"></span>

                <form method="POST" id="formCadEvento">

                    <div class="row mb-3">
                        <label for="cad_title" class="col-sm-2 col-form-label">Título</label><br>
                        <div class="col-sm-12">
                            <input type="text" name="cad_title" class="form-control" id="cad_title" placeholder="Título do evento">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="cad_descricao" class="col-sm-2 col-form-label">Descrição</label>
                        <div class="col-sm-12">
                            <input type="text" name="cad_descricao" class="form-control" id="cad_descricao" placeholder="Descrição do evento">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="cad_start" class="col-sm-2 col-form-label">Data</label>
                        <div class="col-sm-12">
                            <input type="date" name="cad_start" class="form-control" id="cad_start">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="cad_color" class="col-sm-2 col-form-label">Cor</label>
                        <div class="col-sm-12">
                            <select name="cad_color" class="form-control" id="cad_color">
                                <option value="">Selecione</option>
                                <option style="color:#FFD700;" value="#FFD700">Amarelo</option>
                                <option style="color:#0071c5;" value="#0071c5">Azul Turquesa</option>
                                <option style="color:#FF4500;" value="#FF4500">Laranja</option>
                                <option style="color:#8B4513;" value="#8B4513">Marrom</option>
                                <option style="color:#1C1C1C;" value="#1C1C1C">Preto</option>
                                <option style="color:#436EEE;" value="#436EEE">Royal Blue</option>
                                <option style="color:#A020F0;" value="#A020F0">Roxo</option>
                                <option style="color:#40E0D0;" value="#40E0D0">Turquesa</option>
                                <option style="color:#228B22;" value="#228B22">Verde</option>
                                <option style="color:#8B0000;" value="#8B0000">Vermelho</option>
                                
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="btnCadEvento" class="btn btn-success" id="btnCadEvento">Cadastrar</button>
                    

                </form>

            </div>
        </div>
    </div>
</div>


                        <!-- Modal -->
                        <div class="modal fade" id="VisualModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="VisualModal">Visualisar evento</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <span id="msgViewEvento"></span>
                            </div>
                            <div class="modal-body">
                            <dl class="row">
                                <dt class="col-sm-3">ID:</dt>
                                <dd class="col-sm-9" id="visualisar_id"></dd>

                                <dt class="col-sm-3">Titulo:</dt>
                                <dd class="col-sm-9" id="visualisar_titulo"></dd>

                                <dt class="col-sm-3">Data:</dt>
                                <dd class="col-sm-9" id="visualisar_start"></dd>

                                <dt class="col-sm-3">Descrição:</dt>
                                <dd class="col-sm-9" id="visualisar_descricao"></dd>   
                                
                            </dl>

                            
                            
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

    <script src="./vendor/chartist/js/chartist.min.js"></script>

    <script src="./vendor/moment/moment.min.js"></script>
    <script src="./vendor/pg-calendar/js/pignose.calendar.min.js"></script>


    <script src="./js/dashboard/dashboard-2.js"></script>
    <!-- Vectormap -->
    <script src="./vendor/raphael/raphael.min.js"></script>
    <script src="./vendor/morris/morris.min.js"></script>


    <script src="./vendor/circle-progress/circle-progress.min.js"></script>
    <script src="./vendor/chart.js/Chart.bundle.min.js"></script>

    <script src="./vendor/gaugeJS/dist/gauge.min.js"></script>

    <!--  flot-chart js -->
    <script src="./vendor/flot/jquery.flot.js"></script>
    <script src="./vendor/flot/jquery.flot.resize.js"></script>

    <!-- Owl Carousel -->
    <script src="./vendor/owl-carousel/js/owl.carousel.min.js"></script>

    <!-- Counter Up -->
    <script src="./vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="./vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="./vendor/jquery.counterup/jquery.counterup.min.js"></script>


    <script src="./js/dashboard/dashboard-1.js"></script>


    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/locales/pt-br.min.js'></script>
    <script src="./fullcalendar-6.1.15/dist/index.global.min.js"> </script>
    <script src="pt-br.global.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="custom.js"> </script>


</body>

</html>