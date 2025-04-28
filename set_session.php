<?php
// Inicia a sessão
session_start();

// Define a variável de sessão para exibir a notificação
$_SESSION['showToast'] = true;

// Redireciona para a página principal
header("Location: principal.php"); // Substitua com a URL correta
exit();
?>
