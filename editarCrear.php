<?php
require_once 'funciones.php';

$accion = false;

if(isset($_GET['idEntrada']))
	$idEntrada = $_GET['idEntrada'];

if(isset($_GET['accion']))
	$accion = $_GET['accion'];

$result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//Guardar entrada nueva
	if(isset($_POST['crearEntrada'])){

		$idEntrada = $_POST['id'];
		$texto = nl2br($_POST['texto']);
		
		$conexion = conectarDB();
		$sql = "INSERT INTO entrada (titulo, texto, fechaHora, idUsuario) VALUES (?,?,?,?)";
		$guardarEntrada = $conexion->prepare($sql);
		$guardarEntrada->bindParam(1, $_POST['titulo']);
		$guardarEntrada->bindParam(2, $texto);
		$guardarEntrada->bindParam(3, $_POST['fechaHora']);
		$guardarEntrada->bindParam(4, $_POST['usuario']);
		$res = $guardarEntrada->execute();

		if ($res){
			
			//Consultar cuál es el último id en la tabla entrada
			$sql = "SELECT `AUTO_INCREMENT` fROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'blogdwes' AND   TABLE_NAME   = 'entrada';";
			$consulta =  $conexion->prepare($sql);
			$consulta->execute();
			$res2 = $consulta->fetch();
			$idEntrada = $res2['AUTO_INCREMENT']-1;
			$accion = "mostrar";
		}
		else
			header('location:index.php');
		$sql = null;
		$conextion = null;
	}
}

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<?php require 'HTMLheadTag.inc.php'; ?>
		<title>Editar y Crear Entradas</title>
	</head>
	<body>
		<?php include "HTMLheader.inc.php";?>
		<?php include "HTMLsearch.inc.php";?>
		<section class="main">
			<section class="principal">
				<article>
					<?php

					if($accion !== false){
						switch($accion){
							case 'crear': print crearEntrada();break; 
							case 'mostrar': print imprimirEntrada($idEntrada);break;
							default:print "No hay articulos para mostrar";break;
						}
					}else{
						header('Location: login.php ');
					}
					?>
				</article>
			</section>                
			<?php require_once 'HTMLarchive.inc.php';?>       
		</section>   
		<?php include "HTMLfooter.inc.php";?>

	</body>
</html>