<?php 
require_once 'AllClasses.php';
require_once 'funciones.php';


//indice de la paginación
if(!isset($_SESSION['id'])){
	$_SESSION['id']=0;
}

$activo = array();
//mostrar como activo el link actual. reset a cero
for($i=1;$i<=5;$i++){
	$activo[$i] = "";
}

//realizar conexión
$conexion = conectarDB();

//consulta de las 5 últimas entradas del blog.
$sql =  'SELECT id FROM entrada   ORDER BY  id DESC LIMIT 5;';
$consulta  = $conexion->prepare($sql);
$consulta->execute();

$idArr = array();
while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
	$idArr[] = $registro['id']; 
}						

$sql = null;
$conextion = null;

//para ocultar los botones que si hay menos de 5 entradas
$_SESSION['page1']= "hidden";
$_SESSION['page2']= "hidden";
$_SESSION['page3']= "hidden";
$_SESSION['page4']= "hidden";
$_SESSION['page5']= "hidden";

switch(sizeof($idArr)){
	case 5: $_SESSION['page5']= "visible"; 
	case 4: $_SESSION['page4']= "visible"; 
	case 3: $_SESSION['page3']= "visible"; 
	case 2: $_SESSION['page2']= "visible"; 
	case 1: $_SESSION['page1']= "visible"; 
}

$_SESSION['idArr'] = $idArr;


//selecciona la entrada según se presionen los botones de paginación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$idIndex = $_SESSION['id'];

	if(isset($_POST['id'])){
		$idIndex =  $_POST['id'];
		$idIndex -= 1;
	}
	if(isset($_POST['prev'])){
		if($idIndex > 0){
			$idIndex -=1;
		}
	}
	if(isset($_POST['next'])){
		if($idIndex < sizeof($idArr)-1){
			$idIndex +=1;
		}
	}
	$_SESSION['id'] = $idIndex;


}
//mostrar como activo el boton de link actual
$activo[1+$_SESSION['id']]= " activo" ;

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

				<div class="center">
					<div class="pagination">
						<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

							<input type="submit" name="prev" value="&laquo;" class="pagina">
							<input type="submit" name="id" value="1" class=" pagina <?php print $_SESSION['page1']; print $activo[1]; ?>">
							<input type="submit" name="id" value="2" class=" pagina <?php print $_SESSION['page2']; print $activo[2]; ?>">
							<input type="submit" name="id" value="3" class=" pagina <?php print $_SESSION['page3']; print $activo[3]; ?>">
							<input type="submit" name="id" value="4" class=" pagina <?php print $_SESSION['page4']; print $activo[4]; ?>">
							<input type="submit" name="id" value="5" class=" pagina <?php print $_SESSION['page5']; print $activo[5]; ?>">
							<input type="submit" name="next" value="&raquo;"  class="pagina" >

						</form>
					</div>
				</div>
				<?php

				$idArr = $_SESSION['idArr'];

				$entrada = readUnaEntradaDB($idArr[$_SESSION['id']]);
				print "<br><br>".$entrada->imprimirEntradaResumida(); 

				?>

			</section>                
			<?php include 'HTMLarchive.inc.php';?>       
		</section>

		<?php include "HTMLfooter.inc.php";?>


	</body>
</html>