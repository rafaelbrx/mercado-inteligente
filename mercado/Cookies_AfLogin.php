<?php 
ob_start();
session_start();
include("conexao.php");
  if(!isset($_SESSION["usuario"])){
    echo "Erro";
    exit();
    }
  $logged = $_SESSION["usuario"];

  $push = "SELECT * FROM lista_compras";
  $result = $conexao->query($push);
 
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title>Mercado Inteligente</title>
	<link rel="stylesheet" href="./styles/styleAfLogin.css">
	<link rel="stylesheet" href="./styles/fonts.css">
	<link rel="icon" href="./components/images/imgcart.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
	 
</head>
  <header>
   <?php
   echo "<h1>Bem vindo <u>$logged</u></h1>";  
   ?>
   <a href="logout.php">Sair</a>
  </header>

<body>
		<section class="greeting">
			<h2 class="title">Lista de Compras</h2>
		</section>

		<div class="m-5">
		<table class="table text-white table-bg">
		<thead>
			<tr>
			<th scope="col">Código</th>
			<th scope="col">Produto</th>
			<th scope="col">Valor</th>
			<th scope="col">...</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		while($bring = mysqli_fetch_assoc($result))
		{ 
			echo "<tr>";
			echo "<td>".$bring['codigo']."</td>";
			echo "<td>".$bring['produto']."</td>";
			echo "<td>".$bring['valor']."</td>";
			echo "<td>
				<a class='btn btn-sm btn-danger' href='delete.php?codigo=$bring[codigo]' title='Deletar'>
				<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
					<path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
				</svg></a>
			</td>";
			echo "</tr>";
			
		}
		 ?>
		</tbody>
		</table>
		</div>
	<h3>
<?php
$con = mysqli_connect($dbHost, $dbUsername,$dbPassword, $dbName);
 
 
$resultado = mysqli_query($con, "SELECT sum(valor) FROM lista_compras");
$linhas = mysqli_num_rows($resultado);


while($linhas = mysqli_fetch_array($resultado)){
 echo "Valor Total = " .$linhas['sum(valor)'].'<br/>';}
?>
</h3>

</body>
</html>