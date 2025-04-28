<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'sistema_tcc';

// Define o nome do arquivo de backup que será baixado
$backup_file = $banco . '_' . date('Y-m-d_H-i-s') . '.sql';

// Cabeçalhos para download do arquivo diretamente no navegador
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $backup_file . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Comando para exportar o banco de dados e enviar diretamente para a saída padrão (stdout)
$comando = "mysqldump --user=$usuario --password=$senha --host=$host $banco";

// Executa o comando e envia a saída diretamente para o navegador
passthru($comando);

// Encerra o script
exit;
?>
