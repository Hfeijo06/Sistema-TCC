<?php

$con = mysqli_connect("localhost", "root", "", "sistema_tcc");

if (mysqli_connect_errno()) {
    echo "Falha ao se conectar ao MySQL: " . mysqli_connect_erro();
} else {
    mysqli_select_db($con, "sistema_tcc");
}

?>