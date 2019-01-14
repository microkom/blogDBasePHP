<?php 
require_once 'funciones.php';

//añadir Comentario a una entrada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	/*if(isset($_POST['guardarNuevoCommentario'])){
		$commentId = $_POST['commentId'];
		$idEntrada = $_POST['idEntrada'];
		$fechaHora = $_POST['fechaHora'];
		$autor = $_POST['autor'];
		$texto = $_POST['texto'];

		//trata los retornos de carro para que funcionen en html
		$texto = nl2br($texto);
		
		$newComment = new Comentario($commentId, $idEntrada, $fechaHora, $autor, $texto);

		$tempEntrada = $entradaArr->readEntrada($idEntrada);
		$tempEntrada->setComentario($newComment);
		$accion = "mostrarComentario";
		print "<script>alert(\"Comentario guardado correctamente\")</script>";

	}*/
	if(isset($_POST['guardarCommentarioEditado'])){
		$idComentario = $_POST['idComentario'];
		$idEntrada = $_POST['idEntrada'];
		$fechaHora = $_POST['fechaHora'];
		$texto = $_POST['texto'];

		$texto = nl2br($texto);

		$tempEntrada = $entradaArr->readEntrada($idEntrada);
		$comentarioArr = $tempEntrada->comentario;
		$objetoComentario = comentarioDeArray($comentarioArr,$idComentario);

		if($objetoComentario->idComentario === $idComentario){
			$objetoComentario->texto=$texto;
		}
		$accion = "mostrarComentario";
		print "<script>alert(\"Comentario guardado correctamente\")</script>";

	}
	$_SESSION['entradaArr'] = $entradaArr;

}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//Guardar entrada
	if(isset($_POST['guardar'])){
		$idEntrada = $_POST['idEntrada'];
		$fechaHora = $_POST['fechaHora'];
		$titulo = $_POST['titulo'];
		$texto = $_POST['texto'];

		$texto = nl2br($texto);
		$tempEntrada = $entradaArr->readEntrada($idEntrada);

		$tempEntrada->titulo=$titulo;
		$tempEntrada->texto=$texto;

		print "<script>alert(\"Entrada guardada correctamente\")</script>";
		$_SESSION['entradaArr'] = $entradaArr;
		$accion = "mostrar";
	}


}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['buscar'])){

		$busquedaStr = $_POST['datosBusqueda'];
		$tipo = $_POST['tipoBusqueda'];

		$busquedaArr = array();

		if($tipo == "any"){
			$busquedaArr = explode(' ',$busquedaStr);
			$idEntradaArr = $entradaArr->busqueda($busquedaArr,0);
		}else{
			$busquedaStr = trim($busquedaStr);
			$idEntradaArr = $entradaArr->busqueda($busquedaStr,1);
		}
		$accion = "mostrarBusqueda";
	}

}

if(isset($_GET['idEntrada']))
	$idEntrada = $_GET['idEntrada'];

if(isset($_GET['accion']))
	$accion = $_GET['accion'];

if(isset($_GET['year']))
	$year = $_GET['year'];

if(isset($_GET['month']))
	$month = $_GET['month'];

if(isset($_GET['idComentario']))
	$idComentario = $_GET['idComentario'];
?>


<!DOCTYPE html>
<html>

	<head>
		<?php require 'HTMLheadTag.inc.php'; ?>
		<title>
			Comentarios
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

							case 'todosLosComentarios':print todosLosComentarios();break;	
							default:print "No hay articulos para mostrar"."<script> window.setTimeout(function(){window.location.href = 'principal.php'; }, 700)</script>";;break;
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