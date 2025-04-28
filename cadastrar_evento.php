<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao/banco.php';

// Receber os dados enviado pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


// Criar a QUERY cadastrar evento no banco de dados
$query_cad_event = "INSERT INTO tb_calendario (cal_titulo, cal_descricao, cal_cor, cal_data) VALUES (?, ?, ?, ?)";

// Prepara a QUERY
$cad_event = $con->prepare($query_cad_event);

// Substituir o link pelo valor
$cad_event->bind_param("ssss", $dados['cad_title'], $dados['cad_descricao'], $dados['cad_color'], $dados['cad_start']);;

// Verificar se consegui cadastrar corretamente
// Supondo que você já tenha preparado a consulta e vinculado os parâmetros
if ($cad_event->execute()) {
    $retorna = [
        'status' => true,
        'msg' => 'Evento cadastrado com sucesso!',
        'id' => $con->insert_id, // Usando insert_id para obter o ID do último registro inserido
        'title' => $dados['cad_title'], // Certifique-se de que o índice está correto
        'color' => $dados['cad_color'],
        'start' => $dados['cad_start'],
        'descricao' => $dados['cad_descricao']
    ];
} else {
    $retorna = [
        'status' => false,
        'msg' => 'Erro: Evento não cadastrado! ' . htmlspecialchars($stmt->error) // Inclui mensagem de erro
    ];
}
// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
