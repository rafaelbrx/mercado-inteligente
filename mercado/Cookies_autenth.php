<?php
ob_start();
?>
<html>
    <head>
        <title>Mercado Inteligente</title>
    </head>
    <body>
        <?php
           include("conexao.php");

           $email = $_POST["email"];
           $senha = $_POST["senha"];

           $sql = "SELECT usuario FROM cadastro WHERE email = '$email' AND senha = '$senha'";
           $resultado = mysqli_query($conexao, $sql);
           $linhas = mysqli_affected_rows($conexao);

           if($linhas>0){
            session_start();
            $_SESSION["usuario"] = $email;
            header("location: Cookies_AfLogin.php");
           }
           else{
            echo "Dados incorretos";
           }
        ?>
    </body>
</html>