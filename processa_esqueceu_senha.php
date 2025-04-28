<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function enviarEmail($destinatario, $assunto, $corpo) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'henriquefeijo02@gmail.com';
        $mail->Password   = 'nipv oftv gpjn alvb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Configurações do remetente e destinatário
        $mail->setFrom('henriquefeijo02@gmail.com', 'Redefinir Senha');
        $mail->addAddress($destinatario);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpo;
        $mail->AltBody = strip_tags($corpo);

        $mail->send();
    } catch (Exception $e) {
        echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
    }
}

// Conexão com banco de dados
$conn = new mysqli("localhost", "root", "", "sistema_tcc");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificar se o e-mail existe
    $sql = "SELECT * FROM tb_login WHERE log_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Gerar token e data de expiração
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Atualizar banco de dados com token e expiração
        $sql = "UPDATE tb_login SET log_token = ?, log_token_expira = ? WHERE log_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expira, $email);
        $stmt->execute();

        // Definir o assunto e o corpo do e-mail
        $assunto = "Redefinir Senha";
        $url = "http://localhost/sistema/redefinir_senha.php?token=" . $token;
        $corpo = "Clique no link para redefinir sua senha: <a href='$url'>$url</a>";

        // Usar PHPMailer para enviar o e-mail
        enviarEmail($email, $assunto, $corpo);

        // Exibir SweetAlert de sucesso
        echo "Por favor verifique seu email e feche a página!";
    } else {
        // Redireciona para "esqueceu_senha.html" com status de erro
        header("Location: esqueceu_senha.html?status=error");
    }
    exit;
}
?>
