<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <!-- Adicionando Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .form-group label {
            font-weight: normal;
            color: #6c757d;
        }
        .btn-primary {
            background-color: #6c4ae2;
            border-color: #6c4ae2;
            width: 100%;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #563bb2;
            border-color: #4a2e9a;
        }
        .text-center a {
            color: #6c4ae2;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        // Verificar se o token é válido e não expirou
        $conn = new mysqli("localhost", "root", "", "sistema_tcc");

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM tb_login WHERE log_token = ? AND log_token_expira > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token válido, exibir o formulário para definir a nova senha
            echo '
            <h2 class="text-center mb-4">Redefinir Senha</h2>
            <form id="reset-form" action="processa_redefinir_senha.php" method="POST">
                <input type="hidden" name="token" value="'.$token.'">
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" class="form-control" name="nova_senha" id="senha1" required>
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Digite Novamente</label>
                    <input type="password" class="form-control" id="senha2" required>
                    <div id="senha-error" class="text-danger mt-2" style="display: none;">As senhas não correspondem.</div>
                </div>
                <button type="submit" class="btn btn-primary" id="btn">Redefinir Senha</button>
            </form>';
        } else {
            echo "<div class='alert alert-danger'>Token inválido ou expirado.</div>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>

<script>
    document.getElementById('reset-form').addEventListener('submit', function(event) {
        let senha1 = document.getElementById('senha1').value;
        let senha2 = document.getElementById('senha2').value;

        if (senha1 !== senha2) {
            event.preventDefault(); // Impede o envio do formulário
            document.getElementById('senha-error').style.display = 'block'; // Exibe mensagem de erro
        } else {
            document.getElementById('senha-error').style.display = 'none'; // Oculta mensagem de erro
        }
    });
</script>

<!-- Adicionando Bootstrap JS e dependências -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
