<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistema - Neo Emigma</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">
    <!-- Inclua o CSS do Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">




</head>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const loginField = document.getElementById('txt_login');
        const senhaField = document.getElementById('txt_senha');
        
        const loginError = document.getElementById('loginError'); // Seleciona o span de erro do login
        const senhaError = document.getElementById('senhaError'); // Seleciona o span de erro da senha

        // Escuta o evento de envio do formulário
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão de recarregar a página

            // Limpa mensagens de erro anteriores
            loginError.style.display = 'none';
            senhaError.style.display = 'none';
            loginField.classList.remove('error');
            senhaField.classList.remove('error');

            // Pega os valores dos campos
            const loginValue = loginField.value.trim();
            const senhaValue = senhaField.value.trim();

            // Validações básicas
            if (!loginValue || !senhaValue) {
                senhaError.textContent = 'Por favor, preencha todos os campos.';
                senhaError.style.display = 'block';
                loginField.classList.add('error');
                senhaField.classList.add('error');
                return; // Para a execução se os campos estiverem vazios
            }

            // Monta os dados do formulário
            const formData = new FormData(form);

            // Envio do formulário via fetch (AJAX)
            fetch('validacao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Supondo que o servidor retorna JSON
            .then(data => {
                if (data.success) {
                    // Se o login for bem-sucedido, redireciona ou faz algo
                    window.location.href = 'backup.php'; // Exemplo de redirecionamento
                } else {
                    // Se houver erros, exibe as mensagens de erro
                    if (data.error === 'senha_usuario') {
                        senhaError.textContent = 'Usuário ou senha incorreta. Por favor, tente novamente.';
                        senhaError.style.display = 'block';
                        senhaField.classList.add('error');
                        loginError.style.display = 'block';
                        loginField.classList.add('error');
                    } else if (data.error === 'usuario_nao_encontrado') {
                        loginError.textContent = 'Usuário não encontrado. Verifique o nome de usuário.';
                        loginError.style.display = 'block';
                        loginField.classList.add('error');
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao enviar o formulário:', error);
            });
        });
    });
    // Função para criar um cookie
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // Função para ler um cookie
    function getCookie(name) {
        const cname = name + "=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(cname) === 0) {
                return c.substring(cname.length, c.length);
            }
        }
        return "";
    }

    document.addEventListener('DOMContentLoaded', function() {
        const loginField = document.getElementById('txt_login');

        // Verifica se o nome de usuário está salvo no cookie ao carregar a página
        const savedLogin = getCookie("login");
        if (savedLogin !== "") {
            document.getElementById("txt_login").value = savedLogin;
            document.getElementById("basic_checkbox_1").checked = true; // Marca o checkbox
        }
    });
</script>
  

<style>
    input.error {
        border-color: red; /* Borda vermelha no campo com erro */
    }
    span.error-message {
        color: red;
        display: none;
    }
    
</style>


<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sistema Édi Gás e Água - Acesso</h4>
                                    <form action="validacao.php" method="POST" id="loginForm">
                                        <div class="form-group">
                                            <label for="login"><strong>Usuário</strong></label>
                                            <input type="text" name="txt_login" id="txt_login" class="form-control" placeholder="Digite Seu Usuário">
                                            <span id="loginError" style="color:red; display:none;"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="senha"><strong>Senha</strong></label>
                                            <input type="password" name="txt_senha" id="txt_senha" class="form-control" placeholder="Digite Sua Senha">
                                            <span id="senhaError" style="color:red; display:none;"></span>
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="form-check ml-2">
                                                    <!-- Agora o checkbox tem o name="remember" -->
                                                    <input class="form-check-input" type="checkbox" name="remember" id="basic_checkbox_1">
                                                    <label class="form-check-label" for="basic_checkbox_1">Lembrar usuário</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="esqueceu_senha.html">Esqueceu a Senha?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Acessar</button>
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
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/plugins-init/sweetalert.init.js"></script>
    <!-- Inclua o JavaScript do Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>

</html>