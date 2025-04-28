<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <!-- Inclua o CSS e JS do SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .swal2-popup {
            font-size: 1.2em !important; /* Tamanho da fonte */
            font-weight: normal !important; /* Peso normal */
            font-family: Arial, sans-serif !important; /* Família de fontes Arial */
        }
    </style>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];

    // Verificar se a nova senha foi fornecida
    if (empty($nova_senha)) {
        die("Nova senha não fornecida.");
    }

    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "sistema_tcc");

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Verificar se o token é válido
    $sql = "SELECT log_codigo FROM tb_login WHERE log_token = ? AND log_token_expira > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token válido, atualizar a senha
        $row = $result->fetch_assoc();
        $log_codigo = $row['log_codigo'];

        // Hash da nova senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualizar a senha no banco de dados
        $sql = "UPDATE tb_login SET log_senha = ?, log_token = NULL, log_token_expira = NULL WHERE log_codigo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nova_senha, $log_codigo);
        if ($stmt->execute()) {
            // Usando SweetAlert2 para mostrar mensagem de confirmação e redirecionar
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Senha redefinida com sucesso! Você será redirecionado para a tela de login.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php'; 
                    }
                });
            </script>";
        } else {
            echo "Erro ao atualizar a senha: " . $stmt->error;
        }
    } else {
        echo "Token inválido ou expirado.";
    }

    $stmt->close();
    $conn->close();
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

