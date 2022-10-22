<?php
    //Connect DB - Local XAMPP MariaDB
    $dbHost = 'Localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'mercado_inteligente';
   
    $conexao = new mysqli($dbHost, $dbUsername,$dbPassword, $dbName);
    if($conexao->connect_errno){
      echo "Falha na conexão; (".$conexao->connect_errno.") ".$conexao->connect_error;
    }
?>