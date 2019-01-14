<?php 
require_once 'funciones.php';

$accion = false;


//añadir Comentario a una entrada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['guardarNuevoCommentario'])){

		$idEntrada = $_POST['idEntrada'];
		/*$idUsuario = consultaIdUsuarioDBporNick($_POST['autor']);*/

		$conexion = conectarDB();
		$sql = "INSERT INTO comentario (texto, idUsuario, idEntrada, fechaHora) VALUES (?,?,?,?)";
		$guardarEntrada = $conexion->prepare($sql);
		$guardarEntrada->bindParam(1, nl2br($_POST['texto']));
		$guardarEntrada->bindParam(2,  consultaIdUsuarioDBporNick($_POST['autor'])); 
		$guardarEntrada->bindParam(3, $_POST['idEntrada']); 
		$guardarEntrada->bindParam(4, $_POST['fechaHora']); 
		$res = $guardarEntrada->execute();

		$sql = null;
		$conextion = null;

		if ($res){
			//redirección a otra página
			header('Location: principal.php?idEntrada='.$idEntrada.'&accion=mostrarComentario');
		}
		else
			$accion = "mostrarComentario";
	}

	if(isset($_POST['guardarCommentarioEditado'])){
		$idComentario = $_POST['idComentario'];
		$idEntrada = $_POST['idEntrada'];

		$conexion = conectarDB();
		$sql = "UPDATE comentario SET texto=? WHERE id=?";
		$guardar = $conexion->prepare($sql);
		$guardar->bindParam(1, nl2br($_POST['texto'])); 
		$guardar->bindParam(2, $_POST['idComentario']); 
		$res = $guardar->execute();
		$sql = null;
		$conextion = null;

		if ($res){
			//redirección a otra página
			header('Location: principal.php?idEntrada='.$idEntrada.'&accion=mostrarComentario');
		}
		else
			$accion = "mostrarComentario";
	}

	//Guardar entrada
	if(isset($_POST['guardar'])){
		$idEntrada = $_POST['idEntrada'];

		$conexion = conectarDB();
		$sql = "UPDATE entrada SET titulo=?, texto=? WHERE id=?";
		$guardarEntrada = $conexion->prepare($sql);
		$guardarEntrada->bindParam(1, $_POST['titulo']);
		$guardarEntrada->bindParam(2, nl2br($_POST['texto'])); 
		$guardarEntrada->bindParam(3, $_POST['idEntrada']); 
		$res = $guardarEntrada->execute();
		$sql = null;
		$conextion = null;

		if ($res){
			//redirección a otra página
			header('Location: principal.php?idEntrada='.$idEntrada.'&accion=mostrar');
		}
		else
			$accion = "mostrar";
	}

	//realizar búsqueda
	if(isset($_POST['buscar'])){

		$busquedaStr = $_POST['datosBusqueda'];
		$tipo = $_POST['tipoBusqueda'];

		$busquedaArr = array();

		if($tipo == "any"){
			$idEntradaArr = busqueda(trim($busquedaStr),0);
		}else{
			$idEntradaArr = busqueda(trim($busquedaStr),1);
		}
		$accion = "mostrarBusqueda";
	}

}

if(isset($_GET['idEntrada']))
	$idEntrada = $_GET['idEntrada'];


if(isset($_GET['year']))
	$year = $_GET['year'];

if(isset($_GET['month']))
	$month = $_GET['month'];

if(isset($_GET['idComentario']))
	$idComentario = $_GET['idComentario'];

if(isset($_GET['idUsuario']))
	$idUsuario = $_GET['idUsuario'];

if(isset($_GET['accion']))
	$accion = $_GET['accion'];


?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<?php require 'HTMLheadTag.inc.php'; ?>
		<title>
			Pagina principal
		</title>
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

							case 'mostrarBusqueda': print mostrarBusqueda($idEntradaArr);break;
							case 'mostrar':	print imprimirEntrada($idEntrada);break;
							case 'todosLasEntradas': print todosLasEntradas();break;
							case 'editarEntrada':  print  editarEntrada( $idEntrada);break;
							case 'borrarEntradaWarning': print borrarEntradaWarning($idEntrada) ;break;
							case 'borrarEntrada': print borrarEntrada($idEntrada)."
							<script> window.setTimeout(function(){window.location.href = 'index.php'; }, 700)</script>";break;
							case 'borrarComentarioWarning': print borrarComentarioWarning($idEntrada, $idComentario); break;
							case 'borrarComentario':  print borrarComentario($idComentario)."
							<script> window.setTimeout(function(){window.location.href = 'principal.php?idEntrada=$idEntrada&accion=mostrar'; }, 700)</script>";break;
							case 'comentar': print comentar($idEntrada);break;
							case 'editarComentario': print editarComentario($idEntrada, $idComentario);break;
							case 'mostrarComentario': print comentarios($idEntrada);break;
							case 'archivo': print mostrarArchivo($year,$month);break;
							case 'favoritoQuitar': favoritoQuitar($idEntrada,$idUsuario);break;
							case 'favoritoGuardar': favoritoGuardar($idEntrada,$idUsuario);break;


							default:print "No hay articulos para mostrar";break;
						}
					}

					?>
				</article>
			</section>                
			<?php require_once 'HTMLarchive.inc.php';?>       
		</section>  
		<?php include "HTMLfooter.inc.php";?>
	</body>
</html>