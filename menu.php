<style>
/* CSS para estilizar o botão */
#btnBackup {
            background-color: #4CAF50; /* Verde */
            color: white;
            border: none;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 25px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s;
        }

        #btnBackup:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
</style>

<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <a href="principal.php" class="brand-logo">
        <img class="logo-abbr" src="./images/logobranca.png" alt="">
        <img class="brand-title" src="./images/edigaseagua.png" alt="">
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

<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            <li class="nav-label first">Painel Geral</li>
            <!-- Dashboard -->
            <li>
                <a href="principal.php" aria-expanded="false">
                    <i class="bi bi-grid"></i><span class="nav-text">Resumo</span>
                </a>
            </li>

            <!-- Transações -->
            <li class="nav-label">Operações</li>
            <li>
                <a href="consulta_cliente.php" aria-expanded="false">
                    <i class="bi bi-person-lines-fill"></i><span class="nav-text">Clientes</span>
                </a>
            </li>
            <li>
                <a href="consulta_venda.php" aria-expanded="false">
                    <i class="bi bi-bag-check-fill"></i><span class="nav-text">Vendas</span>
                </a>
            </li>
            <li>
                <a href="consulta_fornecedor.php" aria-expanded="false">
                    <i class="bi bi-truck"></i><span class="nav-text">Fornecedores</span>
                </a>
            </li>
            <li>
                <a href="consulta_compra.php" aria-expanded="false">
                    <i class="bi bi-cart-fill"></i><span class="nav-text">Compras</span>
                </a>
            </li>

            <!-- Financeiro -->
            <li class="nav-label">Financeiro</li>
            <li>
                <a href="consulta_receber.php" aria-expanded="false">
                    <i class="bi bi-wallet2"></i><span class="nav-text">Recebimentos</span>
                </a>
            </li>
            <li>
                <a href="consulta_pagar.php" aria-expanded="false">
                    <i class="bi bi-cash"></i><span class="nav-text">Pagamentos</span>
                </a>
            </li>
            <li>
                <a href="consulta_fluxo.php" aria-expanded="false">
                    <i class="bi bi-currency-exchange"></i><span class="nav-text">Fluxo de Caixa</span>
                </a>
            </li>

            <!-- Gerenciamento de Produtos -->
            <li class="nav-label">Estoque</li>
            <li>
                <a href="consulta_produto.php" aria-expanded="false">
                    <i class="bi bi-box-seam"></i><span class="nav-text">Produtos</span>
                </a>
            </li>
            <li>
                <a href="consulta_estoque.php" aria-expanded="false">
                    <i class="bi bi-archive-fill"></i><span class="nav-text">Estoque Atual</span>
                </a>
            </li>

            <!-- Usuários -->
            <li class="nav-label">Configurações</li>
            <li>
                <a href="consulta_login.php" aria-expanded="false">
                    <i class="bi bi-people-fill"></i><span class="nav-text">Usuários</span>
                </a>
            </li>
            <li>
                <button id="btnBackup" class="btn btn-primary">Realizar Backup</button>
            </li>
            <script>
                document.getElementById('btnBackup').addEventListener('click', function () {
                    Swal.fire({
                        title: 'Você tem certeza?',
                        text: "O backup irá baixar uma cópia do banco de dados!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, realizar backup!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona para o script de backup
                            window.location.href = 'executar_backup.php';
                        }
                    });
                });
            </script>
        </ul>
    </div>
</div>
