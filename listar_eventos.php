<?php 


$con = mysqli_connect("localhost", "root", "", "sistema_tcc");

if (mysqli_connect_errno()) {
    echo "Falha ao se conectar ao MySQL: " . mysqli_connect_erro();
} else {
    mysqli_select_db($con, "sistema_tcc");
}


$eventos = "SELECT cal_id, cal_cor, cal_titulo, cal_descricao, cal_data FROM tb_calendario";


$result_events = $con->prepare($eventos);

$result_events->execute();
$result = $result_events->get_result();

$table = [];

// Verifica se $result_events é válido
if ($result_events) {
    while ($row_events = $result-> fetch_assoc()) {
        // Extrai as variáveis do array
        extract($row_events);

        // Adiciona os dados ao array $table
        $table[] = [
            'id' => $cal_id,
            'title' => $cal_titulo,
            'descricao' => $cal_descricao,
            'start' => $cal_data,
            'color' => $cal_cor    
        ];
    }
} else {
    // Lida com o erro de consulta, se necessário
    echo "Erro ao executar a consulta.";
}
echo json_encode($table)
?>