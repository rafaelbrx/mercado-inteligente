<?php
ob_start();


  $produtos = $_GET["codbarras"];

    //Info about database (Local DB - XAMPP MariaDB)
    $dbHost = 'Localhost'; 
    $dbUsername = 'root';  
    $dbPassword = ''; 
    $dbName = 'mercado_inteligente'; 

    $con = mysqli_connect("$dbHost","$dbUsername","$dbPassword","$dbName");

if (mysqli_connect_errno()) {
  echo "Falha ao conectar com o Banco de Dados: " . mysqli_connect_error();
  exit();
}

if ($result = mysqli_query($con, "SELECT * FROM estoque WHERE codigo=$produtos")) {

 
  while($row = mysqli_fetch_assoc($result)) {
     $cod =  $row["codigo"];
    $pro = $row["produto"];
    $val = $row["valor"];
    echo "codigo: " . $row["codigo"]. " - cod: " . $row["produto"]. " " . $row["valor"]. "<br>";
  }  
  
  $sql = "INSERT INTO lista_compras (codigo, produto, valor)
    VALUES ('$cod', '$pro', '$val')";
    
  
    if (mysqli_query($con, $sql)) {
    } else {
    }
  
  mysqli_free_result($result);
}

mysqli_close($con);
?>