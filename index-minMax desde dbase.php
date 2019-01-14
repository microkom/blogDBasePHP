<?php 
require_once 'AllClasses.php';
require_once 'funciones.php';


//realizar conexión
$conexion = conectarDB();


//consulta el id más bajo y el más alto de la base de datos.
$sql =  'SELECT MIN(id) as min, MAX(id) as max fROM entrada  ';
$consulta  = $conexion->prepare($sql);
$res = $consulta->execute();
$registro = $consulta->fetch(PDO::FETCH_ASSOC);
$_SESSION['idMin'] = $registro['min'];
$_SESSION['idMax'] = $registro['max'];


$sql = null;
$conextion = null;



//intervalo de paginacion
$_SESSION['interv'] = 3;

if(!isset($_SESSION['currentId'])){
	$_SESSION['currentId'] = $_SESSION['idMax'];
}
if(!isset($_SESSION['start'])){
	$_SESSION['start'] = $_SESSION['idMax'];
}

//selecciona la entrada según se presionen los botones de paginación
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$_SESSION['resto'] = $_SESSION['idMax'] % $_SESSION['interv'];

	if(isset($_POST['prev'])){
		if($_SESSION['currentId']-$_SESSION['interv'] > 0){
			$_SESSION['currentId']  = $_SESSION['currentId']- $_SESSION['interv'];
			$_SESSION['start']= $_SESSION['currentId'];
		}else{
			$_SESSION['currentId'] = 1;
			$_SESSION['interv'] = 0;
			$_SESSION['start'] = $_SESSION['resto'];
		}
	}
	if(isset($_POST['next'])){
		if($_SESSION['currentId']+$_SESSION['interv'] <= $_SESSION['idMax'] ){
			$_SESSION['currentId']  = $_SESSION['currentId']+ $_SESSION['interv'];
			$_SESSION['start'] = $_SESSION['currentId'];
		}
	}

}

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<?php require 'HTMLheadTag.inc.php'; ?>
		<title>German Navarro Blog</title>
	</head>
	<body> 
		<?php include "HTMLheader.inc.php";?>
		<?php include "HTMLsearch.inc.php";?>

		<section class="main">
			<section class="principal">


				<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

					<input type="submit" name="prev" value="&laquo;" class="pagina" <?php if($_SESSION['currentId']==1) print "disabled";?>>
					<input type="submit" name="next" value="&raquo;"  class="pagina" >

				</form>

				<?php

				//muestra 5 entradas por pantalla
				for($i=$_SESSION['start']; $i>$_SESSION['currentId']-$_SESSION['interv']; $i--){
					if($i!==0){
						//crea objeto del tipo entrada para mostrarlo por pantalla
						$entrada = readUnaEntradaDB($i);
						print $entrada->imprimirEntradaResumida(); 
					}
				}

				?>

			</section>                
			<?php include 'HTMLarchive.inc.php';?>       
		</section>


		<?php include "HTMLfooter.inc.php";?>


	</body>
</html>