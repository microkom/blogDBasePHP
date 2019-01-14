<?php 
require 'AllClasses.php';
require 'funciones.php';

if(isset($_SESSION['usuario'])){
	$usuario = $_SESSION['usuario'];
	$_SESSION['errorLogin'] = 0;
}

$errorNombre =$errorUsuario = $errorPass01 = $errorPass02 = $show = null;

//captura datos del formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty($_POST['usuario'])){
		$errorUsuario = "<br>* Debes escribir el usuario";
	}else{
		$usuario = trim($_POST['usuario']);
		if (!preg_match("/^[a-zA-Z0-9]*$/",$usuario)) {
			$errorUsuario = "<br>*Sólo letras y números sin espacios"; 
		}
	}
	if (empty($_POST['nombre'])){
		$errorNombre = "<br>* Debes escribir el nombre";
	}else{
		$nombre = trim($_POST['nombre']);
		if (!preg_match("/^[a-zA-Z ]*$/",$nombre)) {
			$errorNombre = "<br>*Sólo letras "; 
		}
	}
	if (empty($_POST['contrasena'])){
		$errorPass01 = "<br>* Debes escribir la contraseña";
	}else{
		$pass01 = $_POST['contrasena'];
	}	
	if (empty($_POST['contrasena2'])){
		$errorPass02 = "<br>* Debes escribir la contraseña 2";
	}else{
		$pass02 = $_POST['contrasena2'];
		//Comprueba si las contraseñas son iguales
		if (strcmp($pass01,$pass02) !== 0) {
			unset($pass01);unset($pass02);
			$errorPass02 = "<br> * Las contraseñas no coinciden"; 
		}
	}   

	if($_SESSION['randomWord'] === $_POST['captcha']){
		$captcha = true;		 
	}else{
		$captcha = false;
	}
}


$show2 = "hidden";

//Comprobar que todas los campos tienen datos para validar el registro
if($_SERVER['REQUEST_METHOD']== 'POST'){
	unset($yaExisteUsuario);
	if(isset($usuario)  && isset($pass01)  && isset($pass02) && ($captcha==true) ){

		$conexion = conectarDB();

		//comprueba si el usuario existe
		$sql = "SELECT id FROM usuario WHERE usuario=?";
		$consulta = $conexion->prepare($sql);
		$consulta->bindParam(1,$usuario);
		$consulta->execute();

		$res = $consulta->rowCount();
		if($res >0){
			$yaExisteUsuario = "<h3>El usuario ya existe</h3>";
		}else{

			//si el usuario no existe registra datos en la base datos
			$pass = password_hash($pass01, PASSWORD_BCRYPT );
			$sql = "INSERT INTO usuario (nombre, usuario, pass) VALUES (?,?,?)";
			$consulta = $conexion->prepare($sql);
			$consulta->bindParam(1,$nombre);
			$consulta->bindParam(2,$usuario);
			$consulta->bindParam(3,$pass);
			$consulta->execute();

			$res = $consulta->rowCount();

			if($res > 0)
				$_SESSION['usuario'] = $usuario;
			else
				header('location: registro.php');

			$show = "hidden";
			$show2 = "visible";
		}
	}else{
		$show = "visible";
	}
	$conexion = null;
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<?php require 'HTMLheadTag.inc.php'; ?>
		<title>
			Registro
		</title>
		<link rel="stylesheet" type="text/css" href="style.css" />

	</head>
	<body>
		<?php include_once "HTMLheader.inc.php";?>
		<?php include_once "HTMLsearch.inc.php";?>

		<section class="main">    
			<section class="principal">
				<h1 class="center">FORMULARIO DE REGISTRO</h1>
				<?php if(isset($yaExisteUsuario)) print '<span class="error">'. $yaExisteUsuario.'</span>'?>

				<div id="registroOk" class="<?= $show2?> center">REGISTRO REALIZADO CON EXITO</div>

				<div id="form" class="<?= $show?>">

					<form class="front" action="<?=$_SERVER["PHP_SELF"];?>" method="post">
						<fieldset>
							<legend>Registro de usuarios</legend>
							<table border="0"  >

								<tr>
									<td>Usuario</td>
									<td>
										<input type="text" name="usuario" value="<?php if(isset($usuario)) print $usuario;?> " autofocus>
										<?php if(strlen($errorUsuario)>0) print '<span class="error">'.$errorUsuario.'</span>' ?>
									</td>
								</tr>
								<tr>
									<td>Nombre</td>
									<td>
										<input type="text" name="nombre"  value="<?php if(isset($nombre)) print $nombre; ?>">
										<?php if(strlen($errorNombre)>0) print '<span class="error">'.$errorNombre.'</span>' ?>
									</td>
								</tr>
								<tr>
									<td>Contraseña</td>
									<td>
										<input type="password" name="contrasena"  value="<?php if(isset($pass01)) print $pass01; ?>">
										<?php if(strlen($errorPass01)>0) print '<span class="error">'.$errorPass01.'</span>' ?>
									</td>
								</tr>
								<tr>
									<td>Repita la Contraseña</td>
									<td>
										<input type="password" name="contrasena2"  value="<?php if(isset($pass02)) print $pass02; ?>">
										$<?php if(strlen($errorPass02)>0) print '<span class="error">'.$errorPass02.'</span>' ?>
									</td>
								</tr>
								<tr>

									<td colspan="2" class="center">
											<img src="captcha.php" alt="logo" title="Imagen De Seguridad">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="center">
										<input type="text" name="captcha" > 

									</td>
								</tr>
							</table>
							<div class="center"><input type="submit" name="envio" value="Enviar"></div>
						</fieldset>
					</form>
				</div>
			</section>
			<?php include_once 'HTMLarchive.inc.php';?>;
		</section> 
		<?php include "HTMLfooter.inc.php";?>
	</body>
</html>