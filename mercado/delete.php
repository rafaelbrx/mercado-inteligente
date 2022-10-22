<?php

if(!empty($_GET['codigo']))
{
    include('conexao.php');

    $codigo = $_GET['codigo'];

    $sqlSelect = "SELECT * FROM lista_compras WHERE codigo=$codigo";

    $result = $conexao->query($sqlSelect);

    if($result->num_rows > 0)
    {
        $sqlDelete = "DELETE FROM lista_compras WHERE codigo=$codigo";
        $resultDelete = $conexao->query($sqlDelete);
    }
}
header('Location: Cookies_AfLogin.php');
?>