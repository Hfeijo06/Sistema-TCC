<?php
// Definindo um cookie antes de qualquer saída HTML
if (isset($_POST['txt_login'])) {
    // Se o checkbox 'remember' estiver marcado
    if (isset($_POST['remember'])) {
        // Definir um cookie por 30 dias
        setcookie('login', $_POST['txt_login'], time() + (30 * 24 * 60 * 60), "/");
    } else {
        // Remover o cookie se o checkbox não estiver marcado
        setcookie('login', '', time() - 3600, "/"); // Expira imediatamente
    }
}

session_start();
require_once("conexao/banco.php");


// Verifica se os campos foram enviados
if (isset($_POST['txt_login']) && isset($_POST['txt_senha'])) {
    $login = $_POST['txt_login'];
    $senha = $_POST['txt_senha'];

    // Query para buscar o usuário no banco de dados
    $query = "SELECT log_usuario, log_senha FROM tb_login WHERE log_usuario = ?";
    $stmt = $con->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se encontrou o usuário
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verifica se a senha inserida corresponde à armazenada
            if ($senha === $row['log_senha']) {
                $_SESSION["login"] = $login;

                // Retorna sucesso em JSON
                echo json_encode(['success' => true]);
            } else {
                // Senha incorreta
                echo json_encode(['success' => false, 'error' => 'senha_usuario']);
            }
        } else {
            // Usuário não encontrado
            echo json_encode(['success' => false, 'error' => 'senha_usuario']);
        }
    } else {
        // Erro ao preparar a consulta
        echo json_encode(['success' => false, 'error' => 'Erro ao preparar a consulta: ' . $con->error]);
    }
} else {
    // Campos faltando
    echo json_encode(['success' => false, 'error' => 'campos_faltando']);
}

?>
    