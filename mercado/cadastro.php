<?php
    include("conexao.php");

    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "INSERT INTO cadastro(usuario, email, senha) VALUES('$usuario', '$email', '$senha')";
    if(mysqli_query($conexao, $sql)){
        echo "Usuário cadastrado. Redirecione ao login.";
    }
    else{
        echo "Erro" .mysqli_connect_error($conexao);
    }
    mysqli_close($conexao);

?>