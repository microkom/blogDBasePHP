<?php
require 'funciones.php';
require 'AllClasses.php';
$accion = false;

//contador de errores de login
if(!isset($_SESSION['errorLogin'])){
	$_SESSION['errorLogin'] = 0;
}

$errorCounter=0;
$errorUsuario = $errorPass  = $show = "";

if(isset($_SESSION['usuario'])){
	$usuario=  $_SESSION['usuario']; 
	unset($_SESSION['errorLogin']);

	//destruir variable de sesión
	if(isset($_POST['logout'])){
		unset($_SESSION['usuario']);
	}
}

//captura datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//incremente errores de login
	$_SESSION['errorLogin']++;

	if (empty($_POST['usuario'])){
		$errorUsuario = "<br>* Debes escribir el usuario";
		$errorCounter++;
	}else{
		$usuario = trim($_POST['usuario']);
		if (!preg_match("/^[a-zA-Z0-9]*$/",$usuario)) {
			$errorUsuario = "<br>*Sólo letras y números sin espacios"; 
			$errorCounter++;
		}
	}
	if (empty($_POST['contrasena'])){
		$errorPass = "<br>* Debes escribir la contraseña";
		$errorCounter++;
	}else{
		$pass = $_POST['contrasena'];                    
	}
	if(isset($_POST['captcha'])){
		if($_SESSION['randomWord'] === $_POST['captcha']){
			$captcha = true;		 
		}else{
			$captcha = false;
			$errorCounter++;
		}
	}


	//Si no hay errores en el formulario se ejecuta esto
	if($errorCounter == 0 ){
		$existe = false;
		//ocultar el formulario si los datos son correctos
		$show = "hidden";

		//LOGIN verificacion
		$usuarioOrig = existeUsuario($usuario, $pass);
		if($usuarioOrig !== false){//para que el usuario aparezca tal y como es en la base de datos.

			//Guardar usuario en la sesión
			$_SESSION['usuario'] = $usuarioOrig;
			$userLogged = true;
			$_SESSION['errorLogin'] = 0;
		}else{
			$userLogged = false;
		}
	}
}


if(isset($_GET['idUsuario']))
	$idUsuario = $_GET['idUsuario'];

if(isset($_GET['accion']))
	$accion = $_GET['accion'];
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<?php require 'HTMLheadTag.inc.php'; ?>
		<title>Login Page</title>
	</head>
	<body>
		<?php include_once "HTMLheader.inc.php";?>
		<?php include_once "HTMLsearch.inc.php";?>

		<section class="main">    
			<section class="principal">

				<?php      
				if(isset($_SESSION['usuario'])){	
					print '
					<p class="menuL">
					<a href="editarCrear.php?accion=crear">Crear Entrada</a>
					<a href="login.php?accion=favoritoListar">Favoritos</a>
					<a href="login.php?accion=perfilUsuario">Perfil Usuario</a>
					</p>
					<br>';

					switch($accion){
						case 'favoritoListar' : print favoritoListar();$show = "hidden";break;
						case 'perfilUsuario' : print perfilUsuario();$show = "hidden";break;
						default:

							//Si el usuario ya está logueado se ejecuta esto


							//entradas de la base de datos que coinciden con el usuario logueado
							$entradaArr = readEntradasPorUsuarioDB($usuario);

							//Recorriendo las entradas para buscar las que coincidan con las del usuario
							foreach($entradaArr->entrada as $entrada){
								print $entrada->imprimirTituloEntrada();
							}
							//cambia la clase que usa la variable 'show' a 'hidden' para que no se muestre el formulario de logueo
							$show = "hidden";break;
					}
				}
				?>     

				<section id="form" class="<?= $show?>">
					<h1 class="center">LOGIN</h1>
					<aside class="error center">usuario de prueba: carlos; contraseña: 123</aside>
					<form class="front" action="<?=$_SERVER["PHP_SELF"];?>" method="post">
						<fieldset>
							<legend>Entrada</legend>
							<table border="0">

								<tr>
									<td>Usuario</td>
									<td>
										<input type="text" name="usuario" value="<?php if(isset($usuario)) print $usuario; ?>" autofocus>
										<?php if(strlen($errorUsuario)>0) print '<span class="error">'.$errorUsuario.'</span>' ?>
									</td>
								</tr>
								<tr>
									<td>Contraseña</td>
									<td>
										<input type="password" name="contrasena" value="<?php if(isset($pass)) print $pass; ?>">
										<?php if(strlen($errorPass)>0) print '<span class="error">'.$errorPass.'</span>' ?>
									</td>
								</tr>
								<?php 
								
								//mostrar captcha si hay error de login

								if($_SESSION['errorLogin'] > 0){
									print <<<HERE
									<tr>
									<td colspan="2" class="center">
										<img src="captcha.php" alt="logo" title="Imagen De Seguridad">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="center">
										<input type="text" name="captcha" ><label for="texto"></label>

									</td>
								</tr>
HERE;
								}
								?>

							</table>
							<div class="center"><input type="submit" name="envio" value="Enviar"></div>
						</fieldset>

					</form>
					<?php
					if(isset($userLogged) && $userLogged === false)
						print '<span class="center error">Usuario o contraseña erroneas. </span>';
					?>
				</section>
			</section>

			<?php include_once 'HTMLarchive.inc.php';?>

		</section>
		<?php include "HTMLfooter.inc.php";?>

	</body>
</html>