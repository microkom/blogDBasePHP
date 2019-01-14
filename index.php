<?php 
require_once 'AllClasses.php';
require_once 'funciones.php';

if(!isset($_SESSION['id']))
	$_SESSION['id']=0;

//realizar conexión
$conexion = conectarDB();

//consulta de las 3 últimas entradas del blog.
$sql =  'SELECT id FROM entrada ORDER BY id DESC ;';
$consulta  = $conexion->prepare($sql);
$consulta->execute();

$idArr = array();

while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
	$idArr[] = $registro['id'];
}

$sql = null;
$conextion = null;

$j = 0;
$h = 0;
$group = array();
$INTERVAL = 4;

//agrupa arrays de 5 entradas para paginarlas
for($i=0; $i<count($idArr); $i++){
	//var_dump($group);
	$group[$j][$h] = $idArr[$i];
	if($h === $INTERVAL){
		$j++;
		$h=0;
	}
	$h++;
}

//numero de grupos
$_SESSION['groupSize'] = count($group);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$idIndex = $_SESSION['id'];

	//cambia de registro hacia atras
	if(isset($_POST['prev'])){
		if($idIndex > 0){
			$idIndex -=1;
		}
	}
	
	//cambia de registro hacia adelante
	if(isset($_POST['next'])){
		if($idIndex < sizeof($group)-1){
			$idIndex +=1;
		}
	}
	$_SESSION['id'] = $idIndex;


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
			<section class="principal center">

			<!--Botones para cambiar de pagina -->
				<form action="<?php print $_SERVER['PHP_SELF'] ?>" method="post">

					<!--Boton izquierda-->
					<input type="submit" name="prev" value="&laquo;" class="pagina" <?php if($_SESSION['id']==0) print "disabled";?>>
				
					<!--Boton derecha-->
					<input type="submit" name="next" value="&raquo;" class="pagina" <?php if($_SESSION['id'] == $_SESSION['groupSize']-1) print "disabled";?> >

				</form>
				<br>
				<?php

				//Muestra por pantalla grupos de entradas
				foreach($group[$_SESSION['id']] as $id){
					
					//crea objeto del tipo entrada 
					$entrada = readUnaEntradaDB($id);
					print $entrada->imprimirEntradaResumida(); 
				} 
				?>

			</section>                
			<?php include 'HTMLarchive.inc.php';?>       
		</section>


		<?php include "HTMLfooter.inc.php";?>


	</body>
</html>